<?php

namespace App\Http\Controllers;

use App\Http\Requests\LikeReviewRequest;
use App\Http\Requests\ReviewRequest;

use App\Http\Requests\UserGameInteractionRequest;
use App\Models\Review;
use App\Models\ReviewLike;
use App\Models\User;
use App\Models\UserGameInteraction;
use App\Services\GameService;
use App\Services\GoogleCloudService;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class GameController extends Controller
{
    protected $gameService;
    protected $auth;
    protected $googleCloudService;


    public function __construct(GameService $gameService, Guard $auth, GoogleCloudService $googleCloudService)
    {
        $this->gameService = $gameService;
        $this->auth = $auth;
        $this->googleCloudService = $googleCloudService;
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $games = $this->gameService->search($query);
        return response()->json($games);
    }

    public function searchAll(Request $request)
    {
        $query = $request->input('q');
        $sort = $request->input('sort');
        $asc = $request->input('asc');

        $games = $this->gameService->searchGame($query, $sort, $asc);
        return Inertia::render('Games/Index', compact('games', 'query', 'sort', 'asc'));
    }

    public function advancedSearch(Request $request)
    {
        // $searchTerm = $request->input('q');
        // $games = $this->gameService->searchGame($searchTerm);
        // return response()->json($games);
    }

    public function show(Request $request, $id)
    {
        $age = session('user_age', 0);
        $game = $this->gameService->find((int)$id);
        if($game->matureContent && ($age < 18 || $age === 0)) {
            return Inertia::render('MatureForm', ['game' => $game, 'age' => $age, 'matureSynopsis' => $game->matureContent['synopsis']]);
        }
        return Inertia::render('Games/Show', ['game' => $game]);
    }

    public function storeUserGameInteraction(UserGameInteractionRequest $request)
    {
        $validated = $request->validated();
        $user = $this->auth->user();
        UserGameInteraction::create(
            [
                'user_id' => $user->id,
                'igdb_id' => $validated['igdb_id'],
                'status' => $validated['status'] ?? null,
                'is_favorite' => $validated['is_favorite'] ?? false,
            ]
        );
    }

    public function updateUserGameInteraction(UserGameInteractionRequest $request, $id)
    {
        $userGameInteraction = UserGameInteraction::findOrFail($id);
        $validated = $request->validated();
        $userGameInteraction->update(
            [
                'status' => $validated['status'],
                'is_favorite' => $validated['is_favorite'] ?? $userGameInteraction->is_favorite,
            ]
        );
    }


    public function storeReview(ReviewRequest $request)
    {
        $validated = $request->validated();
        $sentiment_score = $this->googleCloudService->reviewAnalyseSentiment($validated['content']);
        $user = $this->auth->user();
        $igdbGame = $this->gameService->find((int)$validated['igdb_id']);

        if (!$igdbGame) {
            return redirect()->back()->with([
                'type' => 'error',
                'message' => 'Game not found',
            ]);
        }
        Review::create(
            [
                'user_id' => $user->id,
                'igdb_id' => $validated['igdb_id'],
                'content' => $validated['content'] ?? null,
                'platform' => $validated['platform'] ?? null,
                'isSpoiler' => $validated['isSpoiler'] ?? false,
                'sentiment_score' => $sentiment_score,
            ]
        );
        return redirect()->back()->with([
            'type' => 'success',
            'message' => 'Your review has been added successfully',
        ]);
    }

    public function updateReview(ReviewRequest $request, $id)
    {
        $validated = $request->validated();
        $review = Review::find($id);

        $hasReview = !!$validated['content'];
        $sentiment_score =  $hasReview ? $this->googleCloudService->reviewAnalyseSentiment($validated['content']) : 0;

        $review->sentiment_score = $hasReview ? $sentiment_score : $review->sentiment_score;
        $review->content = $validated['content'] ?? $review->content;
        $review->save();
        return redirect()->route('games.show', ['id' => $review->igdb_id]);
    }

    public function destroyReview($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();
        return redirect()->back()->with([
            'type' => 'success',
            'message' => 'Review deleted successfully',
        ]);
    }

    public function storeLikeReview(LikeReviewRequest $request)
    {
        $validated = $request->validated();
        $user = $this->auth->user();
        $review = Review::find($validated['review_id']);
        $review->likes()->create(['user_id' => $user->id]);

        return redirect()->back()->with([
            'type' => 'success',
            'message' => 'Like added successfully',
        ]);

    }

    public function destroyLikeReview($id)
    {

        $reviewLike = ReviewLike::findOrFail($id);
        $reviewLike->delete();
        return redirect()->back()->with([
            'type' => 'success',
            'message' => 'Like deleted successfully',
        ]);


    }


}
