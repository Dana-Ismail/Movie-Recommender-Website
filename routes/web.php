<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecommenderController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index']);
Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movie.show');
Route::get('/search', [MovieController::class, 'search'])->name('search');
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::get('signup', [AuthController::class, 'showSignupForm'])->name('signup');
Route::post('signup', [AuthController::class, 'signup'])->name('signup.post');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile');
    Route::post('/addFavoriteMovie', [ProfileController::class, 'addFavoriteMovie'])->name('addFavoriteMovie');
});
Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
Route::post('/add-favorite', [FavoriteController::class, 'addFavorite'])->name('add.favorite');
Route::post('/remove-favorite', [FavoriteController::class, 'removeFavorite'])->name('remove.favorite');
Route::post('/movies/{movie}/reviews', [ReviewController::class, 'store'])->name('reviews.store');



