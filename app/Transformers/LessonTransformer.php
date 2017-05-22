<?php 

namespace App\Transformers;

use League\Fractal;
use App\Models\Lesson;
use App\Transformers\UserTransformer;
use App\Transformers\PeriodTransformer;

class LessonTransformer extends Fractal\TransformerAbstract
{

	protected $availableIncludes = [
		'periods', 'teacher', 'student'
	];

	public function transform(Lesson $lesson)
	{
		return [
			'id' 		 => $lesson->id,
			'status' 	 => $lesson->status,
			'created_at' => $lesson->created_at->__toString()
		];
	}

	public function includeTeacher(Lesson $lesson)
	{
		return $this->item($lesson->teacher, new UserTransformer);
	}

	public function includeStudent(Lesson $lesson)
	{
		if (empty($lesson->student)) {
			$lesson->load('student');
			print_r($lesson->student);
			die;
		}
		return $this->item($lesson->student, new UserTransformer);
	}

	public function includePeriods(Lesson $lesson )
	{
		if ($lesson->periods->count() == 0) {
			$lesson->load('periods');
		}
		return $this->collection($lesson->periods->all(), new PeriodTransformer);
	}
}