<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Validator;

class RecommenderController extends Controller
{
    public function getFavoriteMovies($userId)
    {
        return DB::table('favorite_movies')
            ->where('user_id', $userId)
            ->pluck('movie_id')
            ->toArray();
    }

    public function getMovieRecommendations($userId)
    {
        
        $validator = Validator::make(['user_id' => $userId], [
            'user_id' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            // return new JsonResponse([]);
            return response()->json([]);
        }
        $pythonAPI = "http://localhost:8080/recommendations/{$userId}";
        # create curl session
        $CurlSession = curl_init($pythonAPI);
        curl_setopt($CurlSession, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($CurlSession);
        curl_close($CurlSession);

        $recommendedMovies = json_decode($response, true);
        return response()->json($recommendedMovies);
    }


    public function profile()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $favoriteMovies = $this->getFavoriteMovies($user->id);

            return view('profile', compact('user', 'favoriteMovies'));
        } else {
            return view('profile'); // when user is not logged in.
        }
    }
}
