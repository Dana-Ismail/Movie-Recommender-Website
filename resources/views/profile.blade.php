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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4"
    crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="{{ url('navbar.css') }}">
    <link rel="stylesheet" href="{{ url('logo.css') }}">
    <link rel="stylesheet" href="{{ url('text.css') }}">
    <link rel="stylesheet" href="{{ url('favorite.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style> 
    body {
        color: white;
    }
    </style>
</head>

<body>

  <!-- navigation -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light" style="background-color:black !important;">
  <a href="/" class="triangle-container navbar-brand">
    <div class="triangle-wrapper">
      <div class="triangle triangle-1"></div>
      <div class="triangle triangle-2"></div>
      <div class="triangle triangle-3"></div>
      <div class="triangle triangle-4"></div>
      <div class="triangle triangle-5"></div>
    </div>
  </a>

 
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
    aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item ">
        <a class="nav-link " href="/">HOME</a>
      </li>
      <li class="nav-item ">
        <a class="nav-link " href="/movies">Movies
        </a>
      </li>
      <li class="nav-item active" >
        <a class="nav-link la" href="/profile">PROFILE</a>
        <span class="sr-only">(current)</span>
      </li>
    </ul>
    
  </div>
</nav>

    <div class="container">
        @if (Auth::check())

        <p>
  <span>
  Welcome, {{ $user->name }}
  </span>
</p>
  </br>
  </br>
</br>
</br>
<div class="section" style="position: absolute !important; left:50px !important; top:30% !important; ">
</br> </br>
                <h2>Add Movie by Title</h2>
                <form action="{{ route('addFavoriteMovie') }}" method="POST">
                    @csrf
                    <div class="preview-zone">
                    <section class="search" role="search">
                      <form action="#" method="get" class="search__form">
                        <input id="basic-search" class="search__input" type="search" name="search" maxlength="100" placeholder="My Favorite Movie..." required />
                        <button class="search__btn-submit" type="submit">Add</button>
                      </form>
                    </section>
                  </div>
                </form>
            </div>
<div class="section">
  </br>
  
  </br>
  <div style="position: absolute; right:20%;">
    <h2 style="text-align:center !important; size:10px !important;">Favorite Movies</h2>
    @if ($favoriteMovies->count() > 0)
        <div class="cars">
            @foreach ($favoriteMovies as $movie)
            <div class="car">
                <div class="img-wrapper">
                    <a href="{{ route('movie.show', ['id' => $movie->id]) }}">See more</a>
                    <img src="{{ $movie->Poster }}" alt="{{ $movie->Title }}">
                </div>
                <h3>{{ $movie->Title }}</h3>
                <ul style="margin-right:170px !important;"role="list">
                    <li>Type: {{ $movie->Type }}</li>
                    <li>Genres: 
                        <?php
                        $uniqueGenres = [];
                        if ($movie->genre1 && !in_array($movie->genre1->genre, $uniqueGenres)) {
                            $uniqueGenres[] = $movie->genre1->genre;
                        }
                        if ($movie->genre2 && !in_array($movie->genre2->genre, $uniqueGenres)) {
                            $uniqueGenres[] = $movie->genre2->genre;
                        }
                        if ($movie->genre3 && !in_array($movie->genre3->genre, $uniqueGenres)) {
                            $uniqueGenres[] = $movie->genre3->genre;
                        }
                        ?>
                        {{ implode(', ', $uniqueGenres) }}
                    </li>
                </ul>
            </div>
            @endforeach
        </div>
    @else
        <p>No favorite movies found.</p>
    @endif
</div>
</div>

<!-- Recommended Movies Section -->
<div style="position: absolute; top: 300%;">
    <h2 style="position:absolute !important; right:135% !important;">Recommended Movies</h2>
    @if (!empty($recommendedMoviesDetails))
        <div class="cars" style="display: flex; flex-wrap: wrap;">
            @foreach ($recommendedMoviesDetails as $index => $recommendedMovie)
                <div class="car" style="width: 20%; box-sizing: border-box; padding: 5px;">
                    <div class="img-wrapper">
                        <a href="{{ route('movie.show', ['id' => $recommendedMovie['id']]) }}">See more</a>
                        <img src="{{ $recommendedMovie['Poster'] }}" alt="{{ $recommendedMovie['Title'] }}">
                    </div>
                    <h3>{{ $recommendedMovie['Title'] }}</h3>
                    <ul style="margin-right: 170px !important;" role="list">
                        <li>Type: {{ $recommendedMovie['Type'] }}</li>
                        <li>Genres:
                            <?php
                            $movieGenres = [];
                            if ($recommendedMovie['genre1']) {
                                $movieGenres[] = $recommendedMovie['genre1']->genre;
                            }
                            if ($recommendedMovie['genre2']) {
                                $movieGenres[] = $recommendedMovie['genre2']->genre;
                            }
                            if ($recommendedMovie['genre3']) {
                                $movieGenres[] = $recommendedMovie['genre3']->genre;
                            }
                            ?>
                            {{ implode(', ', $movieGenres) }}
                        </li>
                    </ul>
                </div>
                @if (($index + 1) % 3 == 0)
                    <div style="width: 100%;"></div>
                @endif
            @endforeach
        </div>
    @else
        <p>No recommended movies found.</p>
    @endif
</div>

            <div style= "position :absolute !important; bottom:74% !important; right:32.3% !important;"class="section">
                <a style="text-decoration:none !important; color:white !important; font-size:13px !important; " href="{{ route('logout') }}"> Logout</a>
            </div>
        @else
            <div class="section">
                <p>Please <a href="{{ route('login') }}">login</a> or <a href="{{ route('signup') }}">sign up</a> to access your
                    profile.</p>
            </div>
        @endif
    </div>
    <script src="{{ url('recommender.js') }}"></script>
    <script src="{{ url('logo.js') }}"></script>
</body>

</html>
