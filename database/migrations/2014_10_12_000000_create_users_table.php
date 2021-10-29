<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('image')->nullable();
            $table->string('introduction')->nullable();
            $table->tinyInteger('efforts_time_badge')->default(0)->comment('0=未取得, 1=取得済み');
            $table->tinyInteger('stacking_days_badge')->default(0)->comment('0=未取得, 1=取得済み');
            $table->tinyInteger('continuation_days_badge')->default(0)->comment('0=未取得, 1=取得済み');
            $table->tinyInteger('goal_clear_badge')->default(0)->comment('0=未取得, 1=取得済み');             
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
