<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddSteamAchievementsToDB extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steam_achievements', function (Blueprint $table) {
            $table->integer('id')->unsigned()->unique();
            $table->integer('steam_games_id')->unsigned();
            $table->string('name');
            $table->integer('value')->default(0);
            $table->string('displayName')->nullable();
            $table->integer('hidden')->default(0);
            $table->string('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('icongray')->nullable();
            $table->primary(['steam_games_id', 'name']);
            $table->foreign('steam_games_id')->references('id')->on('steam_games')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('steam_achievements', function (Blueprint $table) {
            $mysql = 'ALTER TABLE `steam_achievements` CHANGE COLUMN `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT';
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
        Schema::drop('steam_achievements');
    }
}
