<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dates', function (Blueprint $table) {
            $table->integer('date_id')->unique();
            $table->date('date');
            $table->integer('year');
            $table->integer('month');
            $table->string('fullmonth');
            $table->string('shortmonth');
            $table->integer('day');
            $table->string('fullday');
            $table->string('shortday');
            $table->integer('dayofweek'); //ISO-8601 1=monday, 7=Sunday
            $table->integer('dayofyear');
            $table->integer('week');
            $table->integer('nrdaysinmonth');
            $table->boolean('leapyear'); // 1=true, 0=false
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dates');
    }
}
