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

	public function storeGoal($request, $goal)
	{
		$goal->fill($request->all());
		$goal->user_id = $request->user()->id;
		$goal->save();

	}

	public function updateGoal($request, $goal)
	{
		$goal->fill($request->all());
		$goal->save();

	}	

	public function destroy($goal)
	{
		$goal->delete();
	}

	public function clear($goal)
	{
		$goal->status = 1;
		$goal->save();
	}

}
