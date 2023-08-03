<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="{{ url('movie.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="detail">
<div class="background-image" style="background-image: url('{{ $movie->Poster }}');"></div>

<div id="site-container">
  <main id="main">
    <header id="main-header">
      <div class="inner-container">
        <div class="sb-toggle-left">
          <a href="/" class="logo"></a>
        </div>
      </div>
    </header>
    <section id="content">             
      <div class="inner-container">
        <div class="movie-image">
          <img src="{{ $movie->Image }}" alt="{{ $movie->Title }}">
          <div class="blue" id="buttonposter">
            <p>
              <i class="fa fa-play-circle" aria-hidden="true"></i>
              @if ($movie->IMDb_Link)
                <a href="{{ $movie->IMDb_Link }}" class="watch-btn" target="_blank">
                  Play
                </a>
              @else
                <a href="#" class="watch-btn" onclick="openLink('{{ $movie->IMDb_Trailer }}')">
                  Play
                </a>
              @endif
            </p>
          </div>
        </div>
        <div class="movie-info">
          <span class="rating">
            <b>{{ $movie->IMDb_Score }}</b>
          </span>
          <h1 class="entry-title">{{ $movie->Title }}</h1>
          <em class="tagline">{{ $movie->Type . ', ' . $movie->Tags_1 . ', ' . $movie->Tags_2 }}</em>
          <div class="movie-data">
            <div class="details">
              <div class="movie-data">
                <a href="/years/{{ $movie->Year }}/" rel="tag">{{ $movie->Year }}</a>
                @if (!empty($genres))
                  | <a href="/genre/{{ strtolower($genres[0]) }}/"> {{ $genres[0] }}</a>
                @endif
                @if (count($genres) > 1)
                  | <a href="/genre/{{ strtolower($genres[1]) }}/"> {{ $genres[1] }}</a>
                @endif
                @if (count($genres) > 2)
                  | <a href="/genre/{{ strtolower($genres[2]) }}/"> {{ $genres[2] }}</a>
                @endif
                | <span>{{ $movie->Runtime }}</span>
              </div>
            </div>
          </div>
          <p class="movie-description">
            <span class="trama">{{ $movie->Summary }}</span>
            <div>
              @if ($movie->Actors_1)
                <span>{{ $movie->Actors_1 }}</span>
              @endif
              @if ($movie->Actors_2)
                <span>{{ $movie->Actors_2 }}</span>
              @endif
              @if ($movie->Actors_3)
                <span>{{ $movie->Actors_3 }}</span>
              @endif
              @if ($movie->Actors_4)
                <span>{{ $movie->Actors_4 }}</span>
              @endif
              @if ($movie->Actors_5)
                <span>{{ $movie->Actors_5 }}</span>
              @endif
            </div>
          </p>
        </div>
        <div class="movie-actions">
          <ul class="extra">
            <li id="share">
              <a class="a2a_dd"><i class="bi bi-heart"></i><span>Favorite</span></a>
            </li>
            <li id="trailer">
              @if ($movie->TMDb_Trailer)
                <a href="{{ $movie->TMDb_Trailer }}" class="watch-btn" target="_blank">
                  <i class="material-icons">&#xE037;</i>WATCH TRAILER
                </a>
              @endif
            </li>
          </ul>
        </div>
        
      </div>
      <div id="slideshow">
      </div>
    </section>
  </main>
</div>
<script>
  function openLink(link) {
    window.open(link, '_blank');
  }
</script>
</body>
</html>
