<?php 

namespace App\Services\Lesson;

use App\Services\Service;
use App\Models\LessonEvaluation;
use App\Exceptions\ValidationException;
use App\Enums\EnumStatus;
use App\Enums\EnumQueue;
use App\Enums\EnumRole;
use App\Jobs\MakeUserEvaluation;
use Validator;

class UpdateLessonEvaluationService extends Service
{
	public static function registerPolicies() {}

	public function update(LessonEvaluation $evaluation, array $data) 
	{
		$this->validator = Validator::make($data, $this->validation(), $this->messages());
		if ($this->validator->fails()) {
			throw new ValidationException('Parâmetros inválidos');
		}
		$evaluation->update([
			'status'  => EnumStatus::ACTIVE, 
			'note' 	  => $data['note'], 
			'comment' => isset($data['comment']) ? $data['comment'] : null
		]);
		$this->dispatchJob($evaluation);
	}

	public function validation() 
	{
		return [
			'note' => 'required|integer'
		];
	}

	private function dispatchJob(LessonEvaluation $evaluation)
	{
		if ($evaluation->type == EnumRole::STUDENT) {
			$user = $evaluation->lesson->student;
		} else if ($evaluation->type == EnumRole::TEACHER) {
			$user = $evaluation->lesson->teacher;
		}
		$job = new MakeUserEvaluation($user);
		$job->onQueue(EnumQueue::USER);
		dispatch($job);
	}

	public function messages()
	{
		return [
			'note.required' => __('validation.required', ['attribute' => __('evaluation.note')]),
			'note.integer'  => __('validation.integer', ['attribute' => __('evaluation.note')])
		];
	}
}

