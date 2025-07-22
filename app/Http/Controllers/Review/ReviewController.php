<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\ReviewStoreRequest;
use App\Models\Game;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(ReviewStoreRequest $request, Game $game)
    {
        $validatedData = $request->validated();
        Review::updateOrCreate($validatedData);
        return $game;
    }
}
