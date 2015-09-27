<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailDropsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emaildrops', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sender');
            $table->string('subject')->nullable();
            $table->string('Spf')->nullable();
            $table->decimal('Spamscore')->nullable();
            $table->string('Spamflag')->nullable();
            $table->string('DkimCheck')->nullable();
            $table->boolean('public');
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
        Schema::drop('emaildrops');
    }
}
