function handleSearchMovie() {
        let input = document.getElementById('myInput'),
            filter = input.value.toUpperCase(),
            movieList = document.getElementById('moviesContainer'),
            listElement = movieList.getElementsByClassName('card-wrapper'),
            a, i, txtValue;

        // Loop through all list items, and hide those that don't match the search query
        for (i = 0; i < listElement.length; i++) {
            a = listElement[i].getElementsByClassName('card-title')[0];
            txtValue = a.textContent || a.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                listElement[i].style.display = '';
            } else {
                listElement[i].style.display = 'none';
            }
        }
    }

    $(document).ready(function () {
        // Function to fetch filtered movies and update the dropdown
        function filterMovies(genre, year) {
            $.ajax({
                url: "{{ route('filter.movies') }}",
                type: "GET",
                data: { genre: genre, year: year },
                dataType: "json",
                success: function (data) {
                    // Update the dropdowns with the filtered data
                    // Assuming you have a function to update the dropdown options
                    updateDropdownOptions('#genreDropdown', data.movies);
                    updateDropdownOptions('#yearDropdown', data.movies);
                },
                error: function (xhr, status, error) {
                    console.log(error);
                }
            });
        }

        // Event handler when selecting a genre
        $('#genreDropdown li a').on('click', function (e) {
            e.preventDefault();
            var genre = $(this).text();
            filterMovies(genre, null);
        });

        // Event handler when selecting a year
        $('#yearDropdown li a').on('click', function (e) {
            e.preventDefault();
            var year = $(this).text();
            filterMovies(null, year);
        });
    });
