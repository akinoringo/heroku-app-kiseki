<?php

namespace App\Repositories\Effort;

use App\Models\Effort;
use App\Models\Goal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EffortRepository implements EffortRepositoryInterface
{

	/** 
		* 軌跡を保存
		* @param Effort $effort
		* @param Request $request				
		* @return  void
	*/
	public function storeEffort($effort, $request)
	{
		$effort->fill($request->all());
		$effort->user_id = $request->user()->id;
		$effort->save();
	}

	/** 
		* 軌跡を更新
		* @param Effort $effort
		* @param Request $request				
		* @return  void
	*/
	public function updateEffort($effort, $request)
	{
		$effort->fill($request->all());
		$effort->save();		
	}

	/** 
		* 軌跡を削除
		* @param Effort $effort
		* @return  void
	*/
	public function destroyEffort($effort)
	{
		$effort->status = 1;
		$effort->save();		
	}	

	/** 
		* 目標に紐づく未削除の軌跡をすべて取得
		* @param Goal $goal
		* @param Effort $effort
		* @return Collection
	*/
	public function getEffortsOfGoal($goal): Builder
	{
		$effortsOfGoal = Effort::where('goal_id', $goal->id)
			->where(function($query) {
					$query->where('status', 0);
				});

		return $effortsOfGoal;		

	}

	/** 
		* 目標に紐づく未削除の軌跡をすべて取得
		* @param Goal $goal
		* @param Effort $effort
		* @return Builder
	*/
	public function getAllEffortsOfAUser($user): Builder
	{
		$allEffortsOfAUSer = Effort::where('user_id', $user->id);

		return $allEffortsOfAUSer;		

	}

	/** 
		* 未削除の軌跡をすべて取得
		* @param Effort $effort
		* @return  Builder
	*/
	public function getAllEffortsExist(): Builder
	{
		$allEffortsExist = Effort::where('status', 0)
			->orderBy('created_at', 'desc');

		return $allEffortsExist;
	}	

	/** 
		* 目標に紐づく軌跡のうち、与えられた日にちのものを取得
		* @param Goal $goal
		* @param Date $day
		* @param Effort $effort
		* @return  Builder
	*/
	public function getEffortsOfADay($goal, $day): Builder
	{
		$effortsOfADay = Effort::where('goal_id', $goal->id)
			->where(function($query) use ($day){
				$query->whereDate('created_at', $day);
			});

		return $effortsOfADay;
	}

	/** 
		* 目標に紐づく軌跡のうち、与えられた日にちの週のものを取得
		* @param Goal $goal
		* @param Date $day		
		* @param Effort $effort
		* @return  Builder
	*/
	public function getEffortsOfAWeek($goal, $day): Builder
	{
		$effortsOfAWeek = Effort::where('goal_id', $goal->id)
			->where(function($query) use ($day){
				$query
					->whereDate('created_at', '>=', $day->startOfWeek()->format('Y-m-d'))
					->whereDate('created_at', '<=', $day->endOfWeek()->format('Y-m-d'));
			});		

		return $effortsOfAWeek;
	}		

	/** 
		* 目標に紐づく軌跡のうち、与えられた日にちの月のものを取得
		* @param Goal $goal
		* @param Date $day		
		* @param Effort $effort
		* @return  Builder
	*/
	public function getEffortsOfAMonth($goal, $day): Builder
	{
		$effortsOfAMonth = Effort::where('goal_id', $goal->id)
			->where(function($query) use ($day){
				$query
					->whereDate('created_at', '>=', $day->firstOfMonth()->format('Y-m-d'))
					->whereDate('created_at', '<=', $day->lastOfMonth()->format('Y-m-d'));
			});		

		return $effortsOfAMonth;
	}	
	
}