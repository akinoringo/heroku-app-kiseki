<?php

namespace App\Repositories\Goal;

use App\Models\Goal;

class GoalGetRepository implements GoalRepositoryInterface
{
	public function getGoalsOnProgress($user)
	{
		$goalsOnProgress = Goal::where('user_id', $user->id)
			->where(function($query){
				$query->where('status', 0);
			})->get();

		return $goalsOnProgress;
	}
}
