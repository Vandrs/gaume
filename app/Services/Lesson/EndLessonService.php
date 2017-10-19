<?php 

namespace App\Services\Lesson;

use DB;
use App\Models\Lesson;
use App\Enums\EnumLessonStatus;
use App\Enums\EnumQueue;
use Carbon\Carbon;
use App\Services\Wallet\WalletService;
use App\Services\Billing\GetBillingParamService;
use App\Services\Billing\CalculateLessonBillingService;
use App\Enums\EnumBillingParam;
use App\Notifications\EvaluateClassNotification;
use App\Mail\LessonConfirmationMail;

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
		} else if ($status == EnumLessonStatus::FINISHED) {
			$this->calculateBilling($lesson);
			$this->dispatchNotifications($lesson);
			$this->dispatchEndedLessonEmail($lesson);
		}

		return $lesson->save();
	}

	private function returnPoints(Lesson $lesson)
	{
		$paramCoins = GetBillingParamService::getParam(EnumBillingParam::CLASS_POINTS);
		WalletService::returnPoints($lesson->student->wallet, $paramCoins->value);
	}

	private function calculateBilling(Lesson $lesson)
	{
		$service = new CalculateLessonBillingService();
		$service->calculate($lesson);
	}

	private function dispatchNotifications(Lesson $lesson)
	{
		$notificationTeacher = new EvaluateClassNotification($lesson, $lesson->teacher);
		$notificationTeacher->onQueue(EnumQueue::NOTIFICATION);
		dispatch($notificationTeacher);	

		$notificationStudent = new EvaluateClassNotification($lesson, $lesson->student);
		$notificationStudent->onQueue(EnumQueue::NOTIFICATION);
		dispatch($notificationStudent);	
	}

	private function dispatchEndedLessonEmail(Lesson $lesson)
	{
		$lessonStudentMail = new LessonConfirmationMail($lesson->student, $lesson);
		$lessonStudentMail->onQueue(EnumQueue::EMAIL);
		dispatch($lessonStudentMail);	

		$lessonTeacherMail = new LessonConfirmationMail($lesson->teacher, $lesson);
		$lessonTeacherMail->onQueue(EnumQueue::EMAIL);
		dispatch($lessonTeacherMail);
	}
}