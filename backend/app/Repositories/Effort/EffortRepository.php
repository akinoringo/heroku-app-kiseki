<?php

namespace App\Repositories\Effort;

use App\Models\Effort;
use Illuminate\Support\Facades\Auth;

class EffortRepository implements EffortRepositoryInterface
{
	public function storeEffort($effort, $request)
	{
		$effort->fill($request->all());
		$effort->user_id = $request->user()->id;
		$effort->save();
	}

	public function updateEffort($effort, $request)
	{
		$effort->fill($request->all());
		$effort->save();		
	}

	public function destroyEffort($effort)
	{
		$effort->status = 1;
		$effort->save();		
	}	

	public function getEffortsWithSearch($search)
	{
		$efforts = Effort::where('status', 0)
			->orderBy('created_at', 'desc')
			->where(function($query) use ($search){
				$query->orwhere('title', 'like', "%{$search}%")
					->orwhere('content', 'like', "%{$search}%");
			});

		return $efforts;
	}

	public function getEffortsOfGoal($goal)
	{
		$effortsOfGoal = Effort::where('goal_id', $goal->id)
			->where(function($query) {
					$query->where('status', 0);
				});

		return $effortsOfGoal;		

	}

	public function getEffortsOfFollowee()
	{
		if (Auth::check()) {

			$effortsOfFollowee = Effort::orderBy('created_at', 'DESC')
				->whereIn('user_id', Auth::user()->followings()->pluck('followee_id'));		

		} else {

			$effortsOfFollowee = null;
		}

		return $effortsOfFollowee;
	}

	public function getEffortsOfADay($goal, $day)
	{
		$effortsOfADay = Effort::where('goal_id', $goal->id)
			->where(function($goals) use ($day){
				$goals->whereDate('created_at', $day);
			});

		return $effortsOfADay;
	}
}