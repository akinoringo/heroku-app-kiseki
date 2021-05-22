<?php

namespace App\Services;

use App\Models\Effort;
use App\Models\Goal;
use Illuminate\Support\Facades\Auth;

class TimeService{
	/** 
		* 軌跡の合計時間を計算する
		* @param Effort $effort		
		* @return  int
	*/
	public function sumEffortsTime($efforts) {
		$total_efforts_time = 0;

		foreach ($efforts as $effort) {
			$total_efforts_time += $effort->effort_time;
			
		}
		return $total_efforts_time;
	}		

}