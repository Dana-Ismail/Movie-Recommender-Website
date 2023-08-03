<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TrendingMovie extends Model
{
    protected $table = 'trending_movies';

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
