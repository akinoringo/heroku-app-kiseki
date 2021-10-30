<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('title');
            $table->longText('content');
            $table->string('image')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=アクティブ, 1=クリア済み, 2=削除済み');
            $table->bigInteger('goal_time')->unsigned();
            $table->bigInteger('efforts_time')->unsigned()->default(0);
            $table->bigInteger('stacking_days')->unsigned()->default(0);
            $table->bigInteger('continuation_days')->unsigned()->default(0);
            $table->bigInteger('continuation_days_max')->unsigned()->default(0);
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
        Schema::dropIfExists('goals');
    }
}
