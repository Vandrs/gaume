<?php 

namespace App\Transformers;

use League\Fractal;
use App\Models\User;
use App\Enums\EnumLessonStatus;
use App\Enums\EnumRole;


class LessonListTransformer extends Fractal\TransformerAbstract
{

	private $user;

	public function __construct(User $user)
	{
		$this->user = $user;
	}


	public function transform($lesson)
	{
		$value = $this->getValue($lesson);
		return [
			'id' 		 	 => $lesson->id,
			'status' 	 	 => $lesson->status,
			'value'		 	 => $value,
			'formated_value' => "R$".number_format($value, 2, ',', '.'),
			'created_at' 	 => $lesson->created_at,
			'teacher'    	 => $this->parseTeacher($lesson),
			'student'    	 => $this->parseStudent($lesson),
			'periods'    	 => $this->parsePeriods($lesson->periods)
		];
	}

	private function parseTeacher($lesson)
	{
		return [
			'id'    => $lesson->teacher_id,
			'name'  => $lesson->teacher_name,
			'email' => $lesson->teacher_email
		];
	}

	private function parseStudent($lesson)
	{
		return [
			'id'    => $lesson->student_id,
			'name'  => $lesson->student_name,
			'email' => $lesson->student_email
		];
	}

	private function parsePeriods($periods)
	{
		$data = [];
		foreach ($periods as $period) {
			array_push($data, [
				"id" 		  => $period->id,
                "lesson_id"   => $period->lesson_id,
                "hours" 	  => $period->hours,
                "hour_value"  => $period->hour_value,
                "points" 	  => $period->points,
                "status" 	  => $period->status,
                "finished_at" => $period->finished_at,
                "created_at"  => $period->created_at,
                "updated_at"  => $period->updated_at
			]);
		}
		return $data;
	}

	public function getValue($lesson)
	{
		if ($lesson->status == EnumLessonStatus::FINISHED && ($this->user->hasRole(EnumRole::ADMIN) || $this->user->hasRole(EnumRole::TEACHER))) {
			return $lesson->value;
		} else {
			return 0;
		}
	}
}