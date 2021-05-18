<?php

namespace Tests\Feature;

use App\User;
use App\Goal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GoalControllerTest extends TestCase
{
    use RefreshDatabase;

    # 目標作成画面　表示機能のテスト
    // 未ログイン時
    public function testGuestCreate() {
        $responce = $this->get(route('goals.create'));

        $responce->assertRedirect(route('login'));
    }

    // ログイン時
    public function testAuthCreate() {
        $user = factory(User::class)->create();

        $responce = $this->actingAs($user)
            ->get(route('goals.create'));

        $responce->assertStatus(200)
            ->assertViewIs('goals.create');
    }


    # 目標保存機能のテスト
    // 未ログイン時
    public function testGuestStore() {
        $responce = $this->post(route('goals.store'));

        $responce->assertRedirect(route('login'));
    } 

    // ログイン時
    public function testAuthStore() {

        $user = factory(User::class)->create();

        $user_id = $user->id;
        $title = "タイトル";
        $content = "内容";
        $deadline = date('Y-m-d', strtotime('2022-09-09'));

        $responce = $this->actingAs($user)
            ->post(route('goals.store', [
                'user_id' => $user_id,
                'title' => $title,
                'content' => $content,
                'deadline' => $deadline,
            ]));

        $this->assertDatabaseHas('goals', [
                'user_id' => $user_id,
                'title' => $title,
                'content' => $content,
                'deadline' => $deadline,
        ]);

        $responce->assertRedirect(route('mypage.show', ['id' => $user_id]));
    }        


    # 目標詳細画面　表示画面のテスト
    public function testShow(){

        $goal = factory(Goal::class)->create();

        $responce = $this->get(route('goals.show', ['goal' => $goal]));

        $responce->assertStatus(200)
            ->assertViewIs("goals.show");
    }


    # 目標編集画面　表示画面のテスト
    // 未ログイン時
    public function testGuestEdit(){

        $goal = factory(Goal::class)->create();

        $responce = $this->get(route('goals.edit', ['goal' => $goal]));

        $responce->assertRedirect(route('login'));
    }  

    // ログイン時
    public function testAuthEdit(){

        $goal = factory(Goal::class)->create();
        $user = $goal->user;

        $responce = $this->actingAs($user)
            ->get(route('goals.edit', ['goal' => $goal]));

        $responce->assertStatus(200)
            ->assertViewIs("goals.edit");
    }        



    # 目標削除機能のテスト
    public function testDestroy(){

        $user = factory(User::class)->create();

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

        $responce = $this->actingAs($user)
            ->delete(route('goals.destroy', ['goal' => $goal]));

        $this->assertDeleted('goals', [
            'user_id' => $user_id,
            'title' => $title,
            'content' => $content,
            'deadline' => $deadline,           
        ]);

        $responce->assertRedirect(route('mypage.show', ['id' => $user_id]));

    }



}
