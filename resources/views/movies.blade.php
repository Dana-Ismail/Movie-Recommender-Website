<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Library</title>
    <link rel='stylesheet' href='https://static.fontawesome.com/css/fontawesome-app.css'>
    <link href="https://fonts.googleapis.com/css?family=Cardo:400i|Rubik:400,700&display=swap" rel="stylesheet">
    <link rel='stylesheet' href='https://pro.fontawesome.com/releases/v5.9.0/css/all.css'>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400&amp;display=swap'>
    <link rel="stylesheet" href="{{ url('c.css') }}">
    <link rel="stylesheet" href="{{ url('navbar.css') }}">
    <link rel="stylesheet" href="{{ url('popular.css') }}">
    <link rel="stylesheet" href="{{ url('movie.css') }}">
    <link rel="stylesheet" href="{{ url('search.css') }}">
</head>
<body>
<header>
    <ul class="snip1143">
        <li><a class="home" href="#" data-hover="Home">Home</a></li>
        <li class="current"><a class="movies" href="/movies" data-hover="Movies">Movies</a></li>
        @guest
        <li><a class="profile" href="{{ route('login') }}" data-hover="Profile">Profile</a></li>
        @else
        <li><a class="profile" href="{{ route('profile') }}" data-hover="Profile">Profile</a></li>
        @endguest
    </ul>
</header>

<body class="overview">

<div id="header-secondary">
    <div class="inner-container">
        <nav class="filters">
            <ul>
                <li class="dropdown genre-filter">
                    <div class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Genre<i class="fa fa-angle-down"></i></div>
                    <ul class="dropdown-menu dropdown-menu-large" id="genreDropdown">
                        <?php foreach ($genres as $genre) { ?>
                        <li><a href="#"><?php echo $genre->genre; ?></a></li>
                        <?php } ?>
                    </ul>
                </li>
                <li class="dropdown quality-filter">
                    <div class="dropdown-toggle" data-toggle="dropdown">Year<i class="fa fa-angle-down"></i></div>
                    <ul class="dropdown-menu" id="yearDropdown">
                        <?php foreach ($years as $year) { ?>
                            @if ($year)
                                <li><a href="#">{{ $year }}</a></li>
                            @endif
                        <?php } ?>
                    </ul>
                </li>

                <li class="search">
                    <form method="get" role="form" id="searchform" autocomplete="off" action="">
                        <i class="fa fa-search"></i>
                        <input id="search" name="s" type="search" class="search-input" value="" placeholder="Search...">
                    </form>
                    <div class="live-search"></div>
                </li>
            </ul>
        </nav>
    </div>
</div>

<section id="content" class="inner-container">
    <div class="item-container">
        <?php foreach ($movies as $movie) { ?>
        <div class="item">
        <a href="{{ route('movie.show', ['id' => $movie['id']]) }}">
                <div class="item-flip">
                    <div class="item-inner">
                        <img src="<?php echo $movie->Poster; ?>" alt="<?php echo $movie->Title; ?>">
                    </div>
                    <div class="item-details">
                        <div class="item-details-inner">
                            <h2 class="movie-title"><?php echo $movie->Title; ?></h2>
                            <p class="movie-description"><?php echo $movie->Summary; ?></p>
                            <div class="watch-btn">
                                <div class="imdb-rating" data-content="<?php echo $movie->IMDb_Score; ?>">
                                    <i class="fa fa-star"></i><?php echo $movie->IMDb_Score; ?></div>
                                <span class="movie-date"><?php echo $movie->Year; ?></span>
                                <span>Play</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <?php } ?>
    </div>

    <div class="pagination">
        @for ($page = 1; $page <= $movies->lastPage(); $page++)
            @if ($page == $movies->currentPage())
                <span class="current">{{ $page }}</span>
            @else
                <a href="{{ $movies->url($page) }}" class="inactive">{{ $page }}</a>
            @endif
        @endfor
    </div>
    <div class='resppages'>
        @if ($movies->currentPage() < $movies->lastPage())
            <a href="{{ $movies->url($movies->currentPage() + 1) }}"><span class="fa fa-caret-right"></span></a>
        @endif
    </div>

</section>
<script src="{{ url('search.js') }}"></script>
</body>
</html>
