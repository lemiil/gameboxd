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
        $game = Game::where('slug', $request->slug)->firstOrFail();

        return Inertia::render('Game/Game', [
            'game' => $game,
        ]);
    }

}
