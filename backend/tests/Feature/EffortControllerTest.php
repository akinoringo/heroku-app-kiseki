<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Goal;
use App\Models\Effort;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EffortControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 軌跡一覧表示機能のテスト
     * 未ログイン時
     * 
     * @return void
    */
    public function testGuestIndex(){
    	$responce = $this->get(route('home'));

    	$responce->assertStatus(200)
            ->assertViewIs('home')
            ->assertSee('Kisekiとは？');
    }

    /**
     * 軌跡一覧表示機能のテスト
     * ログイン時
     * 
     * @return void
    */
    public function testAuthIndex(){
        $user = factory(User::class)->create();
        $responce = $this->actingAs($user)
            ->get(route('home'));

        $responce->assertStatus(200)
            ->assertViewIs('home')
            ->assertSee('目標作成')
            ->assertSee('軌跡作成');
    }    

    /**
     * 軌跡作成画面　表示機能のテスト
     * 未ログイン時
     * 
     * @return void
    */       
    public function testGuestCreate() {
    	$responce = $this->get(route('efforts.create'));

    	$responce->assertRedirect(route('login'));
    }

    /**
    * 軌跡作成画面　表示機能のテスト
    * ログイン時
    * 目標未作成の場合
    * 
    * @return void
    */ 
    public function testAuthCreateNoGoal() {
    	$user = factory(User::class)->create();

    	$responce = $this->actingAs($user)
    		->get(route('efforts.create'));

    	$responce->assertRedirect(route('mypage.show', ['id' => $user->id]));
    }

    /**
    * 軌跡作成画面　表示機能のテスト
    * ログイン時
    * 目標作成済みの場合
    * 
    * @return void
    */ 
    public function testAuthCreateYesGoal() {
        $user = factory(User::class)->create();

        $goal = Goal::create([
            'user_id' => $user->id,
            'title' => "タイトル",
            'content' => "内容",
            'deadline' => date('Y-m-d', strtotime('2022-09-09')),
        ]);

        $goals = Goal::where('user_id', $user->id)->get();

        $responce = $this->actingAs($user)
            ->get(route('efforts.create', ['goals' => $goals]));

        $responce->assertStatus(200)
            ->assertViewIs('efforts.create');
    }   


    /**
    * 軌跡保存機能のテスト
    * 未ログイン時
    * 
    * @return void
    */     
    public function testGuestStore() {

        // 未ログイン状態で軌跡のストアメソッドを実行    
        $responce = $this->post(route('efforts.store'));

        // ログインページにリダイレクトされることを確認
        $responce->assertRedirect(route('login'));
    } 

    /**
    * 軌跡保存機能のテスト
    * ログイン時
    * 
    * @return void
    */
    public function testAuthStore() {

        ## 準備 ## 
        // ユーザーの作成
        $user = factory(User::class)->create();

        // 目標の作成
        $goal = factory(Goal::class)->create();  

        // 軌跡の登録内容
        $title_effort = "軌跡";
        $content_effort = "軌跡詳細";
        $effort_time = 1;

        ## 実行 ##
        // ログインユーザーとして軌跡のストアメソッドを実行
        $responce = $this->actingAs($user)
            ->post(route('efforts.store', [
                'goal_id' => $goal->id,
                'user_id' => $user->id,
                'title' => $title_effort,
                'content' => $content_effort,
                'effort_time' => $effort_time,
            ]));

        ## 検証 ##
        // データベースに登録した軌跡があるかを確認
        $this->assertDatabaseHas('efforts', [
                'goal_id' => $goal->id,            
                'user_id' => $user->id,
                'title' => $title_effort,
                'content' => $content_effort,
                'effort_time' => $effort_time,
        ]);    

        // マイページにリダイレクトされることを確認
        $responce->assertRedirect(route('mypage.show', ['id' => $user->id]));
    }

    /**
    * 軌跡詳細画面　表示画面のテスト
    * 
    * @return void
    */    
    public function testShow(){

        ## 準備 ##
        // ユーザーの作成
        $user = factory(User::class)->create();

        // 目標の作成
        $goal = factory(Goal::class)->create();      

        // 軌跡の作成
        $title_effort = "軌跡";
        $content_effort = "軌跡詳細";
        $effort_time = 1;           

        $effort = Effort::create([
            'goal_id' => $goal->id,
            'user_id' => $user->id,
            'title' => $title_effort,
            'content' => $content_effort,
            'effort_time' => $effort_time,            
        ]);

        ## 実行 ##
        // 軌跡の詳細画面へアクセス実行
        $responce = $this->get(route('efforts.show', ['effort' => $effort]));

        ## 検証 ##
        // 軌跡の詳細画面のレスポンスとviewを確認  
        $responce->assertStatus(200)
            ->assertViewIs("efforts.show")
            ->assertSee('軌跡詳細');
    }

    /**
    * 軌跡編集画面　表示画面のテスト
    * 未ログイン時
    *
    * @return void
    */    
    public function testGuestEdit(){

        ## 準備 ##
        // ユーザーの作成
        $user = factory(User::class)->create();

        // 目標の作成
        $goal = factory(Goal::class)->create();

        // 軌跡の作成
        $title_effort = "タイトル";
        $content_effort = "内容";
        $effort_time = 1;          
        
        $effort = Effort::create([
            'goal_id' => $goal->id,
            'user_id' => $user->id,
            'title' => $title_effort,
            'content' => $content_effort,
            'effort_time' => $effort_time,            
        ]);        

        ## 実行 ##
        // 軌跡のeditメソッドを実行
        $responce = $this->get(route('efforts.edit', ['effort' => $effort]));

        ## 検証 ##
        // ログイン画面にリダイレクトされることを確認
        $responce->assertRedirect(route('login'));
    }    

    /**
    * 軌跡編集画面　表示画面のテスト
    * ログイン時
    *
    * @return void
    */ 
    public function testAuthEdit(){

        ## 準備 ##
        // ユーザーの作成
        $user = factory(User::class)->create();

        // 目標の作成
        $goal = factory(Goal::class)->create();

        // 軌跡の作成
        $title_effort = "タイトル";
        $content_effort = "内容";
        $effort_time = 1;          
        
        $effort = Effort::create([
            'goal_id' => $goal->id,
            'user_id' => $user->id,
            'title' => $title_effort,
            'content' => $content_effort,
            'effort_time' => $effort_time,            
        ]);        

        ## 実行 ##
        // ログインユーザーとして編集画面にアクセスを実行
        $responce = $this->actingAs($user)
            ->get(route('efforts.edit', ['effort' => $effort]));

        ## 検証 ##
        // レスポンスとviewが返ることを確認
        $responce->assertStatus(200)
            ->assertViewIs("efforts.edit")
            ->assertSee('更新する');
    }   


    /**
    * 軌跡削除機能のテスト
    * ログイン時
    *
    * @return void
    */    
    public function testDestroy() {

        ## 準備 ##
        // ユーザーの作成
        $user = factory(User::class)->create();

        // 目標の作成
        $goal = factory(Goal::class)->create();

        // 軌跡の作成
        $title_effort = "タイトル";
        $content_effort = "内容";
        $effort_time = 1;    
        $effort_status = 0;      
        
        $effort = Effort::create([
            'goal_id' => $goal->id,
            'user_id' => $user->id,
            'title' => $title_effort,
            'content' => $content_effort,
            'effort_time' => $effort_time,   
            'status' =>  0,        
        ]);        

        ## 実行 ##
        // 削除処理を実行
        $responce = $this->actingAs($user)
            ->delete(route('efforts.destroy', ['effort' => $effort]));

        ## 検証 ##
        // 登録した軌跡がデータベースから削除されたかどうかを確認
        $this->assertDeleted('efforts', [
            'id' => $effort->id,
            'goal_id' => $goal->id,
            'user_id' => $user->id,
            'title' => $title_effort,
            'content' => $content_effort,
            'effort_time' => $effort_time,  
            'status' => $effort_status,   
        ]);     

        // マイページにリダイレクトされるかを確認
        $responce->assertRedirect(route('mypage.show', ['id' => $user->id]));           

    } 


}
