<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{

    protected $fillable = ['name', 'release_date', 'cover_url', 'artwork_url', 'summary'];

    public function companies() {
        return $this->belongsToMany(Company::class);
    }

    public function platforms() {
        return $this->belongsToMany(Platform::class);
    }

    public function genres() {
        return $this->belongsToMany(Genre::class);
    }

    public function themes() {
        return $this->belongsToMany(Theme::class);
    }

    public function screenshots() {
        return $this->hasMany(Screenshot::class);
    }

}
