<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->boolean('fanarttvpostermissing')->default('0');
            $table->boolean('fanarttvclearartmissing')->default('0');
            $table->boolean('fanarttvmissing')->default('0');
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
            $table->dropColumn('fanarttvpostermissing');
            $table->dropColumn('fanarttvclearartmissing');
            $table->dropColumn('fanarttvmissing');
        });
    }
}
