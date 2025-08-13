<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\ReviewStoreRequest;
use App\Models\Game;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ReviewResource;

class ReviewController extends Controller
{


    public function latest(Game $game)
    {
        $reviews = Review::with('user')
            ->where('game_id', $game->id)
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        return ReviewResource::collection($reviews);
    }

    public function popular(Game $game)
    {
        $reviews = Review::with('user')
            ->where('game_id', $game->id)
            ->withCount('likers')
            ->orderByDesc('likers_count')
            ->limit(6)
            ->get();

        return ReviewResource::collection($reviews);
    }



    public function store(ReviewStoreRequest $request, Game $game)
    {
        $validated = $request->validated();

        $review = Review::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'game_id' => $game->id,
            ],
            [
                'text' => $validated['text'] ?? null,
                'rating' => $validated['rating'] ?? null,
                'status' => $validated['status'] ?? null,
                'liked' => $validated['liked'] ?? false,
            ]
        );

        return response()->json($review->load('user'));
    }
}
