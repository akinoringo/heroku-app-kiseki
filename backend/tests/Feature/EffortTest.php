<?php

namespace Tests\Feature;

use App\Models\Effort;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EffortTest extends TestCase
{
    use RefreshDatabase;


    /**
     * いいね機能のテスト
     * いいねされていない場合
     * 
     * @return void
    */
    public function testIsLikedByNull(){

        // 軌跡の作成
        $effort = factory(Effort::class)->create();

        // いいねされていない場合にいいねをされているかを判定する処理を実行
        $result = $effort->isLikedBy(null);

        // いいねされているかの判定結果がfalseとなることを確認
        $this->assertFalse($result);
    }

    /**
     * いいね機能のテスト
     * いいねされている場合(trueケース)
     * 
     * @return void
    */
    public function testIsLikedByTheUser(){

        // 軌跡の作成
        $effort = factory(Effort::class)->create();

        // ユーザーの作成
        $user = factory(User::class)->create();

        // 作成したユーザーが作成された記事にいいねする処理を実行
        $effort->likes()->attach($user);

        // いいねをされているかを判定する処理を実行
        $result = $effort->isLikedBy($user);

        // 判定結果がtrueとなることを確認
        $this->assertTrue($result);
    }

    /**
     * いいね機能のテスト
     * いいねされている場合(falseケース)
     * 
     * @return void
    */
    public function testIsLikedByAnother(){

        // 軌跡の作成
        $effort = factory(Effort::class)->create();

        // ユーザーを作成(一人目)
        $user = factory(User::class)->create();

        // ユーザーを作成(二人目)
        $another = factory(User::class)->create();

        // 二人目のユーザーが作成された記事にいいねする処理を実行
        $effort->likes()->attach($another);

        // 一人目のユーザーにいいねされているかを判定する処理を実行
        $result = $effort->isLikedBy($user);

        // 判定結果がfalseとなることを確認
        $this->assertFalse($result);
    }
}
