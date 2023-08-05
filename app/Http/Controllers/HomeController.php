<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrendingMovie;
use App\Models\Movie;
use App\Models\PopularMovie;

class HomeController extends Controller
{
    public function index()
    {
        $trendingMovies = TrendingMovie::all();
        $popularMovies = PopularMovie::all();
        $moviesData = [];
        $popularMoviesData = [];
    
        foreach ($trendingMovies as $trendingMovie) {
            $movie = $trendingMovie->movie;
            if ($movie) {
                $moviesData[] = [
                    'id' => $movie->id,
                    'Poster' => $movie->Poster,
                    'Image' => $movie->Image,
                    'Title' => $movie->Title,
                    'Type' => $movie->Type,
                    'Year' => $movie->Year,
                    'Tags_1' => $movie->Tags_1,
                    'Tags_2' => $movie->Tags_2    
                 ];
            }
        }
    
        foreach ($popularMovies as $popularMovie) {
            $movie = $popularMovie->movie;
            if ($movie) {
                $popularMoviesData[] = [
                    'id' => $movie->id,
                    'Poster' => $movie->Poster,
                    'Title' => $movie->Title,
                    'Summary' => $movie->Summary
                ];
            }
        }
    
        return view('home', ['moviesData' => $moviesData, 'popularMoviesData' => $popularMoviesData]);
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
}    