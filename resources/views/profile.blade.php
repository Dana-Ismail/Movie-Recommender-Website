<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel='stylesheet' href='https://static.fontawesome.com/css/fontawesome-app.css'>
    <link href="https://fonts.googleapis.com/css?family=Cardo:400i|Rubik:400,700&display=swap" rel="stylesheet">
    <link rel='stylesheet' href='https://pro.fontawesome.com/releases/v5.9.0/css/all.css'>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400&amp;display=swap'>
    <link rel="stylesheet" href="{{ url('c.css') }}">
    <link rel="stylesheet" href="{{ url('navbar.css') }}">
    <link rel="stylesheet" href="{{ url('search.css') }}">
    <link rel="stylesheet" href="{{ url('text.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style> 
    body {
        color: white;
    }
    </style>
</head>

<body>
    <div class="container">
        @if (Auth::check())
        <p>

  <span>
  Welcome, {{ $user->name }}
  </span>
</p>

            <div class="section">
                <h2>Favorite Movies</h2>
                <ul>
                    @forelse ($favoriteMovies as $movie)
                        <li>{{ $movie->Title }}</li>
                    @empty
                        <li>No favorite movies found.</li>
                    @endforelse
                </ul>
            </div>

            <div class="section">
                <h2>Recommended Movies</h2>
                <ul>
                    @forelse ($recommendedMovies as $movie)
                        <li>{{ $movie['Title'] }}</li>
                    @empty
                        <li>No recommended movies found.</li>
                    @endforelse
                </ul>
            </div>

            <div class="section">
                <h2>Add Movie by Title</h2>
                <form action="{{ route('addFavoriteMovie') }}" method="POST">
                    @csrf
                    <input type="text" name="movie_title" placeholder="Enter movie title">
                    <button type="submit">Add to Favorites</button>
                </form>
            </div>

            <div class="section">
                <a href="{{ route('logout') }}">Logout</a>
            </div>
        @else
            <div class="section">
                <p>Please <a href="{{ route('login') }}">login</a> or <a href="{{ route('signup') }}">sign up</a> to access your
                    profile.</p>
            </div>
        @endif
    </div>
    <script src="{{ url('recommender.js') }}"></script>
</body>

</html>
