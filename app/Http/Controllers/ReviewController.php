<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
class ReviewController extends Controller
{
    public function store(Request $request, Movie $movie)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            $request->validate([
                'comment' => 'required|string',
            ]);
    
            // Get the currently logged-in user
            $user = Auth::user();
    
            // Save the review to the database
            $review = new Review([
                'user_id' => $user->id,
                'movie_id' => $movie->id,
                'comment' => $request->input('comment'),
            ]);
    
            $review->save();
    
            return redirect()->back()->with('success', 'Review added successfully!');
        } else {
            // Redirect the user to the login page or handle the case where the user is not logged in.
            return redirect()->route('login')->with('error', 'You need to log in to add a review.');
        }
    }
}
