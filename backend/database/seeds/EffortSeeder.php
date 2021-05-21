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
    		],
    		[
    			'id' => 2,
	    		'user_id' => 1,
	    		'goal_id' => 1,
	    		'title' => 'CircleCIで自動テスト自動デプロイを実装した。',
	    		'content' => 'CircleCIが便利すぎて、コードデプロイが必要な理由が分からない。',
	    		'effort_time' => 3,    			
    		],
    		[
    			'id' => 3,
	    		'user_id' => 1,
	    		'goal_id' => 1,
	    		'title' => 'CharjsとVue-chartjsを導入した。',
	    		'content' => 'CharjsとVue-chartjsを用いて積み上げ時間や回数を可視化できるようにした。',
	    		'effort_time' => 1,    			
    		],
        [
          'id' => 4,
          'user_id' => 1,
          'goal_id' => 2,
          'title' => '筋トレした。',
          'content' => '朝と夜に30分ずつ筋トレした。',
          'effort_time' => 1,         
        ],        
  		]); 

      factory(App\Models\Effort::class, 10)->create();   	
    }
}
