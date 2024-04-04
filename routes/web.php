<?php

use App\Http\Controllers\Game\GameApiController;
use App\Http\Controllers\Game\GameReviewController;
use App\Http\Controllers\Game\GameUserInteractionController;
use App\Http\Controllers\Game\GameViewController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'show'])->name('home.index');
Route::get('/search', [GameViewController::class, 'searchAll'])->name('games.searchAll');
Route::get('/games/{id}', [GameViewController::class, 'show'])->name('games.show');
Route::get('/advanced_search', [GameViewController::class, 'advancedSearch'])->name('games.advanced_search');
Route::get('/mature-content', [GameViewController::class, 'matureContent'])->name('games.matureContent');

Route::prefix('api')->group(function () {
    Route::get('/search', [GameApiController::class, 'search'])->name('games.search');
    Route::get('/mature-content', [GameApiController::class, 'getMatureContent'])->name('api.games.matureContent');
    Route::get('/games/{id}', [GameApiController::class, 'getGame'])->name('api.games.show');
    Route::get('/games/{id}/background', [GameApiController::class, 'getBackgroundImage'])->name('api.games.background');
    Route::get('/games/{id}/medias', [GameApiController::class, 'getMedias'])->name('api.games.medias');
    Route::get('/games/{id}/user-interactions', [GameUserInteractionController::class, 'getUserInteractions'])->name('api.games.userInteractions');
});

Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/games/{id}/reviews', [GameReviewController::class, 'getReviews'])->name('api.games.reviews');
    Route::post('/review', [GameReviewController::class, 'storeReview'])->name('games.storeReview');
    Route::put('/review/{id}', [GameReviewController::class, 'updateReview'])->name('games.updateReview');
    Route::delete('/review/{id}', [GameReviewController::class, 'destroyReview'])->name('games.destroyReview');
    Route::post('/like', [GameReviewController::class, 'storeLikeReview'])->name('games.review.storeLike');
    Route::post('/like/{id}', [GameReviewController::class, 'destroyLikeReview'])->name('games.review.destroyLike');
    Route::post('/status', [GameUserInteractionController::class, 'storeUserGameInteraction'])->name('games.storeStatus');
    Route::put('/status/{id}', [GameUserInteractionController::class, 'updateUserGameInteraction'])->name('games.updateStatus');
});

Route::post('/settings/birthdate', [ProfileController::class, 'storeBirthdate'])->name('settings.profil.birthdate');
Route::middleware('auth')->prefix('settings')->name('settings.')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/security', [SettingsController::class, 'security'])->name('security.edit');
    Route::get('/advanced', [SettingsController::class, 'advanced'])->name('advanced');
});

require __DIR__.'/auth.php';
