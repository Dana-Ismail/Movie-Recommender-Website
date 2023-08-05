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
    <link rel="stylesheet" href="{{ url('slider.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4"
    crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="{{ url('navbar.css') }}">
    <link rel="stylesheet" href="{{ url('logo.css') }}">
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
      <li class="nav-item active">
        <a class="nav-link la" href="/">HOME</a>
        
        <span class="sr-only">(current)</span>
      </li>
      <li class="nav-item ">
        <a class="nav-link " href="/movies">Movies
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/profile">PROFILE</a>
      </li>
    </ul>
     <!-- Search Form -->
     <form class="search-form" action="{{ route('search') }}" method="GET">
  <input type="search" value="" placeholder="Search" class="search-input" name='keyword'>
  <button type="submit" class="search-button">
    <svg class="submit-button">
     
    </svg>
  </button>
</form>

<svg xmlns="http://www.w3.org/2000/svg" width="0" height="0" display="none">
  <symbol id="search" viewBox="0 0 32 32">
    <path d="M 19.5 3 C 14.26514 3 10 7.2651394 10 12.5 C 10 14.749977 10.810825 16.807458 12.125 18.4375 L 3.28125 27.28125 L 4.71875 28.71875 L 13.5625 19.875 C 15.192542 21.189175 17.250023 22 19.5 22 C 24.73486 22 29 17.73486 29 12.5 C 29 7.2651394 24.73486 3 19.5 3 z M 19.5 5 C 23.65398 5 27 8.3460198 27 12.5 C 27 16.65398 23.65398 20 19.5 20 C 15.34602 20 12 16.65398 12 12.5 C 12 8.3460198 15.34602 5 19.5 5 z" />
  </symbol>
</svg>

  </div>
</nav>

</br>
</br>
@php
$TrendingMovies = collect($moviesData);
$TrendingMovies = $TrendingMovies->shuffle();
$TopTrendingMovies = $TrendingMovies->take(10);
@endphp

<input type="radio" id="s-1" name="slider-control" checked="checked">
<input type="radio" id="s-2" name="slider-control">
<input type="radio" id="s-3" name="slider-control">
<input type="radio" id="s-4" name="slider-control">
<input type="radio" id="s-5" name="slider-control">
<input type="radio" id="s-6" name="slider-control">
<input type="radio" id="s-7" name="slider-control">
<input type="radio" id="s-8" name="slider-control">
<input type="radio" id="s-9" name="slider-control">
<input type="radio" id="s-10" name="slider-control">

<div class="js-slider">
    @php $counter = 1; @endphp
    @foreach ($TopTrendingMovies as $movieData)
    <figure class="js-slider_item slider-item-{{ $counter }}">
        <a style="color: white !important; outline:none !important;" href="{{ route('movie.show', ['id' => $movieData['id']]) }}"> 
            <div class="js-slider_img">
                <img class="c-img-w-full" src="{{ $movieData['Poster'] }}" alt="Movie Poster {{ $movieData['id'] }}">
            </div>
            <figcaption class="wo-caption">
                <h3 class="wo-h3">{{ $movieData['Title'] }}</h3>
                </a> 
                <ul class="wo-credit">
                    <li>{{ $movieData['Type'] }}</li>
                    <li>{{ $movieData['Year'] }} </li>
                    <li>{{ $movieData['Tags_1'] }} {{ $movieData['Tags_2'] }}</li>
                </ul>
            </figcaption>
       
    </figure>
    @php $counter++; @endphp
    @endforeach

    <div class="js-slider_nav">
        @php $nav_counter = 1; @endphp
        @for ($i = 1; $i <= 10; $i++)
        <label class="js-slider_nav_item s-nav-{{ $nav_counter }} prev" for="s-{{ $nav_counter }}"></label>
        <label class="js-slider_nav_item s-nav-{{ $nav_counter }} next" for="s-{{ $nav_counter + 1 }}"></label>
        @php $nav_counter++; @endphp
        @endfor

        <!-- Add navigation for left arrow -->
        <label class="js-slider_nav_item s-nav-11 prev" for="s-10"></label>
    </div>

    <div class="js-slider_indicator">
        @php $indi_counter = 1; @endphp
        @for ($i = 1; $i <= 10; $i++)
        <div class="js-slider-indi indi-{{ $indi_counter }}"></div>
        @php $indi_counter++; @endphp
        @endfor
    </div>
</div>

</br>
</div>
<script src="{{ url('logo.js') }}"></script> 
    <script src="{{ url('c.js') }}"></script> 
    <script src="{{ url('search.js') }}"></script>
</body>

</html>
