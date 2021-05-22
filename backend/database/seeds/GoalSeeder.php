<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

class GoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        //
    	DB::table('goals')->insert([
    		[
    			'id' => 1,
	    		'user_id' => 1,
	    		'title' => 'エンジニアになる',
	    		'content' => '7月までにエンジニアになる。そのために毎日10時間以上はプログラミングを勉強する。',
	    		'deadline' => '2021-07-01',
          'created_at' => $faker->dateTimeBetween($startDate = '-5 week', $endDate = '-3 week'),          
    		],
    		[
    			'id' => 2,
	    		'user_id' => 1,
          'title' => 'フルマラソン完走',
          'content' => '9月24日のフルマラソンの大会で完走する。そのために毎日筋トレとランニングをする。',
	    		'deadline' => '2021-09-24',   
          'created_at' => $faker->dateTimeBetween($startDate = '-5 week', $endDate = '-3 week'),          			
    		],
    		[
    			'id' => 3,
	    		'user_id' => 1,
	    		'title' => 'カメラを極める',
	    		'content' => '今年中に上手な写真を撮れるようになる。そのために週に一回は一眼レフをもって外に写真を取りに行く。',
	    		'deadline' => '2021-12-31',
          'created_at' => $faker->dateTimeBetween($startDate = '-5 week', $endDate = '-3 week'),             			
    		],
        [
          'id' => 4,
          'user_id' => 3,
          'title' => 'ジークンドーを極める',
          'content' => '兄を超えるジークンドー使いになるために毎日3時間は武道に励む',
          'deadline' => '2021-12-31',
          'created_at' => $faker->dateTimeBetween($startDate = '-5 week', $endDate = '-3 week'),                  
        ],
        [
          'id' => 5,
          'user_id' => 4,
          'title' => '姉を奪還する',
          'content' => '悪い男から姉を救い出す。そのために毎日情報収集を欠かさない',
          'deadline' => '2021-12-31',
          'created_at' => $faker->dateTimeBetween($startDate = '-5 week', $endDate = '-3 week'),                  
        ],  
        [
          'id' => 6,
          'user_id' => 5,
          'title' => 'カルタでクイーンになる。',
          'content' => '毎日カルタを5時間は練習する。葉っぱちゃんには絶対に負けん。',
          'deadline' => '2021-12-31',
          'created_at' => $faker->dateTimeBetween($startDate = '-5 week', $endDate = '-3 week'),                  
        ],           
        [
          'id' => 7,
          'user_id' => 6,
          'title' => '息子を驚かせる小説を書く',
          'content' => '世界の名だたる小説を読み漁る',
          'deadline' => '2021-12-31',
          'created_at' => $faker->dateTimeBetween($startDate = '-5 week', $endDate = '-3 week'),                  
        ],                      
  		]);
    }
}
