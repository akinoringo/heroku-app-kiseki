<?php

namespace App\Services;

use App\Models\Effort;
use App\Repositories\Effort\EffortRepositoryInterface as EffortRepository;
use App\Services\TimeService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EffortService{
	protected $effort_repository;
	protected $time_service;	

	public function __construct(EffortRepository $effort_repository, TimeService $time_service){
		$this->EffortRepository = $effort_repository;
		$this->TimeService = $time_service;
	}

	public function storeEffortsTime($goal)
	{
		$efforts = $this->getEffortsOfGoal($goal);
		$goal->efforts_time = $this->TimeService->sumEffortsTime($efforts);

		$goal->save();
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

		// ログイン中であれば、フォロー中の人の軌跡を取得
		if (Auth::check()) {

			$effortsOfFollowee = $this->EffortRepository->getEffortsOfFollowee()
				->paginate(10, ["*"], "followingeffortspage");

		}	else { // 未ログインであれば、nullを返す

			$effortsOfFollowee = null;

		}

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

		$effortsOfYesterday = $this->EffortRepository->getEffortsOfADay($goal, $yesterday)->get();

		$effortsOfToday = $this->EffortRepository->getEffortsOfADay($goal, $today)->get();				

		return array($effortsOfYesterday, $effortsOfToday);
	}	

  /**
    * 今週の日別の積み上げ回数を目標ごとに取得する
    * @return Array
  */  
  public function getEffortsCountOnDays($goals, $days) {
    for ($i=0; $i < count($goals) ; $i++) {

      for ($j=0; $j < count($days) ; $j++) {

      	// i番目の目標のj日の軌跡を取得
        $effortsOnADay = $this->EffortRepository->getEffortsOfADay($goals[$i], $days[$j]);

        // 軌跡が存在するとき
        if ($effortsOnADay->exists()) { 
          $effortsCountOnWeek[$i][$j] = $effortsOnADay->get()->count();

        } else { // 軌跡が存在しないとき

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
  public function getEffortsTimeTotalOnDays($goals, $days) {
    for ($i=0; $i < count($goals) ; $i++) {
      for ($j=0; $j < count($days) ; $j++) {

      	// i番目の目標のj日の軌跡を取得
        $effortsOnADay = $this->EffortRepository->getEffortsOfADay($goals[$i], $days[$j]);      	

        if ($effortsOnADay->exists()) { // 軌跡が存在するとき

        	// 軌跡の積み上げ時間を積算し、i番目の目標のj日目のものとして保存
          $effortsTimeTotalOnWeek[$i][$j] = array_sum($effortsOnADay->pluck('effort_time')->all());
                
        }
        else { // 軌跡が存在しないとき

          $effortsTimeTotalOnWeek[$i][$j] = 0;

        }
      }       
    }    

    return $effortsTimeTotalOnWeek; 
  
  }

  /**
    * 月別の積み上げ回数を目標ごとに取得する
    * @return Array
  */  
  public function getEffortsCountOnMonth($goals, $month) {
    for ($i=0; $i < count($goals) ; $i++) {

      for ($j=0; $j < count($month) ; $j++) {

      	// i番目の目標のj月の軌跡を取得
        $effortsOnAMonth = $this->EffortRepository->getEffortsOfAMonth($goals[$i], $month[$j]);

        // 軌跡が存在するとき
        if ($effortsOnAMonth->exists()) { 
          $effortsCountOnMonth[$i][$j] = $effortsOnAMonth->get()->count();

        } else { // 軌跡が存在しないとき

          $effortsCountOnMonth[$i][$j] = 0;

        }
      }       
    }    

    return $effortsCountOnMonth; 
  
  } 

  /**
    * 月別の積み上げ時間を目標ごとに取得する
    * @return Array
  */  
  public function getEffortsTimeTotalOnMonth($goals, $month) {
    for ($i=0; $i < count($goals) ; $i++) {

      for ($j=0; $j < count($month) ; $j++) {

      	// i番目の目標のj月の軌跡を取得
        $effortsOnAMonth = $this->EffortRepository->getEffortsOfAMonth($goals[$i], $month[$j]);

        // 軌跡が存在するとき
        if ($effortsOnAMonth->exists()) { 

        	// 軌跡の積み上げ時間を積算し、i番目の目標のj月目のものとして保存
          $effortsTimeTotalOnMonth[$i][$j] = array_sum($effortsOnAMonth->pluck('effort_time')->all());          

        } else { // 軌跡が存在しないとき

          $effortsTimeTotalOnMonth[$i][$j] = 0;

        }
      }       
    }    

    return $effortsTimeTotalOnMonth; 
  
  }  

  /**
    * 週別の積み上げ回数を目標ごとに取得する
    * @return Array
  */  
  public function getEffortsCountOnWeeks($goals, $weeks) {
    for ($i=0; $i < count($goals) ; $i++) {

      for ($j=0; $j < count($weeks) ; $j++) {

      	// i番目の目標のj月の軌跡を取得
        $effortsOnAWeek = $this->EffortRepository->getEffortsOfAWeek($goals[$i], $weeks[$j]);

        // 軌跡が存在するとき
        if ($effortsOnAWeek->exists()) { 
          $effortsCountOnWeeks[$i][$j] = $effortsOnAWeek->get()->count();

        } else { // 軌跡が存在しないとき

          $effortsCountOnWeeks[$i][$j] = 0;

        }
      }       
    }    

    return $effortsCountOnWeeks; 
  
  } 

  /**
    * 週別の積み上げ回数を目標ごとに取得する
    * @return Array
  */  
  public function getEffortsTimeTotalOnWeeks($goals, $weeks) {
    for ($i=0; $i < count($goals) ; $i++) {

      for ($j=0; $j < count($weeks) ; $j++) {

      	// i番目の目標のj月の軌跡を取得
        $effortsOnAWeek = $this->EffortRepository->getEffortsOfAWeek($goals[$i], $weeks[$j]);

        // 軌跡が存在するとき
        if ($effortsOnAWeek->exists()) { 

        	// 軌跡の積み上げ時間を積算し、i番目の目標のj週目のものとして保存        	
          $effortsTimeTotalOnWeeks[$i][$j] = array_sum($effortsOnAWeek->pluck('effort_time')->all());

        } else { // 軌跡が存在しないとき

          $effortsTimeTotalOnWeeks[$i][$j] = 0;

        }
      }       
    }    

    return $effortsTimeTotalOnWeeks; 
  
  }      





}