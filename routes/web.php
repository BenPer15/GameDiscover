<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'show'])->name('home.index');
Route::get('/api/search', [GameController::class, 'search'])->name('games.search');
Route::get('/search', [GameController::class, 'searchAll'])->name('games.searchAll');
Route::get('/games/{id}', [GameController::class, 'show'])->name('games.show');
Route::get('/advanced_search', [GameController::class, 'advancedSearch'])->name('games.advanced_search');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
