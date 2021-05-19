<?php

namespace App\Http\Controllers;

use App\Effort;
use App\Goal;
use App\User;

use DB;
use Illuminate\Http\Request;

class EffortGraphController extends Controller
{
  public function index($id){
    // viewから受け渡された$idに対応するユーザーの取得
    $user = User::find($id);
    $goal_label = 0;
    $goals = Goal::where('user_id', $user->id)->get();

    $goalsTitle = $goals->pluck('title');

    // 直近1週間の日付を取得
    $daysOnWeek = $this->getDaysOnWeek();    

    // 1週間の日別積上回数を配列で取得
    $effortsCountOnWeek = $this->getEffortsCountOnWeek($goals, $daysOnWeek);    

    // 1週間の日別積上時間を配列で取得
    $effortsTimeTotalOnWeek = $this->getEffortsTimeTotalOnWeek($goals, $daysOnWeek);

    return [
      'goalsTitle' => $goalsTitle,
      'daysOnWeek' => $daysOnWeek,
      'effortsCountOnWeek' => $effortsCountOnWeek,
      'effortsTimeTotalOnWeek' => $effortsTimeTotalOnWeek,
    ];     

  }


  /**
    * ユーザーの(未達成or達成済みの)目標を全て取得する
    * @param Goal $goal
    * @return Builder
  */    
  private function goalsGet($user, $goal_label)
  {
    if ($goal_label == 1) {
      $goals = Goal::where('user_id', $user->id)
        ->where(function($goals){
          $goals->where('status', 1);
        })->get();
    } else {
      $goals = Goal::where('user_id', $user->id)
        ->where(function($goals){
          $goals->where('status', 0);
        })->get();      
    }

    return $goals;
  }

  /**
    * 今週の日付を取得する
    * @return Array
  */ 
  private function getDaysOnWeek() {
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
    * 今週の日別の積み上げ時間を目標ごとに取得する
    * @return Array
  */ 
  private function getEffortsTimeTotalOnWeek($goals, $daysOnWeek) {
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

  /**
    * 今週の日別の積み上げ回数を目標ごとに取得する
    * @return Array
  */  
  private function getEffortsCountOnWeek($goals, $daysOnWeek) {
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

}