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
		return $this->item($lesson->student, new UserTransformer);
	}

	public function includePeriods(Lesson $lesson )
	{
		return $this->collection($lesson->periods->all(), new PeriodTransformer);
	}
}