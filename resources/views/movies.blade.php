<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Movie List</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4"
    crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="{{ url('movie.css') }}">
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
        <li class="nav-item">
          <a class="nav-link" href="/">HOME</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link la" href="/movies">Movies
            <span class="sr-only">(current)</span>
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
        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#search"></use>
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

  <!-- data-panel -->
  <div class="container mt-5">

    <!-- viewbox -->
    <div class="viewbox">
      <div class="view"><i class="fa fa-th hover-color-change" id="btn-cardModel" aria-hidden="true"></i></div>
      <div class="view"><i class="fa fa-bars hover-color-change" id="btn-listModel" aria-hidden="true"></i></div>
    </div>

    <div class="clearfix"></div>

<!-- movie list -->
<div class="row" id="data-panel">
  @foreach ($movies as $movie)
  <div class="col-sm-3">
    <div class="card mb-2 size">
      <a href="{{ route('movie.show', ['id' => $movie->id]) }}">
        <img class="card-img-top" src="<?php echo $movie->Poster; ?>" alt="<?php echo $movie->Title; ?>">
      </a>
      <div class="card-body movie-item-body">
        <h6 class="card-title">
          <a href="{{ route('movie.show', ['id' => $movie->id]) }}">{{ $movie->Title }}</a>
        </h6>
      </div>
      <div class="card-footer" style="display: flex; align-items: center;">
      <a href="{{ route('movie.show', ['id' => $movie->id]) }}">
          @csrf
          <input type="hidden" name="movie_id" value="{{ $movie->id }}">
          <button type="submit" class="btn btn-primary btn-show-movie button">More</button>
        </a>
        <form method="POST" action="{{ route('add.favorite') }}">
              @csrf
              <input type="hidden" name="movie_id" value="{{ $movie->id }}">
              <button type="submit" class="btn btn-info btn-add-favorite }}"
                data-id="{{ $movie->id }}">
                <i class="fas fa-heart"></i>
              </button>
            </form>
</button>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      </div>
    </div>
  </div>
  @endforeach
</div>

  <!-- Pagination -->
  <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center" id="pagination">
      <li class="page-item">
        <a class="page-link" href="#">1</a>
      </li>
      <li class="page-item">
        <a class="page-link" href="#">2</a>
      </li>
      <li class="page-item">
        <a class="page-link" href="#">3</a>
      </li>
    </ul>
  </nav>

  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"
    crossorigin="anonymous"></script>
  <script src="{{ url('search.js') }}"></script>
  <script src="{{ url('logo.js') }}"></script>
  <script>
    $(document).ready(function() {
      $('.btn-add-favorite').on('click', function() {
        $(this).toggleClass('liked');
      });
    });
  </script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
  $(document).ready(function() {
    $('.btn-add-favorite').click(function() {
      var movieId = $(this).data('id');
      var isFavorite = $(this).hasClass('liked');
      // Save the button reference in a variable
      var button = $(this);

      // send an AJAX request to add/remove the movie from favorites
      $.ajax({
        url: isFavorite ? '/remove-favorite' : '/add-favorite',
        method: 'POST',
        data: {
          movie_id: movieId,
        },
        success: function(response) {
          if (response.success) {
            // update the button appearance using the saved variable
            button.toggleClass('liked', !isFavorite);
          }
        }
      });
    });
  });
</script>


</body>

</html>
