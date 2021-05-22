<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    	DB::table('goals')->insert([
    		[
    			'id' => 1,
	    		'user_id' => 1,
	    		'title' => 'エンジニアになる',
	    		'content' => '7月までにエンジニアになる。そのために毎日10時間以上はプログラミングを勉強する。',
	    		'deadline' => '2021-07-01',
    		],
    		[
    			'id' => 2,
	    		'user_id' => 1,
          'title' => 'フルマラソン完走',
          'content' => '9月24日のフルマラソンの大会で完走する。そのために毎日筋トレとランニングをする。',
	    		'deadline' => '2021-09-24',   			
    		],
    		[
    			'id' => 3,
	    		'user_id' => 1,
	    		'title' => 'カメラを極める',
	    		'content' => '今年中に上手な写真を撮れるようになる。そのために週に一回は一眼レフをもって外に写真を取りに行く。',
	    		'deadline' => '2021-12-31',   			
    		],
  		]);
    }
}
