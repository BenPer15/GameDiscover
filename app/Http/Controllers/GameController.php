<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameReviewRequest;
use App\Models\UserGame;
use App\Services\GameService;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Inertia\Inertia;
use MarcReichel\IGDBLaravel\Models\Game;

class GameController extends Controller
{
    private $gameService;
    protected $auth;

    public function __construct(GameService $gameService, Guard $auth)
    {
        $this->gameService = $gameService;
        $this->auth = $auth;
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

    public function show($id)
    {
        $game = $this->gameService->find((int)$id);
        $user = $this->auth->user();
        if ($user && $game) {
            $gameReviewedByUser = $user->games->where('igdb_id', $game->id)->first();
            $game->user = $gameReviewedByUser;
        }
        return Inertia::render('Games/Show', ['game' => $game]);
    }

    public function storeStatus(GameReviewRequest $request)
    {
        $validated = $request->validated();
        $user = $this->auth->user();
        UserGame::create(
            [
                'user_id' => $user->id,
                'igdb_id' => $validated['id'],
                'status' => $validated['status'],
            ]
        );

    }

    public function updateStatus(GameReviewRequest $request, $id)
    {
        $userGame = UserGame::findOrFail($id);
        $validated = $request->validated();
        $userGame->update(
            [
                'status' => $validated['status'],
            ]
        );
    }


    public function storeReview(GameReviewRequest $request)
    {
        $gameReview = $request->validated();
        $user = $this->auth->user();
        $igdbGame = $this->gameService->find((int)$gameReview['id']);
        UserGame::create(
            [
                'user_id' => $user->id,
                'igdb_id' => $igdbGame->id,
                'rating' => $gameReview['rating'] ?? null,
                'review' => $gameReview['review'] ?? null,
                'is_favorite' => $gameReview['is_favorite'] ?? false,
                'status' => $gameReview['status'] ?? null,
            ]
        );
        return redirect()->route('games.show', ['id' => $igdbGame->id]);
    }

    public function updateReview(GameReviewRequest $request, $id)
    {
        $gameReview = $request->validated();
        $userGame = UserGame::find($id);

        $userGame->rating = $gameReview['rating'] ?? $userGame->rating;
        $userGame->review = $gameReview['review'] ?? $userGame->review;
        $userGame->is_favorite = $gameReview['is_favorite'] ?? $userGame->is_favorite;
        $userGame->status = $gameReview['status'];
        $userGame->save();
        return redirect()->route('games.show', ['id' => $userGame->igdb_id]);
    }
}
