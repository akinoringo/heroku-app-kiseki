<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Goal;
use App\User;
use Faker\Generator as Faker;

$factory->define(Goal::class, function (Faker $faker) {
    return [
        //
  		'title' => $faker->text(20),
  		'content' => $faker->text(100),
  		'goal_time' => 100,
  		'deadline' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+2 week'),
  		'user_id' => function () {
  			return factory(User::class);
  		},   	
    ];
});
