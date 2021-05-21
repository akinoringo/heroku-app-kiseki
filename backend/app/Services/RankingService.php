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
	public function ranking() {

		// ユーザーの軌跡登録数をカウントして、多い順に配列で取得する。
		$users = User::withCount(['efforts' => function ($efforts) {
			$efforts
				->where('created_at', '>=', Carbon::now()->startOfMonth())
				->where('created_at', '<=', Carbon::now()->endOfMonth());
			}])
			->orderBy('efforts_count', 'desc')
			->get();


		$ranked_users = [];

		if ($users->isNotEmpty()) {
			// 軌跡登録数とユーザー名の配列を取得
			$efforts_count = $users->pluck('efforts_count');
			$users_name = $users->pluck('name');
			$ids = $users->pluck('id');

			$compared_efforts_count = $efforts_count[0];

			$rank = 1;
			$i = 0;
			foreach ($efforts_count as $value) {

				if ($value < $compared_efforts_count) {
					$rank++;
				}
				
				array_push($ranked_users, ['rank' => $rank, 'name'=> $users_name[$i], 'efforts_count' => $efforts_count[$i], 'id' => $ids[$i]]);		

				$i++;		
			}	

		}

		return $ranked_users;

	}		

}