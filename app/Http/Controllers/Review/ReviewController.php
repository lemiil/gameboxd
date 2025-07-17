<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\ReviewStoreRequest;
use App\Models\Game;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(ReviewStoreRequest $request, Game $game)
    {
        $user = Auth::user();
        $validatedData = $request->validated();
        $validatedData['user_id'] = $user->id;
        Review::create($validatedData);
        return $game;
    }
}
