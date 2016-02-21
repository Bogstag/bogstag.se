<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSteamGamesTable extends Migration
{
    public function up()
    {
        Schema::create('steam_games', function (Blueprint $table) {
            $table->integer('id')->primary()->unsigned();
            $table->string('name')->nullable();
            $table->integer('playtime_forever')->default('0');
            $table->integer('playtime_2weeks')->default('0');
            $table->boolean('has_community_visible_stats')->default(0);
            $table->string('image_icon_url')->nullable();
            $table->string('image_logo_url')->nullable();
            $table->string('Image_background')->nullable();
            $table->string('image_header')->nullable();
            $table->boolean('is_free')->default(0);
            $table->string('about_the_game')->nullable();
            $table->string('legal_notice')->nullable();
            $table->string('website')->nullable();
            $table->integer('meta_critic_score')->default('0');
            $table->string('meta_critic_url')->nullable();
            $table->string('screenshot_path_thumbnail')->nullable();
            $table->string('screenshot_path_full')->nullable();
            $table->string('movie_thumbnail')->nullable();
            $table->string('movie_full_url')->nullable();
            $table->string('movie_name')->nullable();
            $table->timestamp('schema_updated_at')->nullable();
            $table->timestamp('player_stats_updated_at')->nullable();
            $table->timestamp('game_updated_at')->nullable();
            $table->timestamp('description_updated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('steam_games');
    }
}
