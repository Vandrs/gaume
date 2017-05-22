<?php 

namespace App\Transformers;

use League\Fractal;

class LessonListTransformer extends Fractal\TransformerAbstract
{

	public function transform($lesson)
	{
		return [
			'id' 		 => $lesson->id,
			'status' 	 => $lesson->status,
			'created_at' => $lesson->created_at,
			'teacher'    => $this->parseTeacher($lesson),
			'student'    => $this->parseStudent($lesson),
			'periods'    => $this->parsePeriods($lesson->periods)
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
				"id" => $period->id,
                "lesson_id" => $period->lesson_id,
                "hours" => $period->hours,
                "hour_value" => $period->hour_value,
                "status" => $period->status,
                "billed" => $period->billed,
                "finished_at" => $period->finished_at,
                "created_at" => $period->created_at,
                "updated_at" => $period->updated_at
			]);
		}
		return $data;
	}
}