<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    protected $fillable = ['igdb_id', 'name'];

    public function games()
    {
        return $this->belongsToMany(Game::class);
    }
}
