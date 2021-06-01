<?php

namespace App\Repositories\Goal;

use App\Models\Goal;
use Illuminate\Database\Eloquent\Collection;

class GoalRepository implements GoalRepositoryInterface
{
	/** 
		* goal_idによる目標の取得
		* @param Request $request
		* @param User $user
		* @param Goal $goal
		* @return  Illuminate\Support\Collection
	*/	
	public function getGoalsOnProgress($user): Collection
	{
		$goalsOnProgress = Goal::where('user_id', $user->id)
			->where(function($query){
				$query->where('status', 0);
			})->get();

		return $goalsOnProgress;
	}

	/** 
		* goal_idによる目標の取得
		* @param Request $request
		* @param Goal $goal
		* @return  Illuminate\Support\Collection
	*/
	public function getGoalById($id): Collection
	{
		$goal = Goal::where('id', $id)->get();

		return $goal;
	}

	/** 
		* 目標の保存
		* @param Request $request
		* @param Goal $goal
		* @return  void
	*/
	public function storeGoal($request, $goal)
	{
		$goal->fill($request->all());
		$goal->user_id = $request->user()->id;
		$goal->save();

	}

	/** 
		* 目標の更新
		* @param Request $request
		* @param Goal $goal
		* @return  void
	*/
	public function updateGoal($request, $goal)
	{
		$goal->fill($request->all());
		$goal->save();

	}	

	/** 
		* 目標の削除
		* @param Goal $goal
		* @return  void
	*/
	public function destroy($goal)
	{
		$goal->delete();
	}

	/** 
		* 目標のクリア(ステータスカラムを変更)
		* @param Goal $goal
		* @return  void
	*/
	public function clear($goal)
	{
		$goal->status = 1;
		$goal->save();
	}

}
