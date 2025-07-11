<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $hidden = ['pivot'];
    protected $fillable = ['name'];

    public function games() {
        return $this->belongsToMany(Game::class);
    }
}
