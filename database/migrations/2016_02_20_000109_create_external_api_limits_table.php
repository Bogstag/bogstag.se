<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateExternalApiLimitsTable extends Migration
{
    public function up()
    {
        Schema::create('external_api_limits', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('external_api_name')->index();
            $table->integer('external_api_limit')->default('0');
            $table->string('external_api_limit_interval');
            $table->integer('external_api_count')->default('0');
            $table->timestamp('limit_interval_start');
            $table->timestamp('limit_interval_end');
        });
    }

    public function down()
    {
        Schema::drop('external_api_limits');
    }
}
