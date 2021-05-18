<?php

namespace Tests\Feature;

use App\User;
use App\Goal;
use App\Effort;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EffortControllerTest extends TestCase
{
    use RefreshDatabase;

    # 軌跡一覧表示機能のテスト
    public function testIndex(){
    	$responce = $this->get(route('home'));

    	$responce->assertStatus(200)
        ->assertViewIs('home');
    }

    # 軌跡作成画面　表示機能のテスト
    // 未ログイン時
    public function testGuestCreate() {
    	$responce = $this->get(route('efforts.create'));

    	$responce->assertRedirect(route('login'));
    }

    // ログイン時（目標未作成）
    public function testAuthCreateNoGoal() {
    	$user = factory(User::class)->create();

    	$responce = $this->actingAs($user)
    		->get(route('efforts.create'));

    	$responce->assertRedirect(route('mypage.show', ['id' => $user->id]));
    }

    // ログイン時（目標作成済み）
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

    # 軌跡保存機能のテスト
    // 未ログイン時
    public function testGuestStore() {
        $responce = $this->post(route('efforts.store'));

        $responce->assertRedirect(route('login'));
    } 

    // ログイン時
    public function testAuthStore() {
        $user = factory(User::class)->create();

        $goal = Goal::create([
            'user_id' => $user->id,
            'title' => "タイトル",
            'content' => "内容",
            'deadline' => date('Y-m-d', strtotime('2022-09-09')),
        ]);    

        $title_effort = "タイトル";
        $content_effort = "内容";
        $effort_time = 1;


        $responce = $this->actingAs($user)
            ->post(route('efforts.store', [
                'goal_id' => $goal->id,
                'user_id' => $user->id,
                'title' => $title_effort,
                'content' => $content_effort,
                'effort_time' => $effort_time,
            ]));

        $this->assertDatabaseHas('efforts', [
                'goal_id' => $goal->id,            
                'user_id' => $user->id,
                'title' => $title_effort,
                'content' => $content_effort,
                'effort_time' => $effort_time,
        ]);    

        $responce->assertRedirect(route('mypage.show', ['id' => $user->id]));
    }

    # 軌跡詳細画面　表示画面のテスト
    public function testShow(){
        $user = factory(User::class)->create();

        $goal = Goal::create([
            'user_id' => $user->id,
            'title' => "タイトル",
            'content' => "内容",
            'deadline' => date('Y-m-d', strtotime('2022-09-09')),
        ]);      

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

        $responce = $this->get(route('efforts.show', ['effort' => $effort]));

        $responce->assertStatus(200)
            ->assertViewIs("efforts.show");
    }

    # 軌跡編集画面　表示画面のテスト
    // 未ログイン時
    public function testGuestEdit(){

        $user = factory(User::class)->create();

        $goal = factory(Goal::class)->create();

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

        $responce = $this->get(route('efforts.edit', ['effort' => $effort]));

        $responce->assertRedirect(route('login'));
    }    

    // ログイン時
    public function testAuthEdit(){

        $user = factory(User::class)->create();

        $goal = factory(Goal::class)->create();

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

        $responce = $this->actingAs($user)
            ->get(route('efforts.edit', ['effort' => $effort]));

        $responce->assertStatus(200)
            ->assertViewIs("efforts.edit");
    }   


    # 軌跡削除機能のテスト
    public function testDestroy() {
        $user = factory(User::class)->create();

        $goal = factory(Goal::class)->create();

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

        $responce = $this->actingAs($user)
            ->delete(route('efforts.destroy', ['effort' => $effort]));

        $this->assertDeleted('efforts', [
            'id' => $effort->id,
            'goal_id' => $goal->id,
            'user_id' => $user->id,
            'title' => $title_effort,
            'content' => $content_effort,
            'effort_time' => $effort_time,  
            'status' => 0,        
        ]);     

        $responce->assertRedirect(route('mypage.show', ['id' => $user->id]));           

    } 


}
