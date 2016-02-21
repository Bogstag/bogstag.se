<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSteamGameAchievementsTable extends Migration
{

    public function up()
    {
        Schema::create('steam_game_achievements', function (Blueprint $table) {
            $table->integer('id', true)->unique();
            $table->integer('steam_game_id')->unsigned();
            $table->string('name')->nullable();
            $table->integer('value')->default('0');
            $table->string('display_name')->nullable();
            $table->boolean('hidden')->default(0);
            $table->string('description')->nullable();
            $table->string('icon_url')->nullable();
            $table->string('icon_gray_url')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('steam_game_achievements');
    }
}
