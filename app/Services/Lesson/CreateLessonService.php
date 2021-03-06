<?php 

namespace App\Services\Lesson;

use Gate;
use Config;
use Validator;
use Log;
use Lang;
use DB;
use Illuminate\Validation\Rule;
use App\Services\Service;
use App\Services\Wallet\WalletService;
use App\Services\Billing\GetBillingParamService;
use App\Enums\EnumPolicy;
use App\Enums\EnumRole;
use App\Enums\EnumLessonStatus;
use App\Enums\EnumQueue;
use App\Enums\EnumBillingParam;
use App\Exceptions\ValidationException;
use App\Exceptions\AuthorizationException;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Wallet;
use App\Jobs\CheckPendingLesson;
use App\Notifications\LessonStartNotification;
use Carbon\Carbon;

class CreateLessonService extends Service
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
		Gate::define(EnumPolicy::CREATE_LESSON, function(User $user) use ($service) {
			return $service->createPolicy($user);
		});
	}

	public function create(User $student, array $data)
	{
		$this->validator = Validator::make(
								$data, 
								$this->createValidation(), 
								$this->createValidationMessages()
							);
		if ($this->validator->fails()) {
			throw new ValidationException('FALHA AO VALIDAR: '.json_encode($this->validator->errors()->all()));
		}

		$this->checkWallet($student->wallet);

		$lesson = Lesson::create([
			'teacher_id'  => $data['teacher_id'],
			'student_id'  => $student->id,
			'game_id'	  => $data['game_id'],
			'platform_id' => $data['platform_id'],
			'status'	  => EnumLessonStatus::PENDING
		]);

		$this->discountWallet($student->wallet);
		$this->dispatchJobCheckPendingJob($lesson);		
		$this->dispatchNotificationJob($lesson);

		return $lesson;
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

	private function dispatchJobCheckPendingJob(Lesson $lesson)
	{
		$delayJobTime = Carbon::now()->addMinutes(Config::get('lesson.confirm_time'));
		$job = new CheckPendingLesson($lesson);
		$job->delay($delayJobTime)
			->onQueue(EnumQueue::LESSON);
		dispatch($job);
	}

	private function dispatchNotificationJob(Lesson $lesson)
	{
		$notification = new LessonStartNotification($lesson, $lesson->teacher);
		$notification->onQueue(EnumQueue::NOTIFICATION);
		dispatch($notification);
	}

	
	public function createPolicy(User $user)
	{
		return $user->hasRole(EnumRole::STUDENT);
	}

	private function createValidation()
	{
		return [
			'teacher_id'  => ['required', 'integer', Rule::exists('users','id')->where('role_id', EnumRole::TEACHER_ID)],
			'game_id' 	  => ['required', 'integer', Rule::exists('games','id')->whereNull('deleted_at')],
			'platform_id' => ['required', 'integer', Rule::exists('platforms','id')->whereNull('deleted_at')],
		];
	}

	private function confirmValidation()
	{
		return [
			'confirmed' => "required|boolean"
		];
	}

	private function createValidationMessages()
	{
		return [
			'teacher_id.required' => __('validation.required',['attribute' => 'teacher_id']),
			'teacher_id.integer'  => __('validation.integer',['attribute' => 'teacher_id']),
			'teacher_id.exists'   => __('validation.custom.is_not_teacher'),
			'game_id.required'	  => __('validation.required',['attribute' => __('games.game')]),
			'platform_id.required' => __('validation.required',['attribute' => __('games.platform')])
		];	
	}

}