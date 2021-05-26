<?php

namespace App\Repositories\Goal;

interface GoalRepositoryInterface
{
	// 未達成の目標を取得する。
	public function getGoalsOnProgress($user);
}