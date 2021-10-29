<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Goal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GoalControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 目標作成画面　表示機能のテスト
     * 未ログイン時
     * 
     * @return void
    */    
    public function testGuestCreate() {

        // 目標作成画面にアクセス
        $responce = $this->get(route('goals.create'));

        // ログイン画面にリダイレクトを確認
        $responce->assertRedirect(route('login'));
    }

    /**
     * 目標作成画面　表示機能のテスト
     * ログイン時
     * 
     * @return void
    */ 
    public function testAuthCreate() {

        // ユーザーを作成
        $user = factory(User::class)->create();

        // ログインユーザーとして目標作成画面にアクセス
        $responce = $this->actingAs($user)
            ->get(route('goals.create'));

        // レスポンスが正常に返ることを確認
        // Viewが正常に返ることを確認
        $responce->assertStatus(200)
            ->assertViewIs('goals.create')
            ->assertSee('作成する');
    }


    /**
     * 目標保存機能のテスト
     * 未ログイン時
     * 
     * @return void
    */     
    public function testGuestStore() {

        // 未ログイン状態で目標保存処理を実行
        $responce = $this->post(route('goals.store'));

        // ログインページにリダイレクトされることを確認
        $responce->assertRedirect(route('login'));
    } 

    /**
     * 目標保存機能のテスト
     * ログイン時
     * 
     * @return void
    */  
    public function testAuthStore() {
        // ユーザーの作成
        $user = factory(User::class)->create();

        // 目標の作成
        $user_id = $user->id;
        $title = "タイトル";
        $content = "内容";
        $deadline = date('Y-m-d', strtotime('2022-09-09'));

        // ログインユーザーとして目標を保存
        $responce = $this->actingAs($user)
            ->post(route('goals.store', [
                'user_id' => $user_id,
                'title' => $title,
                'content' => $content,
                'deadline' => $deadline,
            ]));

        // 作成した目標がデータベースにあるかどうかを確認
        $this->assertDatabaseHas('goals', [
                'user_id' => $user_id,
                'title' => $title,
                'content' => $content,
                'deadline' => $deadline,
        ]);

        // マイページにリダイレクトされるかを確認
        $responce->assertRedirect(route('mypage.show', ['id' => $user_id]));
    }        


    /**
     * 目標詳細画面　表示画面のテスト
     * 
     * @return void
    */      
    public function testShow(){

        // 目標の作成
        $goal = factory(Goal::class)->create();

        // 目標詳細画面にアクセス
        $responce = $this->get(route('goals.show', ['goal' => $goal]));

        // レスポンスが返ることを確認
        // Viewが正常に返ることを確認
        $responce->assertStatus(200)
            ->assertViewIs("goals.show")
            ->assertSee($goal->title);
    }


    /**
     * 目標編集画面　表示画面のテスト
     * 未ログイン時
     * 
     * @return void
    */     
    public function testGuestEdit(){

        // 目標の作成
        $goal = factory(Goal::class)->create();

        // 目標の編集画面にアクセス
        $responce = $this->get(route('goals.edit', ['goal' => $goal]));

        // ログイン画面にリダイレクトされることを確認
        $responce->assertRedirect(route('login'));
    }  

    /**
     * 目標編集画面　表示画面のテスト
     * ログイン時
     * 
     * @return void
    */     
    public function testAuthEdit(){

        // 目標の作成
        $goal = factory(Goal::class)->create();
        $user = $goal->user;

        // 目標の作成者としてログイン状態で目標編集画面にアクセス
        $responce = $this->actingAs($user)
            ->get(route('goals.edit', ['goal' => $goal]));

        // レスポンスやViewが正常に返ることを確認
        $responce->assertStatus(200)
            ->assertViewIs("goals.edit")
            ->assertSee("更新する");
    }        

    /**
     * 目標削除機能のテスト
     * ログイン時
     * 
     * @return void
    */     
    public function testDestroy(){

        // ユーザーの作成
        $user = factory(User::class)->create();

        // 目標の作成
        $user_id = $user->id;
        $title = "タイトル";
        $content = "内容";
        $deadline = date('Y-m-d', strtotime('2022-09-09'));

        $goal = Goal::create([
            'user_id' => $user_id,
            'title' => $title,
            'content' => $content,
            'deadline' => $deadline
        ]);

        // 目標作成者がログイン状態で削除を実行
        $responce = $this->actingAs($user)
            ->delete(route('goals.destroy', ['goal' => $goal]));

        // 目標が削除されていることをデータベースで確認
        $this->assertDeleted('goals', [
            'user_id' => $user_id,
            'title' => $title,
            'content' => $content,
            'deadline' => $deadline,           
        ]);

        // マイページにリダイレクトされることを確認
        $responce->assertRedirect(route('mypage.show', ['id' => $user_id]));

    }



}
