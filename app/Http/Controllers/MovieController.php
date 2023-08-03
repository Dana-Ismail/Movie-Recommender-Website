<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Genre;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class MovieController extends Controller
{

    public function index()
{
    // get movies data
    $allMovies = Movie::all();
    // calculate the number of movies per page (3 rows * 5 movies per row = 15 movies per page)
    $moviesPerPage = 15;
    // use the LengthAwarePaginator to create a new paginator instance
    $moviesPaginator = new LengthAwarePaginator(
        $allMovies->forPage(1, $moviesPerPage), // first page with 15 movies
        $allMovies->count(), // number of movies
        $moviesPerPage, // movies per page
        Paginator::resolveCurrentPage(), // current page - determined by Laravel -
        ['path' => Paginator::resolveCurrentPath()] // URL path for pagination links
    );
    
    $genres = Genre::all();
    $years = Movie::distinct('Year')->pluck('Year');
    return view('movies', ['movies' => $moviesPaginator, 'genres' => $genres, 'years' => $years]);

}

    public function show($id)
    {
        $movie = Movie::findOrFail($id);
        $genreIds = [
            $movie->Genre1,
            $movie->Genre2,
            $movie->Genre3,
        ];

        $genres = Genre::whereIn('GenreID', $genreIds)->pluck('Genre')->toArray();
        return view('show', ['movie' => $movie, 'genres' => $genres]);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $movies = Movie::query()
            ->where('Title', 'LIKE', "%{$keyword}%")
            ->orWhere('Tags_1', 'LIKE', "%{$keyword}%")
            ->orWhere('Tags_2', 'LIKE', "%{$keyword}%")
            ->orWhere('Type', 'LIKE', "%{$keyword}%")
            ->orWhereHas('genre1', function ($query) use ($keyword) {
                $query->where('Genre', 'LIKE', "%{$keyword}%");
            })
            ->orWhereHas('genre2', function ($query) use ($keyword) {
                $query->where('Genre', 'LIKE', "%{$keyword}%");
            })
            ->orWhereHas('genre3', function ($query) use ($keyword) {
                $query->where('Genre', 'LIKE', "%{$keyword}%");
            })
            ->orWhere(function ($query) use ($keyword) {
                $query->where('Actors_1', 'LIKE', "%{$keyword}%")
                    ->orWhere('Actors_2', 'LIKE', "%{$keyword}%")
                    ->orWhere('Actors_3', 'LIKE', "%{$keyword}%")
                    ->orWhere('Actors_4', 'LIKE', "%{$keyword}%");
            })
            ->get();

        return view('search', compact('movies', 'keyword'));
    }

    public function filter(Request $request)
    {
        $genre = $request->input('genre');
        $year = $request->input('year');
    
        $query = Movie::query();
    
        if ($genre) {
            $query->where('genre', $genre);
        }
    
        if ($year) {
            $query->where('year', $year);
        }
    
        $movies = $query->get();
    
        return response()->json(['movies' => $movies]);
    }
}
