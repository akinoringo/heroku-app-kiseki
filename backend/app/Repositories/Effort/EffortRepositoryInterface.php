<?php

namespace App\Repositories\Effort;

interface EffortRepositoryInterface
{
	public function storeEffort($effort, $request);

	public function updateEffort($effort, $request);

	public function destroyEffort($effort);		

	public function getEffortsWithSearch($search);	

	public function getEffortsOfGoal($goal);	

	public function getEffortsOfFollowee();

	public function getEffortsOfADay($goal, $day);		
}