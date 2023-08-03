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
                    'Title' => $movie->Title,
                    'Type' => $movie->Type,
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
}    