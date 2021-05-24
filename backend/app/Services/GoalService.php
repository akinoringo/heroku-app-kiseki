<?php

namespace App\Services;

use App\Models\Effort;
use App\Models\Goal;
use App\Services\TimeService;
use Illuminate\Support\Facades\Auth;


class GoalService{

	protected $time_service;
  
	public function __construct(TimeService $time_service)
	{
		// Serviceクラスからインスタンスを作成
		$this->TimeService = $time_service;
	}

	/** 
		* 自身の未達成の目標を取得する
		* @param Goal $goal
		* @return  Builder
	*/
	public function myGoalsGet() {
		$goals = Goal::where('user_id', Auth::user()->id)
							->where(function($goals){
								$goals->where('status', 0);
							})->get();

		return $goals;		
	}	

	/** 
		* 軌跡の合計時間を計算し、目標ステータスを更新する
		* @param Goal $goal
		* @param Effort $effort		
		* @return  void
	*/
	public function updateGoalStatus($goal, $efforts){

		if ($goal->goal_time <= $this->TimeService->sumEffortsTime($efforts)) {

			$goal->status = 1;
			$goal->save();

			session()->flash('flash_message', 'おめでとうございます。目標をクリアしました。');
			session()->flash('color', 'success');

		} else {

			$goal->status = 0;
			$goal->save();

			session()->flash('flash_message', '軌跡を作成しました。');
			session()->flash('color', 'success');			

		}		

	}	

	/**
		* 未達成の目標数をカウントする
		* @param Goal $goal
		* @return  int $number
	*/
	public function countGoalsOnProgress($user) {
		$number = Goal::where('user_id', $user->id)
			->where(function($goals) {
				$goals->where('status', 0);
		})->count();

		return $number;			
	}

}