<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration
{

    public function up()
    {
        Schema::table('steam_game_stats', function (Blueprint $table) {
            $table->foreign('steam_game_id')->references('id')->on('steam_games')
                ->onDelete('cascade')
                ->onUpdate('no action');
        });
        Schema::table('steam_game_achievements', function (Blueprint $table) {
            $table->foreign('steam_game_id')->references('id')->on('steam_games')
                ->onDelete('cascade')
                ->onUpdate('no action');
        });
    }

    public function down()
    {
        Schema::table('steam_game_stats', function (Blueprint $table) {
            $table->dropForeign('steam_game_stats_steam_game_id_foreign');
        });
        Schema::table('steam_game_achievements', function (Blueprint $table) {
            $table->dropForeign('steam_game_achievements_steam_game_id_foreign');
        });
    }
}
