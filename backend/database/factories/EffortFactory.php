<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Effort;
use App\Models\User;
use App\Models\Goal;
use Faker\Generator as Faker;

$factory->define(Effort::class, function (Faker $faker) {
    return [
        //
  		'title' => $faker->realText(20),
  		'content' => $faker->realText(100),
      'reflection' => $faker->realText(100),
      'enthusiasm' => $faker->realText(100),
  		'goal_id' => function () {
  			return factory(Goal::class);
  		},  		
  		'user_id' => function () {
  			return factory(User::class);
  		},
  		'effort_time' => $faker->numberBetween($min= 1, $max=10),
  		'status' => 0,
      'created_at' => $faker->dateTimeBetween($startDate = '-3 week', $endDate = '-1 week'),
      'updated_at' => $faker->dateTimeBetween($startDate = '-1 week', $endDate = 'now'),      
    ];
});
