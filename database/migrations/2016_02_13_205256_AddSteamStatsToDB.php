<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddSteamStatsToDB extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steam_stats', function (Blueprint $table) {
            $table->integer('id')->unsigned()->unique();
            $table->integer('steam_games_id')->unsigned();
            $table->string('name');
            $table->integer('value')->default(0);
            $table->string('displayName')->nullable();
            $table->primary(['steam_games_id', 'name']);
            $table->foreign('steam_games_id')->references('id')->on('steam_games')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('steam_stats', function (Blueprint $table) {
            $mysql = 'ALTER TABLE `steam_stats` CHANGE COLUMN `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT';
            DB::connection()->getPdo()->exec($mysql);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('steam_stats');
    }
}
