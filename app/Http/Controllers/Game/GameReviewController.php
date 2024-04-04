<?php

namespace App\Http\Controllers\Game;

use App\Http\Requests\LikeReviewRequest;
use App\Http\Requests\ReviewRequest;
use App\Models\Review;
use App\Models\ReviewLike;
use App\Services\GoogleCloudService;

class GameReviewController extends GameBaseController
{
    protected $googleCloudService;

    public function __construct(GoogleCloudService $googleCloudService)
    {
        $this->googleCloudService = $googleCloudService;
    }

    public function getReviews($id)
    {
        $reviewsData = Review::where('igdb_id', $id)->with(['likes', 'comments', 'user'])->get();
        if($reviewsData->isEmpty()) {
            return [];
        }
        return $reviewsData->whereNotNull('content');
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
