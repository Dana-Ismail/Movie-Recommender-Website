<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\FavoriteMovie;
use App\Models\Movie;
class FavoriteController extends Controller
{
    public function showMovieList()
{
    $movies = Movie::all();
    $userFavorites = [];
    if (Auth::check()) {
        $user = Auth::user();
        $userFavorites = $user->favoriteMovies->pluck('movie_id')->toArray();
    }

    return view('movies', compact('movies', 'userFavorites'));
}
    public function addFavorite(Request $request)
    {
        $movieId = $request->input('movie_id');
        $userId = auth()->id(); 

        // Check if the movie is already in favorites for the user
        $isFavorite = FavoriteMovie::where('user_id', $userId)
            ->where('movie_id', $movieId)
            ->exists();

        if (!$isFavorite) {
            // If movie is not in favorites, add it
            $favoriteMovie = new FavoriteMovie();
            $favoriteMovie->user_id = $userId;
            $favoriteMovie->movie_id = $movieId;
            $favoriteMovie->save();
            return response()->json(['success' => true]);
        } else {
            // Return a failure response if the movie is already in favorites
            return response()->json(['success' => false]);
        }
    }

    public function removeFavorite(Request $request)
    {
        $movieId = $request->input('movie_id');
        $userId = auth()->id(); // Assuming you are using Laravel's built-in authentication

        // Find the favorite movie for the user
        $favoriteMovie = FavoriteMovie::where('user_id', $userId)
            ->where('movie_id', $movieId)
            ->first();

        if ($favoriteMovie) {
            // If movie is in favorites, remove it
            $favoriteMovie->delete();
            return response()->json(['success' => true]);
        } else {
            // Return a failure response if the movie is not in favorites
            return response()->json(['success' => false]);
        }
    }
    
}
