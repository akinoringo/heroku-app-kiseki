<?php

namespace App\Http\Controllers;

use App\Models\Effort;
use App\Models\Goal;
use App\Models\User;
use App\Services\DayService;
use App\Services\EffortService;
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
  public function index($id){
    
    // viewから受け渡された$idに対応するユーザーの取得
    $user = User::find($id);

    // 目標を進行中->達成済みの順に新しいものから3つ取得する。
    $goals = Goal::where('user_id', $user->id)
      ->orderBy('status', 'asc')
      ->orderBy('created_at', 'desc')
      ->limit(3)
      ->get();

    // 目標タイトルを配列で取得
    $goalsTitle = $goals->pluck('title');

    // 直近1週間の日付を配列で取得
    $daysOnWeek = $this->DayService->getDaysOnWeek();    
    // グラフ用フォーマット(配列)
    $daysOnWeekFormated = $this->DayService->getDaysOnWeekFormated();

    // 1週間の日別積み上げ回数を配列で取得
    $effortsCountOnWeek = $this->EffortService->getEffortsCountOnWeek($goals, $daysOnWeek);    

    // 1週間の日別積み上げ時間を配列で取得
    $effortsTimeTotalOnWeek = $this->EffortService->getEffortsTimeTotalOnWeek($goals, $daysOnWeek);

    return [
      'goalsTitle' => $goalsTitle,
      'daysOnWeekFormated' => $daysOnWeekFormated,
      'effortsCountOnWeek' => $effortsCountOnWeek,
      'effortsTimeTotalOnWeek' => $effortsTimeTotalOnWeek,
    ];     

  }
    

}