<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMovieToDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_trakt')->unique();
            $table->integer('id_tmdb')->unique();
            $table->string('id_imdb')->unique();
            $table->string('slug')->unique();
            $table->string('title');
            $table->integer('plays')->default(1);
            $table->dateTime('last_watched_at');
            $table->integer('year')->nullable();
            $table->dateTime('ticket_datetime')->nullable();
            $table->integer('ticket_price')->nullable();
            $table->integer('ticket_row')->nullable();
            $table->integer('ticket_seat')->nullable();
            $table->dateTime('tmdb_updated_at')->nullable();
            $table->timestamps();
            $table->string('tagline')->nullable();
            $table->text('overview')->nullable();
            $table->date('released')->nullable();
            $table->integer('runtime')->nullable();
            $table->string('trailer')->nullable();
            $table->string('homepage')->nullable();
            $table->dateTime('trakt_updated_at')->nullable();
            $table->string('certification')->nullable();
            $table->text('genres')->nullable();
            $table->boolean('fanarttvpostermissing')->default('0');
            $table->boolean('fanarttvclearartmissing')->default('0');
            $table->boolean('fanarttvmissing')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('movies');
    }
}
