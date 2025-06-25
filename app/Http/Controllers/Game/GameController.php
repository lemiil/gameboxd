<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GameController extends Controller
{
    public function show(Request $request)
    {
        $game = Game::with([
            'genres:name',
            'companies:name',
            'platforms:name',
            'screenshots',
            'themes:name',
        ])->where('slug', $request->slug)->firstOrFail();

        return Inertia::render('Game/GameShow', [
            'game' => $game,
        ]);
    }

    public function index(Request $request)
    {
        $games = Game::paginate(15);

        return Inertia::render('Game/GameIndex', [
            'games' => $games,
        ]);
    }


}
