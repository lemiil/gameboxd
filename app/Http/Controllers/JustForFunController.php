<?php

namespace App\Http\Controllers;

use MarcReichel\IGDBLaravel\Models\Game;

class JustForFunController extends Controller
{
    public function whatsup()
    {
        $games = Game::where('name', 'Fortnite')->get();
        dd($games);
    }
}
