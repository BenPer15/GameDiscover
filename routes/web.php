<?php

use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'show'])->name('home.index');
Route::get('/api/search', [GameController::class, 'search'])->name('games.search');
Route::get('/search', [GameController::class, 'searchAll'])->name('games.searchAll');
Route::get('/games/{id}', [GameController::class, 'show'])->name('games.show');
Route::get('/advanced_search', [GameController::class, 'advancedSearch'])->name('games.advanced_search');

Route::middleware('auth')->prefix('api')->group(function () {
    Route::post('/review', [GameController::class, 'storeReview'])->name('games.storeReview');
    Route::put('/review/{id}', [GameController::class, 'updateReview'])->name('games.updateReview');
    Route::delete('/review/{id}', [GameController::class, 'destroyReview'])->name('games.destroyReview');
    Route::post('/status', [GameController::class, 'storeUserGameInteraction'])->name('games.storeStatus');
    Route::put('/status/{id}', [GameController::class, 'updateUserGameInteraction'])->name('games.updateStatus');
    Route::post('/like', [GameController::class, 'storeLikeReview'])->name('games.review.storeLike');
    Route::post('/like/{id}', [GameController::class, 'destroyLikeReview'])->name('games.review.destroyLike');
});


Route::middleware('auth')->prefix('settings')->name('settings.')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/security', [SettingsController::class, 'security'])->name('security.edit');
    Route::get('/advanced', [SettingsController::class, 'advanced'])->name('advanced');
    Route::post('/birthdate', [ProfileController::class, 'storeBirthdate'])->name('profil.birthdate');
});

require __DIR__.'/auth.php';
