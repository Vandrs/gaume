<?php

namespace App\Services\Lesson;

use App\Models\Lesson;
use App\Models\User;
use App\Models\LessonEvaluation;
use App\Enums\EnumStatus;
use App\Enums\EnumRole;
use App\Enums\EnumQueue;
use Carbon\Carbon;
use Mail;
use Config;

class CreateLessonEvaluationService
{
	public function create(Lesson $lesson)
	{
		$teacherEvaluation = $this->createForTeacher($lesson, $lesson->teacher);
		$studentEvaluation = $this->createForStudent($lesson, $lesson->student);
	}

	private function createForTeacher(Lesson $lesson, User $teacher)
	{
		$hash = $this->makeHash($teacher->email);
		return LessonEvaluation::create([
			'lesson_id' => $lesson->id,
			'user_id'   => $teacher->id,
			'type'      => EnumRole::STUDENT,
			'status'    => EnumStatus::PENDING,
			'code'      => $hash
		]);
	}

	private function createForStudent(Lesson $lesson, User $student)
	{
		$hash = $this->makeHash($student->email);
		return LessonEvaluation::create([
			'lesson_id' => $lesson->id,
			'user_id'   => $student->id,
			'type'      => EnumRole::TEACHER,
			'status'    => EnumStatus::PENDING,
			'code'      => $hash
		]);
	}

	private function makeHash($phrase)
	{
		$now = Carbon::now();
		return bcrypt($now->format('YmdHis').$phrase);
	}
}
