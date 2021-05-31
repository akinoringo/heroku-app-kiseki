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
    $daysOnWeek[0] = $startOfWeek->format('n/j');    

    //Carbonのインスタンスが上書きされないようにcopy()して日付を加算
    for ($i=1; $i < $numOfDays ; $i++) {
      $daysOnWeek[$i] = $startOfWeek->copy()->addDay($i)->format('n/j');
    }

    return $daysOnWeek;

  }  	

  /**
    * 与えられた日付範囲を取得する
    * @return Array
  */
  public function getDaysForGraph($startdate, $enddate) {

    $startdateOnCarbon = new Carbon($startdate);
    $enddateOnCarbon = new Carbon($enddate);

    $diffInDays = $startdateOnCarbon->diffInDays($enddateOnCarbon);

    $start = $startdateOnCarbon;

    $daysForGraph[0] = $start;

    //Carbonのインスタンスが上書きされないようにcopy()して日付を加算
    for ($i=1; $i <= $diffInDays ; $i++) {
      $daysForGraph[$i] = $start->copy()->addDay($i);
    }    

    return $daysForGraph;

  }

  /**
    * 与えられた日付範囲を取得する
    * @return Array
  */
  public function getDaysForGraphFormated($startdate, $enddate) {

    $startdateOnCarbon = new Carbon($startdate);
    $enddateOnCarbon = new Carbon($enddate);

    $diffInDays = $startdateOnCarbon->diffInDays($enddateOnCarbon);

    $start = $startdateOnCarbon;

    $daysForGraph[0] = $start->format('n/j');

    //Carbonのインスタンスが上書きされないようにcopy()して日付を加算
    for ($i=1; $i <= $diffInDays ; $i++) {
      $daysForGraph[$i] = $start->copy()->addDay($i)->format('n/j');
    }    

    return $daysForGraph;

  }   

  /**
    * 与えられた開始日と終了日の間の月を配列で返す
    * @return Array
  */
  public function getMonthsForGraph($startdate, $enddate) {

    date_default_timezone_set('Asia/Tokyo');

    $startdateOnCarbon = new Carbon($startdate);
    $enddateOnCarbon = new Carbon($enddate);   

    $diffInMonths = $startdateOnCarbon->diffInMonths($enddateOnCarbon);
    
    // // 開始日と終了日の月の差を算出
    // $monthOfstartdate = $startdateOnCarbon->month;
    // $monthOfenddate = $enddateOnCarbon->month;

    // if ($monthOfstartdate < $monthOfenddate) {

    //   $diffInMonths = $monthOfenddate - $monthOfstartdate;      

    // } else {

    //   $diffInMonths = (12 - $monthOfstartdate) + $monthOfenddate;
    // }

    // 月を配列で取得
    $months[0] = $startdateOnCarbon;

    for ($i=1; $i <= $diffInMonths ; $i++) {
      $months[$i] = $startdateOnCarbon->copy()->addMonthNoOverflow($i);
    }     

    return $months;

  }


  /**
    * 与えられた開始日と終了日の間の月を配列で返す(Formated)
    * @return Array
  */
  public function getMonthsForGraphFormated($months) {

    $i = 0;

    foreach ($months as $month) {
      $monthsFormated[$i] = $month->format('Y/n');
      $i++;
    }

    return $monthsFormated;

  }

  public function getDiffInDays($startdate, $enddate) {

    $startdateOnCarbon = new Carbon($startdate);
    $enddateOnCarbon = new Carbon($enddate);

    $diffInDays = $startdateOnCarbon->diffInDays($enddateOnCarbon);

    return $diffInDays;    

  }

  // 
  public function getWeeksForGraph($startdate, $enddate) {

    // Carbonインスタンスを作成
    $startdateOnCarbon = new Carbon($startdate);
    $enddateOnCarbon = new Carbon($enddate); 

    // 年の中で何週目かを取得
    $weekOfYearOfStartDate = $startdateOnCarbon->weekOfYear; // 4
    $weekOfYearOfEndDate = $enddateOnCarbon->weekOfYear; // 10

    // // 週の差分を取得
    // if ($weekOfYearOfStartDate < $weekOfYearOfEndDate) {

    //   $diffInWeeks = $weekOfYearOfEndDate - $weekOfYearOfStartDate; // 6 

    // } else { // 5, 51

    //   $diffInWeeks = (52 - $weekOfYearOfStartDate) + $weekOfYearOfEndDate;

    // }

    $diffInWeeks = $startdateOnCarbon->diffInWeeks($enddateOnCarbon);

    $weeks[0] = $startdateOnCarbon->startOfWeek();

    for ($i=1; $i <= $diffInWeeks ; $i++) {
      $weeks[$i] = $startdateOnCarbon->copy()->addWeeks($i)->startOfWeek();
    }     

    return $weeks;

  }

  // 
  public function getWeeksForGraphFormated($weeks) {


    $i = 0;

    foreach ($weeks as $week) {
      $weeksFormated[$i] = $week->format('n月') . $week->weekNumberInMonth . "週";
      $i++;
    }

    return $weeksFormated;

  }  



}
