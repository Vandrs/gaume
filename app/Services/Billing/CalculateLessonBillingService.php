<?php

namespace App\Services\Billing;

use App\Models\Lesson;
use App\Enums\EnumLessonStatus;


class CalculateLessonBillingService
{
	public function calculate(Lesson $lesson)
	{
		$points = 0;
		$value = 0;

		$periods = $this->getFinishedPeriods($lesson);
		$periods->each(function($period) use (&$points, &$value, &$hours){
			$points += $period->points;
			$value += $period->hours * $period->hour_value;
		});

		return $lesson->update([
			'points' => $points,
			'value' => $value
		]);

	}	

	private function getFinishedPeriods(Lesson $lesson)
	{
		$periods = $lesson->periods->filter(function($period){
			return $period->status == EnumLessonStatus::FINISHED;
		});
		return $periods;
	}
}