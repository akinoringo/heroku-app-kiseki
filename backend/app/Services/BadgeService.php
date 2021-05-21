<?php

namespace App\Services;

use App\Models\Effort;
use App\Models\Goal;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BadgeService{
	// 積み上げ時間が99時間以上でバッジを獲得
	public function getEffortsTimeBadge($user, $goal){
		if ($goal->efforts_time > 99 && $user->efforts_time_badge == 0) {
			$user->efforts_time_badge = 1;
			session()->flash('badge_message', 'おめでとうございます。忍耐力の称号を取得しました。');
			session()->flash('badge_color', 'primary');				
		}		
	}

	// 積み上げ日数が10日以上でバッジを獲得
	public function getStackingDaysBadge($user, $goal){
		if ($goal->stacking_days > 9 && $user->stacking_days_badge == 0) {
			$user->stacking_days_badge = 1;
			session()->flash('badge_message', 'おめでとうございます。継続力の称号を取得しました。');
			session()->flash('badge_color', 'primary');				
		}			
	}

	// 目標をクリアしたら、バッジを獲得
	public function getGoalClearBadge($user, $goal){
		if ($goal->status == 1 && $user->goal_clear_badge == 0) {
			$user->goal_clear_badge = 1;
			session()->flash('badge_message', 'おめでとうございます。達成力の称号を取得しました。');
			session()->flash('badge_color', 'primary');				

		}		
	}	

}