<?php 

namespace App\Services\Lesson;

use App\Models\Period;
use App\Enums\EnumLessonStatus;
use Config;
use Carbon\Carbon;
use App\Services\Wallet\WalletService;

class EndPeriodService
{
	public function canFinishPeriod(Period $period)
	{
		if ($period->status == EnumLessonStatus::IN_PROGRESS) {
			$now = Carbon::now();
			$date = Carbon::createFromTimestamp($period->updated_at->getTimeStamp());
			$date->addHours($period->hours);
			$date->addMinutes(Config::get('lesson.confirm_time'));
			return $now->greaterThan($date);
		}
		return false;
	}

	public function finishPeriod(Period $period)
	{
		$now = Carbon::now();
		$period->finished_at = $now;
		$period->status = EnumLessonStatus::FINISHED;
		return $period->save();
	}


	public function canCancelPeriod(Period $period)
	{
		if ($period->status == EnumLessonStatus::PENDING) {
			$now = Carbon::now();
			$date = Carbon::createFromTimestamp($period->created_at->getTimeStamp());
			$date->addMinutes(Config::get('lesson.confirm_time'));
			return $now->greaterThan($date);
		}
		return false;
	}

	public function cancelPeriod(Period $period)
	{
		$now = Carbon::now();
		$period->finished_at = $now;
		$period->status = EnumLessonStatus::CANCELED;
		$this->returnPoints($period);
		return $period->save();
	}

	private function returnPoints(Period $period)
	{
		$wallet = $period->lesson->student->wallet;
		WalletService::returnPoints($wallet, $period->points);
	}

}

