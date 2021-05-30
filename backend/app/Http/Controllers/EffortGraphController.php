<?php

namespace App\Http\Controllers;

use App\Models\Effort;
use App\Models\Goal;
use App\Models\User;
use App\Services\DayService;
use App\Services\EffortService;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class EffortGraphController extends Controller
{
  protected $day_service;
  protected $effort_service;

  public function __construct(DayService $day_service, EffortService $effort_service)
  {
    $this->DayService = $day_service;
    $this->EffortService = $effort_service;
  }  

  // マイページのグラフ表示に必要なパラメータを取得
  // 戻り値：目標タイトル, 直近1週間の日付, 軌跡の積み上げ数, 軌跡の積み上げ時間
  public function index($id, Request $request){

    // viewから受け渡された$idに対応するユーザーの取得
    $user = User::find($id);

    // 目標を進行中->達成済みの順に新しいものから3つ取得する。
    $goals = Goal::where('user_id', $user->id)
      ->orderBy('status', 'asc')
      ->limit(3)
      ->orderBy('created_at', 'asc')
      ->get();

    // 目標タイトルを配列で取得
    $goalsTitle = $goals->pluck('title');


    // リクエストの日付を取得
    $startdate = $request->startdate;
    $enddate = $request->enddate;

    $diffInDays = $this->DayService->getDiffInDays($startdate, $enddate);

    // 日付の範囲を指定した場合
    if ($startdate && $enddate && $diffInDays <= 31 ) {

      $daysForGraph = $this->DayService->getDaysForGraph($startdate, $enddate);

      $parametersXForGraph = $this->DayService->getDaysForGraphFormated($startdate, $enddate);
      $parametersCountForGraph = $this->EffortService->getEffortsCountOnDays($goals, $daysForGraph); 
      $parametersTimeForGraph = $this->EffortService->getEffortsTimeTotalOnDays($goals, $daysForGraph);


    } else if ($startdate && $enddate && $diffInDays <= 62) {

      // 
      $daysForGraph = $this->DayService->getWeeksForGraph($startdate, $enddate);

      $parametersXForGraph = $this->DayService->getWeeksForGraphFormated($daysForGraph);
      // $parametersXForGraph = $daysForGraph;  
      

      $parametersCountForGraph = $this->EffortService->getEffortsCountOnWeeks($goals, $daysForGraph);

      // $parametersCountForGraph　= [2,4];

      $parametersTimeForGraph = $this->EffortService->getEffortsTimeTotalOnWeeks($goals, $daysForGraph);  
      // $parametersTimeForGraph = 4;             

    } else if ($startdate && $enddate && $diffInDays > 62) {

      // リクエスト範囲の年月日を配列で取得['3月5日 17:00', '4月5日 17:00', '5月5日 17:00']
      $months = $this->DayService->getMonthsForGraph($startdate, $enddate);

      $parametersXForGraph = $this->DayService->getMonthsForGraphFormated($months);

      // $parametersXForGraph = $months;

      $parametersCountForGraph = $this->EffortService->getEffortsCountOnMonth($goals, $months);

      // $parametersCountForGraph = [10, 20, 30];


      $parametersTimeForGraph = $this->EffortService->getEffortsTimeTotalOnMonth($goals, $months);

      // $parametersTimeForGraph = [20, 40, 60];



    }

    else { // 日付の範囲を指定していない場合は、今週1週間の軌跡を取得

      // 直近1週間の日付を配列で取得
      $daysOnWeek = $this->DayService->getDaysOnWeek(); 

      // グラフ用フォーマット(配列)
      $daysOnWeekFormated = $this->DayService->getDaysOnWeekFormated(); 

      // 1週間の日別積み上げ回数を配列で取得
      $effortsCountOnWeek = $this->EffortService->getEffortsCountOnDays($goals, $daysOnWeek);    

      // 1週間の日別積み上げ時間を配列で取得
      $effortsTimeTotalOnWeek = $this->EffortService->getEffortsTimeTotalOnDays($goals, $daysOnWeek);            

      $parametersXForGraph = $daysOnWeekFormated;
      $parametersCountForGraph = $effortsCountOnWeek;
      $parametersTimeForGraph = $effortsTimeTotalOnWeek;

    }    

    return [
      'goalsTitle' => $goalsTitle, // 必須
      'daysOnWeekFormated' => $parametersXForGraph, // 必須
      'effortsCountOnWeek' => $parametersCountForGraph, // 必須
      'effortsTimeTotalOnWeek' => $parametersTimeForGraph, // 必須
      'daysForGraph' => ['test1', 'test2'], // 必須
    ];     

  }
    

}