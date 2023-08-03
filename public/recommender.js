function displayRecommendedMovies(recommendedMovies) {
  const recommendedMoviesList = document.getElementById('recommended-movies-list');

  if (recommendedMovies.length > 0) {
    recommendedMoviesList.innerHTML = recommendedMovies
      .map(movie => `<li>${movie.Title}</li>`)
      .join('');
  } else {
    recommendedMoviesList.innerHTML = '<li>No recommended movies found.</li>';
  }
}


// fetch movie recommendations using AJAX
function getRecommendations(userId) {
  fetch(`/get_recommendations/${userId}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
  })
    .then(response => response.json())
    .then(data => {
      displayRecommendedMovies(data);
    })
    .catch(error => {
      console.error('Error fetching movie recommendations:', error);
    });
}