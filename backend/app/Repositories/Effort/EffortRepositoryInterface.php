<?php

namespace App\Repositories\Effort;

interface EffortRepositoryInterface
{
	public function getEffortsWithSearch($search);	

	public function getEffortsOfGoal($goal);	

	public function getEffortsOfFollowee();

	public function getEffortsOfADay($goal, $day);		
}