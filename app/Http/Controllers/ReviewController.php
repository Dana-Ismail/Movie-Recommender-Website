<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use Illuminate\Support\Facades\Http; 

class ReviewController extends Controller
{
    public function store(Request $request, Movie $movie)
        {
            // check if the user is authenticated
            if (Auth::check()) {
                $request->validate([
                    'comment' => 'required|string',
                ]);
    
                // get the currently logged-in user
                $user = Auth::user();
    
                // save review  database
                $review = new Review([
                    'user_id' => $user->id,
                    'movie_id' => $movie->id,
                    'comment' => $request->input('comment')
                ]);
    
                $review->save();
                return redirect()->route('movies.show', ['id' => $movie->id])->with('success', 'Review added successfully!');
                // return redirect()->back()->with('success', 'Review added successfully!');
        } else {
            // redirect the user to the login page when user is not logged in.
            return redirect()->route('login')->with('error', 'You need to log in to add a review.');
            }
        }
    }
