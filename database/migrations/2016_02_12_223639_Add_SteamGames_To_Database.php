<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSteamGamesToDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steam_games', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->primary('id');
            $table->string('name')->nullable();
            $table->integer('playtimeforever')->default(0);
            $table->integer('playtime2weeks')->default(0);
            $table->string('iconurl')->nullable();
            $table->string('logourl')->nullable();
            $table->boolean('hasstats')->default(0);
            $table ->timestamp('schema_updated_at')->nullable();
            $table ->timestamp('player_stats_updated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('steam_games');
    }
}
