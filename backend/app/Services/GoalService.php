<?php

namespace App\Services;

use App\Models\Effort;
use App\Models\Goal;
use App\Repositories\Goal\GoalRepositoryInterface as GoalRepository;
use App\Services\TimeService;
use Illuminate\Support\Facades\Auth;


class GoalService{

	protected $time_service;
	protected $goal_repository;
  
	public function __construct(TimeService $time_service, GoalRepository $goal_repository)
	{
		// Serviceクラスからインスタンスを作成
		$this->TimeService = $time_service;

		// RepositoryのInterfaceのインスタンス化
		$this->GoalRepository = $goal_repository;
	}

	/** 
		* 自身の未達成の目標を取得する
		* @param Goal $goal
		* @return  Builder
	*/
	public function getGoalsOnProgress($user) {
		// 未達成の目標を取得
		$goalsOnProgress = $this->GoalRepository->getGoalsOnProgress($user);

		return $goalsOnProgress;		
	}	


	/**
		* 未達成の目標数をカウントする
		* @param Goal $goal
		* @return  int $number
	*/
	public function countGoalsOnProgress($user) {

		// 未達成の目標をRepository層で取得
		$goalsOnProgress = $this->GoalRepository->getGoalsOnProgress($user);

		// 目標数を算出
		$count = $goalsOnProgress->count();

		return $count;			
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



}