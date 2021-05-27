<?php

namespace App\Services;

use App\Models\Effort;
use App\Repositories\Effort\EffortRepositoryInterface as EffortRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EffortService{
	protected $effort_repository;

	public function __construct(EffortRepository $effort_repository){
		$this->EffortRepository = $effort_repository;
	}


	/** 
		* 全ての軌跡を検索語でソートして取得する
		* @param Effort $effort
		* @return  LengthAwarePaginator
	*/
	public function getEffortsWithSearch($search) {

		// 検索語で検索をかけた$effortsを取得
		$efforts = $this->EffortRepository->getEffortsWithSearch($search)
			->paginate(10, ["*"], 'effortspage');

		return $efforts;
	}


	/** 
		* 目標に紐づく軌跡を取得する
		* @param Goal $goal
		* @param Effort $effort
		* @return  Builder
	*/
	public function getEffortsOfGoal($goal){

		// リポジトリ層で$goalに紐づく軌跡を取得
		$effortsOfGoal = $this->EffortRepository->getEffortsOfGoal($goal)->get();

		return $effortsOfGoal;
	}


	/** 
		* フォロー中の人の軌跡を検索語でソートして取得する
		* @param Effort $effort
		* @return  LengthAwarePaginator
	*/
	public function getEffortsOfFollowee() {

		// フォロー中の人の軌跡を取得
		$effortsOfFollowee = $this->EffortRepository->getEffortsOfFollowee()
			->paginate(10, ["*"], "followingeffortspage");	

		return $effortsOfFollowee;
	}	

	/** 
		* 昨日と今日の軌跡を取得する
		* @param Carbon $yesterday
		* @param Carbon $today
		* @param Effort $effort	
		* @return  array
	*/
	public function getEffortsYesterdayAndToday($goal){

		$yesterday = Carbon::yesterday()->format('Y-m-d');
		$today = Carbon::today()->format('Y-m-d');

		$efforts_yesterday = Effort::where('goal_id', $goal->id)
			->where(function($goals) use ($yesterday){
				$goals->whereDate('created_at', $yesterday);
			})->get();

		$efforts_today = Effort::where('goal_id', $goal->id)
			->where(function($goals) use ($today){
				$goals->whereDate('created_at', $today);
			})->get();		

		return array($efforts_yesterday, $efforts_today);
	}	

  /**
    * 今週の日別の積み上げ回数を目標ごとに取得する
    * @return Array
  */  
  public function getEffortsCountOnWeek($goals, $daysOnWeek) {
    for ($i=0; $i < count($goals) ; $i++) {
      for ($j=0; $j < count($daysOnWeek) ; $j++) {
        $effortsOnWeek[$i] = Effort::where('goal_id', $goals[$i]->id)
          ->where(function ($query) use ($daysOnWeek, $j) {
            $query->whereDate('created_at', $daysOnWeek[$j]);
          });

        if ($effortsOnWeek[$i]->exists()) {
          $effortsCountOnWeek[$i][$j] = $effortsOnWeek[$i]->get()->count();

        } else {

          $effortsCountOnWeek[$i][$j] = 0;

        }
      }       
    }    

    return $effortsCountOnWeek; 
  
  } 


  /**
    * 今週の日別の積み上げ時間を目標ごとに取得する
    * @return Array
  */ 
  public function getEffortsTimeTotalOnWeek($goals, $daysOnWeek) {
    for ($i=0; $i < count($goals) ; $i++) {
      for ($j=0; $j < count($daysOnWeek) ; $j++) {
        $effortsOnWeek[$i] = Effort::where('goal_id', $goals[$i]->id)
          ->where(function ($query) use ($daysOnWeek, $j) {
            $query->whereDate('created_at', $daysOnWeek[$j]);
          });

        if ($effortsOnWeek[$i]->exists()) {
          $effortsTimeOnWeek[$i][$j] = $effortsOnWeek[$i]->pluck('effort_time')->all();
          $effortsTimeTotalOnWeek[$i][$j] = array_sum($effortsTimeOnWeek[$i][$j]);                  
        }
        else {
          $effortsTimeTotalOnWeek[$i][$j] = 0;
        }
      }       
    }    

    return $effortsTimeTotalOnWeek; 
  
  }  



}