<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReflectionAndEnthusiasmColumnToEffortsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('efforts', function (Blueprint $table) {
            $table->longText('reflection')->nullable();
            $table->longText('enthusiasm')->nullable();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('efforts', function (Blueprint $table) {
            $table->dropColumn('enthusiasm');
            $table->dropColumn('reflection');
        });
    }
}
