<?php

namespace App\Services;

use App\Models\Effort;
use App\Models\User;
use Carbon\Carbon;

class RankingService{

	/** 
		* 今月の積み上げ回数が多い順にユーザーを取得して連想配列で返す		
		* @return  array
	*/
	public function rankingEffortsCount() {

		// ユーザーの軌跡登録数をカウントして、多い順に配列で取得する。
		$users = User::withCount(['efforts' => function ($efforts) {
			$efforts
				->where('created_at', '>=', Carbon::now()->startOfMonth())
				->where('created_at', '<=', Carbon::now()->endOfMonth());
			}])
			->orderBy('efforts_count', 'desc')
			->limit(10)
			->get();


		$ranked_users = [];

		if ($users->isNotEmpty()) {
			// 軌跡登録数とユーザー名の配列を取得
			$efforts_count = $users->pluck('efforts_count');
			$users_name = $users->pluck('name');
			$ids = $users->pluck('id');

			$compared_efforts_count = $efforts_count[0] + 1;

			$rank = 0;
			$i = 0;
			$plus = 0;
			foreach ($efforts_count as $value) {

				if ($value == $compared_efforts_count) {
					$plus += 1; 
				}				

				if ($value < $compared_efforts_count) {
					$rank += $plus + 1;
					$plus =0;
				}

				if ($rank > 5 ) {
					break;
				}	
				
				array_push($ranked_users, ['rank' => $rank, 'name'=> $users_name[$i], 'efforts_count' => $efforts_count[$i], 'id' => $ids[$i]]);	

				$compared_efforts_count = $value;	
				$i++;
	
			}	

		}

		return $ranked_users;

	}		

}