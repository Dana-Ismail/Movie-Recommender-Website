<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    
    public function showProfile()
    {
        $recommendedMoviesDetails = [];
        if (Auth::check()) {
            // user is logged in
            $user = Auth::user();
            // eager load the 'favorites' relationship for the user
            $userWithFavorites = User::with('favorites')->find($user->id);
            $favoriteMovies = $userWithFavorites->favorites;
            // get movie recommendations using the recommender script
            $recommendedMovies = $this->getRecommendedMovies($user->id);
            foreach ($recommendedMovies as $movieId) {
                $movie = Movie::find($movieId);

                if ($movie) {
                    $genre1 = $movie->Genre1 ? Genre::find($movie->Genre1) : null;
                    $genre2 = $movie->Genre2 ? Genre::find($movie->Genre2) : null;
                    $genre3 = $movie->Genre3 ? Genre::find($movie->Genre3) : null;
                    $recommendedMoviesDetails[] = [
                        'id' => $movieId,
                        'Poster' => $movie->Poster,
                        'Title' => $movie->Title,
                        'Type' => $movie->Type,
                        'genre1' => $genre1,
                        'genre2' => $genre2,
                        'genre3' => $genre3
                    ];
                }
            }
            $uniqueGenres = Genre::whereIn('id', array_column($recommendedMoviesDetails, 'genre1'))
            ->orWhereIn('id', array_column($recommendedMoviesDetails, 'genre2'))
            ->orWhereIn('id', array_column($recommendedMoviesDetails, 'genre3'))
            ->distinct()
            ->pluck('genre')
            ->toArray();
            // get all movies from the database
            $movies = Movie::all();
            // get the IDs of favorite movies
            $favoriteMovieIds = $favoriteMovies->pluck('id')->toArray();
            // get favorite movie titles based on favorite movies ids
            $favoriteMovieTitles = $movies->whereIn('id', $favoriteMovieIds)->pluck('Title')->toArray();
            return view('profile', compact('user', 'favoriteMovies', 'recommendedMovies', 'movies', 'recommendedMoviesDetails','uniqueGenres'));
        } else {
            // user is not logged in
            return view('login');
        }
    }

    

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $movies = Movie::where('Title', 'LIKE', "%{$keyword}%")->get();
        return view('search_results', compact('movies'));
    }

    public function addFavoriteMovie(Request $request)
    {
        $user = Auth::user();
        $movieTitle = $request->input('movie_title');

        // find the movie by title
        $movie = Movie::where('Title', $movieTitle)->first();

        if ($movie) {
            // add the movie to the user's favorites without duplicates
            $user->favorites()->syncWithoutDetaching([$movie->id]);
            // get the updated list of favorite movies directly
            $favorites = $user->favorites()->get();
            // get movie recommendations using the recommender python script
            $recommendedMovies = $this->getRecommendedMovies($user->id);
            // get all movies from the database
            $movies = Movie::all();

            return redirect()->route('profile', compact('favorites', 'recommendedMovies', 'movies'));
        } else {
            return redirect()->route('profile')->with('error', 'Movie not found. Please check the title and try again.');
        }
    }

    private function getRecommendedMovies($user_id)
    {
        $pythonAPI = "http://localhost:8080/recommendations/{$user_id}";

        // Create a curl session to make an HTTP request to the Python API
        $curlSession = curl_init($pythonAPI);
        # CURL option to return the response as a string
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curlSession);
        $httpStatusCode = curl_getinfo($curlSession, CURLINFO_HTTP_CODE);
        curl_close($curlSession);

        if ($httpStatusCode === 200) {
            $recommendedMovies = json_decode($response, true);
            return $recommendedMovies;
        } else {
            // Handle the error gracefully
            return [];
        }
    }



public function addFavoriteMovieFromMoviesPage(Request $request)
{
    $user = Auth::user();
    $movieId = $request->input('movie_id');

    // Check if the movie ID exists in the database
    $movie = Movie::find($movieId);

    if ($movie) {
        // Add the movie to the user's favorites without duplicates
        $user->favorites()->syncWithoutDetaching([$movie->id]);
        // Redirect back to the movies page with a success message
        return redirect()->route('movies')->with('success', 'Movie added to favorites successfully.');
    } else {
        // Redirect back to the movies page with an error message
        return redirect()->route('movies')->with('error', 'Movie not found. Please try again.');
    }
}

}
        // // Define the path to the Python script
        // $python_script = base_path('python/movie_recommender.py');
    
        // // Build the shell command to run the Python script
        // $command = "python {$python_script} {$user_id}";
    
        // // Execute the shell command and capture the output
        // exec($command, $output, $return_var);
    
        // // Check if the command executed successfully
        // if ($return_var !== 0) {
        //     throw new \RuntimeException("Failed to execute Python script: {$python_script}");
        // }
    
        // // Get the recommended movie IDs from the Python script output
        // $recommended_movie_ids = json_decode(implode('', $output), true);
    
        // // Check if the recommended_movie_ids is not null
        // if (is_array($recommended_movie_ids) && count($recommended_movie_ids) > 0) {
        //     // Fetch movie names from the database based on the IDs
        //     $recommendedMovies = Movie::whereIn('id', $recommended_movie_ids)->pluck('Title')->toArray();
        // } else {
        //     // Set recommendedMovies as an empty array if no recommendations are returned
        //     $recommendedMovies = [];
        // }
    
        // return $recommendedMovies;
    