<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function like(Review $review)
    {
        $user = Auth::user();

        try {
            if ($user->hasLiked($review)) {
                $user->unlike($review);
                return response()->json(['message' => 'Unliked'], 200);
            } else {
                $user->like($review);
                return response()->json(['message' => 'Liked'], 200);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}
