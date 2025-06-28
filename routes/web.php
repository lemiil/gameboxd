<?php

use App\Http\Controllers\Game\GameController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use MarcReichel\IGDBLaravel\Models\Game;

Route::get('/', function () {
    return Inertia::render('Main');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/games', [GameController::class, 'index'])->name('game.index');
Route::get('/games/{slug}', [GameController::class, 'show'])->name('game.show');


/////

require __DIR__.'/auth.php';

/////

Route::get('/test', function() {
    $game = Game::where('name', 'Nightmare Kart: The Old Karts')->first();

    dd($game);
});
