<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopularMovie extends Model
{
    protected $table = 'popular_movies';

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }
}
