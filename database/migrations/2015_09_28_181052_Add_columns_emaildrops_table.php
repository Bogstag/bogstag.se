<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsEmaildropsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emaildrops', function (Blueprint $table) {
            $table->string('recipient')->nullable();
            $table->text('bodyplain')->nullable();
            $table->text('messageheaders')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emaildrops', function (Blueprint $table) {
            $table->dropColumn('recipient');
            $table->dropColumn('bodyplain');
            $table->dropColumn('messageheaders');
        });
    }
}
