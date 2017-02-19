<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMovieTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn('fanart');
            $table->dropColumn('poster');
            $table->dropColumn('logo');
            $table->dropColumn('clearart');
            $table->dropColumn('banner');
            $table->dropColumn('thumb');
            $table->boolean('fanarttvpostermissing');
            $table->boolean('fanarttvclearartmissing');
            $table->boolean('fanarttvmissing');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->string('fanart')->nullable();
            $table->string('poster')->nullable();
            $table->string('logo')->nullable();
            $table->string('clearart')->nullable();
            $table->string('banner')->nullable();
            $table->string('thumb')->nullable();
            $table->dropColumn('fanarttvpostermissing');
            $table->dropColumn('fanarttvclearartmissing');
            $table->dropColumn('fanarttvmissing');
        });
    }
}
