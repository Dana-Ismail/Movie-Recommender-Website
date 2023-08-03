<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel='stylesheet' href='https://static.fontawesome.com/css/fontawesome-app.css'>
    <link href="https://fonts.googleapis.com/css?family=Cardo:400i|Rubik:400,700&display=swap" rel="stylesheet">
    <link rel='stylesheet' href='https://pro.fontawesome.com/releases/v5.9.0/css/all.css'>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400&amp;display=swap'>
    <link rel="stylesheet" href="{{ url('c.css') }}">
    <link rel="stylesheet" href="{{ url('navbar.css') }}">
    <link rel="stylesheet" href="{{ url('popular.css') }}">
    <link rel="stylesheet" href="{{ url('search.css') }}">
    <link rel="stylesheet" href="{{ url('logo.css') }}">
</head>
<body>
<header>

    <a href="/" class="cont">
      <div class="triangle-wrapper">
        <div class="triangle triangle-1"></div>
        <div class="triangle triangle-2"></div>
        <div class="triangle triangle-3"></div>
        <div class="triangle triangle-4"></div>
        <div class="triangle triangle-5"></div>
      </div>
      <nav>
        <ul>
      <li class="current"><a class="home" href="/" data-hover="Home">Home</a></li>
    <li><a class="movies" href="/movies" data-hover="Movies">Movies</a></li>
    @guest
    <li><a class="profile" href="{{ route('login') }}" data-hover="Profile">Profile</a></li>
    @else
    <li><a class="profile" href="{{ route('profile') }}" data-hover="Profile">Profile</a></li>
    @endguest
        </ul>
      </nav>
    </a>
    
  
</header>


</br>
    @php
    $TrendingMovies = collect($moviesData);
    $TopTrendingMovies = $TrendingMovies->take(10);
    @endphp

    <div class="carousel">
    <div class="carousel__body">
        <div class="carousel__prev"><i class="far fa-angle-left"></i></div>
        <div class="carousel__next"><i class="far fa-angle-right"></i></div>
        <div class="carousel__slider">
            @foreach ($TopTrendingMovies as $movieData)
            <div class="carousel__slider__item">
                <div class="item__3d-frame">
                    <a href="{{ route('movie.show', ['id' => $movieData['id']]) }}">
                        <div class="item__3d-frame__box item__3d-frame__box--front">
                            <img src="{{ $movieData['Poster'] }}" alt="Movie Poster {{ $movieData['id'] }}">
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    </div>
    <!-- <div class="page-content">
    @foreach ($popularMoviesData as $index => $popularMovieData)
        @if ($index >= 10)
            @break
        @endif
        <div class="card">
            <img src="{{ $popularMovieData['Poster'] }}" alt="Movie Poster {{ $popularMovieData['id'] }}">
            <div class="content">
                <h2 class="title">{{ $popularMovieData['Title'] }}</h2>
                <p class="copy">Summary: {{ $popularMovieData['Summary'] }}</p>
                <a class="btn" href="{{ route('movie.show', ['id' => $popularMovieData['id']]) }}">View Details</a>
            </div>
        </div>
    @endforeach
</div> -->

</div>
<script src="{{ url('logo.js') }}"></script> 
    <script src="{{ url('c.js') }}"></script> 
    <script src="{{ url('search.js') }}"></script>
</body>

</html>
