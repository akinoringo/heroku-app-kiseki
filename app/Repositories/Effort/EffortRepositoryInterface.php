<?php

namespace App\Repositories\Effort;

interface EffortRepositoryInterface
{
	public function storeEffort($effort, $request);

	public function updateEffort($effort, $request);

	public function destroyEffort($effort);	

	public function getAllEffortsExist();

	public function getAllEffortsOfAUser($user);	

	public function getEffortsOfGoal($goal);	

	public function getEffortsOfADay($goal, $day);

	public function getEffortsOfAWeek($goal, $day);	

	public function getEffortsOfAMonth($goal, $day);		
}