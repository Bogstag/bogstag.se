<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeEmailDropsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emaildrops', function (Blueprint $table) {
            $table->string('Spamflag_new')->nullable();
            $table->string('DkimCheck_new')->nullable();
        });

        Schema::table('emaildrops', function (Blueprint $table) {
            $table->dropColumn('DkimCheck');
            $table->dropColumn('Spamflag');
        });

        Schema::table('emaildrops', function (Blueprint $table) {
            $table->renameColumn('DkimCheck_new', 'DkimCheck');
            $table->renameColumn('Spamflag_new', 'Spamflag');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
