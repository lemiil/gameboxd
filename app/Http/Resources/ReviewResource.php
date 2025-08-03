<?php

// app/Http/Resources/ReviewResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'rating' => $this->rating,
            'status' => $this->status,
            'liked' => (bool)$this->liked,
            'created_at' => $this->created_at->toDateTimeString(),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'avatar_url' => $this->user->avatar_url ?? null,
            ],
        ];
    }
}
