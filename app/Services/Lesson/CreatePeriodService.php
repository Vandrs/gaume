<?php 

namespace App\Services\Lesson;

use Config;
use Gate;
use Log;
use Lang;
use App\Enums\EnumLessonStatus;
use App\Enums\EnumPolicy;
use App\Enums\EnumRole;
use App\Enums\EnumQueue;
use App\Enums\EnumBillingParam;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Period;
use App\Models\Wallet;
use App\Services\Service;
use App\Services\Wallet\WalletService;
use App\Services\Billing\GetBillingParamService;
use App\Exceptions\ValidationException;
use App\Exceptions\AuthorizationException;
use Carbon\Carbon;
use App\Jobs\CheckInProgressPeriod;
use App\Jobs\CheckPendingPeriod;
use App\Notifications\LessonStartNotification;

class CreatePeriodService extends Service
{

	private $paramCoins;
	private $paramValue;

	public function __construct()
	{
		$this->paramCoins = GetBillingParamService::getParam(EnumBillingParam::CLASS_POINTS);		
		$this->paramValue = GetBillingParamService::getParam(EnumBillingParam::CLASS_VALUE);		
	}

	public static function registerPolicies()
	{
		$service = new self();
		Gate::define(EnumPolicy::CREATE_PERIOD, function(User $user, Lesson $lesson) use ($service) {
			return $service->createPolicy($user, $lesson);
		});
	}

	public function createFromLesson(Lesson $lesson)
	{
		if ($lesson->status !== EnumLessonStatus::IN_PROGRESS) {
			throw new AuthorizationException(__('validation.custom.class_confirmation_expired'));
		}

		$period = Period::create([
			'lesson_id'  => $lesson->id,
			'hours' 	 => Config::get('lesson.lesson_time'),
			'hour_value' => $this->paramValue->value,
			'points' 	 => $this->paramCoins->value,
			'status' 	 => EnumLessonStatus::IN_PROGRESS
		]);

		$this->dispatchJobInProgress($period);

		return $period;
	}

	public function createToRenewLesson(Lesson $lesson)
	{
		if ($lesson->status !== EnumLessonStatus::IN_PROGRESS) {
			throw new AuthorizationException(__('validation.custom.class_confirmation_expired'));
		}

		$this->checkWallet($lesson->student->wallet);

		$period = Period::create([
			'lesson_id'  => $lesson->id,
			'hours' 	 => Config::get('lesson.lesson_time'),
			'hour_value' => $this->paramValue->value,
			'points' 	 => $this->paramCoins->value,
			'status' 	 => EnumLessonStatus::PENDING
		]);

		$this->discountWallet($lesson->student->wallet);
		$this->dispatchJobPending($period);
		$this->dispatchNotificationJob($period);

		return $period;
	}

	private function checkWallet(Wallet $wallet)
	{
		if (!WalletService::checkPoints($wallet, $this->paramCoins->value)) {
			throw new AuthorizationException(Lang::get('wallet.no_credits',['link' => route('pagseguro.redirect')]));
		}
	}
	
	private function discountWallet(Wallet $wallet)
	{
		WalletService::removePoints($wallet, $this->paramCoins->value);	
	}

	public function createPolicy(User $user, Lesson $lesson)
	{
		return $user->hasRole(EnumRole::STUDENT) && $user->id == $lesson->student_id;
	}

	private function dispatchJobInProgress(Period $period)
	{
		$job = new CheckInProgressPeriod($period);
		$minutes = Config::get('lesson.confirm_time');
		$minutes += 1;
		$delayTime = Carbon::now()->addHours($period->hours)
								  ->addMinutes($minutes);
		$job->delay($delayTime)
			->onQueue(EnumQueue::LESSON);
		dispatch($job);
	}

	private function dispatchJobPending(Period $period)
	{
		$job = new CheckPendingPeriod($period);
		$minutes = Config::get('lesson.confirm_time');
		$minutes += 1;
		$delayTime = Carbon::now()->addMinutes($minutes);
		$job->delay($delayTime)
			->onQueue(EnumQueue::LESSON);
		dispatch($job);
	}

	private function dispatchNotificationJob(Period $period)
	{
		try {
			$user = $period->lesson->teacher;
			$user->notify(new LessonStartNotification($period->lesson, $user));
			return true;
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return false;
		}
	}

}