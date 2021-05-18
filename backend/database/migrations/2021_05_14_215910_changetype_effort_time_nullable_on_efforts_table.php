<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangetypeEffortTimeNullableOnEffortsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('efforts', function (Blueprint $table) {
            $table->bigInteger('effort_time')->unsigned()->nullable()->change();
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
            $table->bigInteger('effort_time')->unsigned();
        });
    }
}
