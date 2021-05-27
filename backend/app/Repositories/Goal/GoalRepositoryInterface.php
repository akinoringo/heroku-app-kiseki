<?php

namespace App\Repositories\Goal;

interface GoalRepositoryInterface
{
	// 未達成の目標を取得する。
	public function getGoalsOnProgress($user);

	// 軌跡が紐づいている目標を取得する。
	public function getGoalOfEffort($id);

	// 目標を保存する。
	public function storeGoal($request, $goal);

	// 目標を更新する。
	public function updateGoal($request, $goal);

	// 目標を削除する。
	public function destroy($goal);

	// 目標を達成済にするする。
	public function clear($goal);				
}