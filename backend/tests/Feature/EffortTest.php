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

    public function testIsLikedByNull(){
        $effort = factory(Effort::class)->create();
        $result = $effort->isLikedBy(null);

        $this->assertFalse($result);
    }

    public function testIsLikedByTheUser(){
        $effort = factory(Effort::class)->create();
        $user = factory(User::class)->create();
        $effort->likes()->attach($user);

        $result = $effort->isLikedBy($user);

        $this->assertTrue($result);
    }

    public function testIsLikedByAnother(){
        $effort = factory(Effort::class)->create();
        $user = factory(User::class)->create();
        $another = factory(User::class)->create();
        $effort->likes()->attach($another);

        $result = $effort->isLikedBy($user);

        $this->assertFalse($result);
    }
}
