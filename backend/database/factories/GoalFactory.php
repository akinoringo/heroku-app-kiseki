<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Goal;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Goal::class, function (Faker $faker) {
    return [
        //
  		'title' => $faker->realText(20),
  		'content' => $faker->realText(100),
  		'goal_time' => 100,
  		'deadline' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+2 week'),
  		'user_id' => function () {
  			return factory(User::class);
      },
      'created_at' => $faker->dateTimeBetween($startDate = '-5 week', $endDate = '-3 week'),
      'updated_at' => $faker->dateTimeBetween($startDate = '-3 week', $endDate = '-1 week'),   	
    ];
});
