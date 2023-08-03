<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Movie extends Model
{
    protected $table = 'movies';

    /**
     * Get trending movies
     *
     * @param Builder $query
     * @return void
     */
    public function scopeTrending(Builder $query){
        $query->where('is_trending',1);
    }

    
    public function genre1()
    {
        return $this->belongsTo(Genre::class, 'Genre1');
    }

    public function genre2()
    {
        return $this->belongsTo(Genre::class, 'Genre2');
    }

    public function genre3()
    {
        return $this->belongsTo(Genre::class, 'Genre3');
    }
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorite_movies', 'movie_id', 'user_id');
    }

    
    // public function users()
    // {
    //     return $this->belongsToMany(User::class, 'favorite_movies', 'movie_id', 'user_id');
    // }
 
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorite_movies', 'movie_id', 'user_id')
            ->withTimestamps();
    }
}

