<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Screenshot extends Model
{
    protected $fillable = ['game_id', 'url'];

    public function game() {
        return $this->belongsTo(Game::class);
    }
}
