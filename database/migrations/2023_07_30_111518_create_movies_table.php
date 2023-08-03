<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateMoviesTable extends Migration
{
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('Title')->nullable();
            $table->string('Type')->nullable();
            $table->string('Runtime')->nullable();
            $table->float('IMDb_Score')->nullable();
            $table->string('Netflix_Link')->nullable();
            $table->string('IMDb_Link')->nullable();
            $table->text('Summary')->nullable();
            $table->integer('IMDb_Votes')->nullable();
            $table->text('Image')->nullable();
            $table->text('Poster')->nullable();
            $table->text('TMDb_Trailer')->nullable();
            $table->unsignedBigInteger('Genre1')->nullable();
            $table->unsignedBigInteger('Genre2')->nullable();
            $table->unsignedBigInteger('Genre3')->nullable();
            $table->string('Tags_1')->nullable();
            $table->string('Tags_2')->nullable();
            $table->string('Tags_3')->nullable();
            $table->string('Tags_4')->nullable();
            $table->string('Tags_5')->nullable();
            $table->string('Tags_6')->nullable();
            $table->string('language1')->nullable();
            $table->string('language2')->nullable();
            $table->string('Director_1')->nullable();
            $table->string('Director_2')->nullable();
            $table->string('Actors_1')->nullable();
            $table->string('Actors_2')->nullable();
            $table->string('Actors_3')->nullable();
            $table->string('Actors_4')->nullable();
            $table->integer('Year')->nullable();
            $table->timestamps();
        });

        // Read data from movies.csv and insert into the movies table
        $data = file_get_contents(base_path('resources/notebooks/movies.csv'));
        $rows = explode("\n", $data);
        $header = str_getcsv(array_shift($rows));

        foreach ($rows as $row) {
            $rowData = str_getcsv($row);

            // Skip if the row is empty
            if (empty($rowData)) {
                continue;
            }

            // Skip if the number of values doesn't match the number of keys
            if (count($header) !== count($rowData)) {
                continue;
            }

            // Map the row data to the table columns
            $data = array_combine($header, $rowData);

            // Replace empty values with null
            $data = array_map(function ($value) {
                return $value !== '' ? $value : null;
            }, $data);

            // Handle "None" values for genre columns
            $genreColumns = ['Genre1', 'Genre2', 'Genre3'];
            foreach ($genreColumns as $column) {
                if ($data[$column] === 'None') {
                    $data[$column] = null;
                }
            }

            // Insert the data into the movies table
            DB::table('movies')->insert($data);
        }
    }

    public function down()
    {
        Schema::dropIfExists('movies');
    }
}
