<?php

namespace App\Http\Controllers\Game;

use App\Http\Requests\LikeReviewRequest;
use App\Http\Requests\ReviewRequest;
use App\Models\Review;
use App\Models\ReviewLike;
use App\Services\GameService;
use App\Services\GoogleCloudService;
use Illuminate\Contracts\Auth\Guard;

class GameReviewController extends GameBaseController
{
    protected $googleCloudService;

    public function __construct(GameService $gameService, Guard $auth, GoogleCloudService $googleCloudService)
    {
        parent::__construct($gameService, $auth);
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
        $review = Review::create(
            [
                'user_id' => $user->id,
                'igdb_id' => $validated['igdb_id'],
                'content' => $validated['content'] ?? null,
                'platform' => $validated['platform'] ?? null,
                'isSpoiler' => $validated['isSpoiler'] ?? false,
                'sentiment_score' => $sentiment_score,
            ]
        );
        $review->load(['likes', 'comments', 'user']);
        return response()->json([
                'type' => 'success',
                'message' => 'Your review has been added successfully',
                'review' => $review,
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
        return response()->json([
               'type' => 'success',
               'message' => 'Your review has been updated successfully',
               'review' => $review,
           ]);
    }

    public function destroyReview($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();
        return response()->json([
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

        return response()->json([
             'type' => 'success',
             'message' => 'Like added successfully',
         ]);

    }

    public function destroyLikeReview($id)
    {
        $reviewLike = ReviewLike::findOrFail($id);
        $reviewLike->delete();
        return response()->json([
             'type' => 'success',
             'message' => 'Like deleted successfully',
         ]);
    }
}
