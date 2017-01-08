<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSteamGameStatsTable extends Migration
{
    public function up()
    {
        Schema::create('steam_game_stats', function (Blueprint $table) {
            $table->integer('id', true)->unique();
            $table->integer('steam_game_id')->unsigned();
            $table->string('name')->nullable();
            $table->string('display_name')->nullable();
            $table->integer('value')->default('0');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('steam_game_stats');
    }
}
