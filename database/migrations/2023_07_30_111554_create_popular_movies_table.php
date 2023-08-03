<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePopularMoviesTable extends Migration
{
    protected $tableName = 'popular_movies';

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('movie_id');
            $table->timestamps();

            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
        });

        // Read data from popular_movies.csv and insert into the popular_movies table
        $data = file_get_contents(resource_path('notebooks/popular_movies.csv'));
        $rows = explode("\n", $data);

        foreach ($rows as $row) {
            $movieId = trim($row);

            // Check if the movie_id exists in the movies table
            $exists = DB::table('movies')->where('id', $movieId)->exists();

            if ($exists) {
                DB::table($this->tableName)->insert([
                    'movie_id' => $movieId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
