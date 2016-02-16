<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSteamGameDescriptionToDB extends Migration
{

    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('steam_game_descriptions', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->primary('id');
            $table->string('name')->nullable();
            $table->boolean('is_free')->default(false);
            $table->text('about')->nullable();
            $table->string('header_image')->nullable();
            $table->string('legal_notice')->nullable();
            $table->integer('meta_critic_score')->default(0);
            $table->string('meta_critic_url')->nullable();
            $table->string('website')->nullable();
            $table->string('screenshot_thumbnail')->nullable();
            $table->string('screenshot_full')->nullable();
            $table->string('movie_thumbnail')->nullable();
            $table->string('movie_full')->nullable();
            $table->string('movie_name')->nullable();
            $table->string('background')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::drop('steam_game_descriptions');
    }
}
