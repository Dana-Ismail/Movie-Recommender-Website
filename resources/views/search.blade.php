@if ($movies->isEmpty())
    <p>No movies found for "{{ $keyword }}".</p>
@else
    <p>Showing search results for "{{ $keyword }}":</p>
    <ul>
        @foreach ($movies as $movie)
            <li>
                <a href="{{ route('movie.show', $movie->id) }}">{{ $movie->Title }}</a>
            </li>
        @endforeach
    </ul>
@endif