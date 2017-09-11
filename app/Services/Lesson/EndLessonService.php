<?php 

namespace App\Services\Lesson;

use DB;
use App\Models\Lesson;
use App\Enums\EnumLessonStatus;
use Carbon\Carbon;
use App\Services\Wallet\WalletService;
use App\Services\Billing\GetBillingParamService;
use App\Enums\EnumBillingParam;

class EndLessonService
{
	public function mustEndLesson(Lesson $lesson)
	{
		$exists = DB::table('periods')
		  			->where('lesson_id','=',$lesson->id)
		  			->whereIn('status',[EnumLessonStatus::PENDING, EnumLessonStatus::IN_PROGRESS])
		  			->exists();
		return !$exists;
	}

	public function endLesson(Lesson $lesson)
	{
		$existsFinishedPeriod = DB::table('periods')
		  						  ->where('lesson_id','=',$lesson->id)
		  				    	  ->where('status','=',EnumLessonStatus::FINISHED)
		  					      ->exists();
		$finishedAt = Carbon::now();
		$status = $existsFinishedPeriod ? EnumLessonStatus::FINISHED : EnumLessonStatus::CANCELED;
		$lesson->finished_at = $finishedAt;
		$lesson->status = $status;

		if ($status == EnumLessonStatus::CANCELED) {
			$this->returnPoints($lesson);
		}

		return $lesson->save();
	}

	private function returnPoints(Lesson $lesson)
	{
		$paramCoins = GetBillingParamService::getParam(EnumBillingParam::CLASS_POINTS);
		WalletService::returnPoints($lesson->student->wallet, $paramCoins->value);
	}
}