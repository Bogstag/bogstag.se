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
        Schema::create('steamgames', function (Blueprint $table) {
            $table->integer('id')->unique();
            $table->string('name')->nullable();
            $table->integer('playtimeforever')->default(0);
            $table->integer('playtime2weeks')->default(0);
            $table->string('iconurl')->nullable();
            $table->string('logourl')->nullable();
            $table->boolean('hasstats')->default(0);
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
        Schema::drop('steamgames');
    }
}
