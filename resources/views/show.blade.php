<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="{{ url('movie.css') }}">
  <link rel="stylesheet" href="{{ url('showmovie.css') }}">
  <link rel="stylesheet" href="{{ url('logo.css') }}">
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
              <!-- navigation -->
</br>
  <nav class="navbar navbar-expand-lg navbar-light bg-light" style="background-color:none !important;">
    <a href="/" class="triangle-container navbar-brand">
      <div class="triangle-wrapper">
        <div class="triangle triangle-1"></div>
        <div class="triangle triangle-2"></div>
        <div class="triangle triangle-3"></div>
        <div class="triangle triangle-4"></div>
        <div class="triangle triangle-5"></div>
      </div>
</a>
  </nav>
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
              <a class="a2a_dd"><i class="bi bi-heart"></i><form method="POST" action="{{ route('add.favorite') }}">
              @csrf
              <input type="hidden" name="movie_id" value="{{ $movie->id }}">
              <button style="font-size:20px !important"type="submit" class="btn btn-info btn-add-favorite omg}}"
                data-id="{{ $movie->id }}">
                <i class="fas fa-heart">  &nbsp;  &nbsp; &nbsp;Add to Favorite</i>
              </button>
            </form></a>
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

  <form style="border-top: solid 1px yellow;" action="{{ route('reviews.store', ['movie' => $movie->id]) }}" method="POST">
</br>  
  @csrf
    <p style="font-size:17px; margin-left: 10px;">Add your Review</p>
</br>
    <div>
        <textarea name="comment" rows="4" cols="50" placeholder="Write your review here" style="margin-left: 10px; width: 80%; height:120px; background: transparent; border: 1px solid #ccc; color:white;"></textarea>
    </div>
    <div>
    <button type="submit" class="btn btn-primary btn-show-movie button" style="margin-left: 10px; margin-top:10px;">Submit Review</button>
    </div>
</form>
</br> </br>
@if ($movie->reviews && $movie->reviews->count() > 0)
    <h3>Reviews</h3>
    <ul>
        @foreach ($movie->reviews as $review)
            <li>{{ $review->user->name }}: {{ $review->comment }}</li>
        @endforeach
    </ul>
@else
    <p>No reviews yet.</p>
@endif

</div>
  <script src="{{ url('logo.js') }}"></script>
<script>
  function openLink(link) {
    window.open(link, '_blank');
  }
</script>
</body>
</html>
