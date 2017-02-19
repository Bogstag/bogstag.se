<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    public function up()
    {
        Schema::create('Images', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('orginalimage');
            $table->string('imagetype');
            $table->string('imagepath');
            $table->integer('imageable_id')->unsigned()->index();
            $table->string('imageable_type');
        });
    }

    public function down()
    {
        Schema::drop('Images');
    }
}
