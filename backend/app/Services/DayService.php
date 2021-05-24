<?php

namespace App\Services;

use App\Models\Effort;
use App\Models\Goal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DayService{
	public function addStackingdays($goal, $efforts_today) {
		// 本日の軌跡がなければ、積み上げ日数を+1
		if ($efforts_today->isEmpty()) {
			$goal->stacking_days += 1;						
		}

	}	

	public function updateContinuationdays($goal, $efforts_yesterday, $efforts_today){
		// 昨日の軌跡がなければ、継続日数を1にリセットする
		if ($efforts_yesterday->isEmpty()) {
			$goal->continuation_days = 1;		
		}			

		// 昨日の軌跡が存在し、今日の軌跡が空だった場合継続日数を+1
		if ($efforts_yesterday->isNotEmpty() && $efforts_today->isEmpty()) {
			$goal->continuation_days += 1;
		}		

	}

	public function updateContinuationdaysmax($goal){
		// 最大継続日数を更新する
		if ($goal->continuation_days_max < $goal->continuation_days) {
			$goal->continuation_days_max = $goal->continuation_days;
		}		
	}	

	public function checkGoalDeadline($goal){
		$today = Carbon::today();
		if ($today->gt($goal->deadline) ) {
			session()->flash('deadline_message', '目標達成期限を過ぎています。修正してください。');
			session()->flash('deadline_color', 'danger');				
		} 

	}

  /**
    * 今週の日付を取得する
    * @return Array
  */ 
  public function getDaysOnWeek() {
    //1週間の日数
    $numOfDays = 7; 

    //週の始まりの日付
    $startOfWeek = now()->startOfWeek();
    $daysOnWeek[0] = $startOfWeek->format('Y-m-d');    

    //Carbonのインスタンスが上書きされないようにcopy()して日付を加算
    for ($i=1; $i < $numOfDays ; $i++) {
      $daysOnWeek[$i] = $startOfWeek->copy()->addDay($i)->format('Y-m-d');
    }

    return $daysOnWeek;

  }

  /**
    * 今週の日付を取得する(Format:n/d)
    * @return Array
  */ 
  public function getDaysOnWeekFormated() {
    //1週間の日数
    $numOfDays = 7; 

    //週の始まりの日付
    $startOfWeek = now()->startOfWeek();
    $daysOnWeek[0] = $startOfWeek->format('n/d');    

    //Carbonのインスタンスが上書きされないようにcopy()して日付を加算
    for ($i=1; $i < $numOfDays ; $i++) {
      $daysOnWeek[$i] = $startOfWeek->copy()->addDay($i)->format('n/d');
    }

    return $daysOnWeek;

  }  	


}
