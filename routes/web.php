<?php

use App\Http\Controllers\Review\LikeController;
use App\Http\Controllers\Review\ReviewController;
use App\Http\Controllers\Game\GameController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use MarcReichel\IGDBLaravel\Models\Game;

// TODO нормально расписать руты, закомментировать, добавить мидлвейр


Route::get('/', function () {
    return Inertia::render('Main');
})->name('main');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::post('/games/{game}/reviews', [ReviewController::class, 'store'])->name('review.store');
Route::get('/games/{game}/reviews/latest', [ReviewController::class, 'latest'])->name('review.latest');
Route::get('/games/{game}/reviews/popular', [ReviewController::class, 'popular'])->name('review.popular');


Route::get('/games', [GameController::class, 'index'])->name('game.index');
Route::get('/games/{slug}', [GameController::class, 'show'])->name('game.show');

Route::post('/reviews/like/{review}', [LikeController::class, 'like'])->name("review.like");
Route::post('/user/reviews/likes', [LikeController::class, 'status'])->name("review.likes.status");

/////


/////

require __DIR__ . '/auth.php';

/////

Route::get('/test', function () {
    $game = Game::where('name', 'Nightmare Kart: The Old Karts')->first();

    dd($game);
});
