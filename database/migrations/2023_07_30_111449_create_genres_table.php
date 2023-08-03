<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->string('genre');
            $table->unsignedBigInteger('genreID');
            $table->timestamps();
        });

        // read data from genres.csv and store in genres table
        $data = file_get_contents(base_path('resources/notebooks/genres.csv'));
        $rows = explode("\n", $data);
        $header = str_getcsv(array_shift($rows));

        foreach ($rows as $row) {
            $rowData = str_getcsv($row);
            if (count($rowData) >= 2) {
                $genre = trim($rowData[0]);
                $genreID = (int) trim($rowData[1]);

                DB::table('genres')->insert([
                    'genre' => $genre,
                    'genreID' => $genreID,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('genres');
    }
}
