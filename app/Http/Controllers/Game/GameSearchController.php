<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GameSearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json([]);
        }

        $games = Game::query()
            ->where('name', 'ILIKE', "%{$query}%")
            ->limit(20)
            ->get();


        return response()->json($games);
    }

}
