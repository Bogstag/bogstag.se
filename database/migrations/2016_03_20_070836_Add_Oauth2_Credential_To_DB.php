<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddOauth2CredentialToDB extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth2_credentials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('provider');
            $table->string('clientid');
            $table->string('clientsecret');
            $table->string('accesstoken');
            $table->string('refreshtoken');
            $table->string('redirecturi');
            $table->dateTime('expires');
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
        Schema::drop('oauth2_credentials');
    }
}
