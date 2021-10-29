<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EffortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    	DB::table('efforts')->insert([
    		[
    			'id' => 1,
	    		'user_id' => 1,
	    		'goal_id' => 1,
	    		'title' => 'AWSのEC2にアプリをデプロイした。',
	    		'content' => 'アプリケーションにPHP-FPM, サーバーにNGINXを用いた。データベースはRDS(MySQL)を用いた。',
	    		'effort_time' => 2,
          // 'created_at' => $faker->dateTimeBetween($startDate = '-3 week', $endDate = '-1 week'),
    		],
    		[
    			'id' => 2,
	    		'user_id' => 1,
	    		'goal_id' => 1,
	    		'title' => 'CircleCIで自動テスト自動デプロイを実装した。',
	    		'content' => 'CircleCIが便利すぎて、コードデプロイが必要な理由が分からない。',
	    		'effort_time' => 3,   
          // 'created_at' => $faker->dateTimeBetween($startDate = '-3 week', $endDate = '-1 week'),           			
    		],
    		[
    			'id' => 3,
	    		'user_id' => 1,
	    		'goal_id' => 1,
	    		'title' => 'CharjsとVue-chartjsを導入した。',
	    		'content' => 'CharjsとVue-chartjsを用いて積み上げ時間や回数を可視化できるようにした。',
	    		'effort_time' => 1,    			
          // 'created_at' => $faker->dateTimeBetween($startDate = '-3 week', $endDate = '-1 week'),          
    		],
        [
          'id' => 4,
          'user_id' => 1,
          'goal_id' => 2,
          'title' => '筋トレした。',
          'content' => '朝と夜に30分ずつ筋トレした。',
          'effort_time' => 1,         
          // 'created_at' => $faker->dateTimeBetween($startDate = '-3 week', $endDate = '-1 week'),          
        ],
        [
          'id' => 5,
          'user_id' => 3,
          'goal_id' => 4,
          'title' => '筋トレ',
          'content' => '腕立て/スクワット/腹筋/背筋',
          'effort_time' => 3,         
          // 'created_at' => $faker->dateTimeBetween($startDate = '-3 week', $endDate = '-1 week'),          
        ],
        [
          'id' => 6,
          'user_id' => 4,
          'goal_id' => 5,
          'title' => 'メガネの少年から情報を入手',
          'content' => 'どうやらやつらは黒い服をまとっているらしい。',
          'effort_time' => 2,         
          // 'created_at' => $faker->dateTimeBetween($startDate = '-3 week', $endDate = '-1 week'),          
        ],         
        [
          'id' => 7,
          'user_id' => 3,
          'goal_id' => 4,
          'title' => 'ランニング',
          'content' => '10kmランニング',
          'effort_time' => 2,         
          // 'created_at' => $faker->dateTimeBetween($startDate = '-3 week', $endDate = '-1 week'),          
        ], 
        [
          'id' => 8,
          'user_id' => 5,
          'goal_id' => 6,
          'title' => '名人とカルタ',
          'content' => '本気で2時間ぶっ通した。',
          'effort_time' => 2,         
          // 'created_at' => $faker->dateTimeBetween($startDate = '-3 week', $endDate = '-1 week'),          
        ],                                
  		]); 
  	
    }
}
