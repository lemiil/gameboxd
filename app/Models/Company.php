<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['name'];

    public function games() {
        return $this->belongsToMany(Game::class);
    }
}
