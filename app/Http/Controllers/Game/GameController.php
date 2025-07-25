<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Review;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $user = Auth::user();

        $userReview = null;
        if ($user) {
            $userReview = Review::where('user_id', $user->id)
                ->where('game_id', $game->id)
                ->first();
        }

        return Inertia::render('Game/GameShow', [
            'game' => $game,
            'user' => $user,
            'userReview' => $userReview,
        ]);
    }


    public function index(Request $request)
    {
        $games = Game::select('id', 'name', 'slug', 'cover_url')
            ->paginate(30)
            ->withQueryString();

        return Inertia::render('Game/GameIndex', [
            'games' => $games,
        ]);
    }


}
