<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $hidden = ['pivot'];
    protected $fillable = ['name'];

    public function games() {
        return $this->belongsToMany(Game::class);
    }
}
