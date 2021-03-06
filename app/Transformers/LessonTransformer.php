<?php 

namespace App\Transformers;

use League\Fractal;
use App\Models\Lesson;
use App\Transformers\UserTransformer;
use App\Transformers\PeriodTransformer;
use App\Transformers\LessonEvaluationTransformer;

class LessonTransformer extends Fractal\TransformerAbstract
{

	protected $availableIncludes = [
		'periods', 'teacher', 'student', 'evaluations'
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
		}
		return $this->item($lesson->student, new UserTransformer);
	}

	public function includePeriods(Lesson $lesson)
	{
		if ($lesson->periods->count() == 0) {
			$lesson->load('periods');
		}
		return $this->collection($lesson->periods->all(), new PeriodTransformer);
	}

	public function includeEvaluations(Lesson $lesson)
	{
		return $this->collection($lesson->evaluations->all(), new LessonEvaluationTransformer);	
	}
}