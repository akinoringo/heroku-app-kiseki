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


  /** 
    * 目標に紐づく軌跡の合計時間を保存する
    * @param Goal $goal
    * @param Effort $effort
    * @return  void
  */
	public function updateEffortsTime($goal)
	{
		$efforts = $this->getEffortsOfGoal($goal);
		$goal->efforts_time = $this->TimeService->sumEffortsTime($efforts);

		$goal->save();
	}

	/** 
		* 全ての軌跡を検索語でソートして取得する
    * @param $search
		* @param Effort $effort
		* @return  LengthAwarePaginator
	*/
	public function getEffortsWithSearch($search) {

		// 未削除の軌跡をすべて取得(Builderとして)
		$allEffortsExist = $this->EffortRepository->getAllEffortsExist();

    // 検索後でタイトルと内容を検索
    $allEffortsFilteredBySearch = $allEffortsExist
      ->where(function($query) use ($search){
        $query->orwhere('title', 'like', "%{$search}%")
          ->orwhere('content', 'like', "%{$search}%");
      })
			->paginate(10, ["*"], 'effortspage');

		return $allEffortsFilteredBySearch;
	}


	/** 
		* 目標に紐づく軌跡を取得する
		* @param Goal $goal
		* @param Effort $effort
		* @return  Collection
	*/
	public function getEffortsOfGoal($goal){

		// リポジトリ層で$goalに紐づく軌跡を取得
		$effortsOfGoal = $this->EffortRepository->getEffortsOfGoal($goal)->get();

		return $effortsOfGoal;
	}

  /** 
    * ユーザーの未削除の軌跡を取得する
    * @param Goal $goal
    * @param Effort $effort
    * @return  LengthAwarePaginator
  */
  public function getAllEffortsOfAUser($user){

    // リポジトリ層で$goalに紐づく軌跡を取得
    $allEffortsOfAUser = $this->EffortRepository->getAllEffortsOfAUser($user)
      ->where('status', 0)
      ->orderBy('created_at', 'DESC')
      ->paginate(5, ["*"], "effortspage");

    return $allEffortsOfAUser;
  }  

	/** 
		* フォロー中の人の軌跡をすべて取得する
		* @param Effort $effort
		* @return  LengthAwarePaginator or null
	*/
	public function getEffortsOfFollowee() {

		// ログイン中であれば、フォロー中の人の軌跡を取得
		if (Auth::check()) {

      // 未削除の軌跡をすべて取得(Builderとして)
      $allEffortsExist = $this->EffortRepository->getAllEffortsExist();      

      // フォロー中の人の軌跡を取得
			$effortsOfFollowee = $allEffortsExist
        ->whereIn('user_id', Auth::user()->followings()->pluck('followee_id'))
        ->orderBy('created_at', 'DESC')
				->paginate(10, ["*"], "followingeffortspage");

		}	else { // 未ログインであれば、nullを返す

			$effortsOfFollowee = null;

		}

		return $effortsOfFollowee;
	}	

	/** 
		* 昨日と今日の軌跡を取得する
		* @param Carbon $yesterday, $today
		* @param Goal $goal
		* @param Effort $effort	
		* @return  array
	*/
	public function getEffortsYesterdayAndToday($goal){

    // 昨日と今日に日付を取得
		$yesterday = Carbon::yesterday()->format('Y-m-d');
		$today = Carbon::today()->format('Y-m-d');

    // 昨日の日付の軌跡を取得
		$effortsOfYesterday = $this->EffortRepository
      ->getEffortsOfADay($goal, $yesterday)
      ->get();

    // 今日の日付の軌跡を取得
		$effortsOfToday = $this->EffortRepository
      ->getEffortsOfADay($goal, $today)
      ->get();				

		return array($effortsOfYesterday, $effortsOfToday);
	}	

  /**
    * 与えられた日付(複数)の日別の積み上げ回数を目標ごとに取得する
    * @param Goal $goal
    * @param Effort $effort
    * @return Array in Array
  */  
  public function getEffortsCountOnDays($goals, $days) {
    // 目標の数だけループ
    for ($i=0; $i < count($goals) ; $i++) {

      // 日付の数だけループ
      for ($j=0; $j < count($days) ; $j++) {

      	// i番目の目標のj日目の軌跡を取得
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
    * 与えられた日付(複数)の日別の積み上げ時間を目標ごとに取得する
    * @param Goal $goal
    * @param Effort $effort
    * @return Array in Array
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
    * 与えられた日付(複数)の週別の積み上げ回数を目標ごとに取得する
    * @param Goal $goal
    * @param Effort $effort
    * @return Array in Array
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
    * 与えられた日付(複数)の週別の積み上げ時間を目標ごとに取得する
    * @param Goal $goal
    * @param Effort $effort
    * @return Array in Array
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

  /**
    * 与えられた日付(複数)の月別の積み上げ回数を目標ごとに取得する
    * @param Goal $goal
    * @param Effort $effort
    * @return Array in Array
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
    * 与えられた日付(複数)の月別の積み上げ時間を目標ごとに取得する
    * @param Goal $goal
    * @param Effort $effort
    * @return Array in Array
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

}