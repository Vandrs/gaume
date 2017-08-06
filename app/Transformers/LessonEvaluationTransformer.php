<?php 

namespace App\Transformers;

use App\Models\LessonEvaluation;
use League\Fractal;

class LessonEvaluationTransformer extends Fractal\TransformerAbstract
{
	public function transform(LessonEvaluation $evaluation)
	{
		return [
			'id' => $evaluation->id,
			'lesson_id' => $evaluation->lesson_id,
			'user_id' => $evaluation->user_id,
			'type' => $evaluation->type,
			'status' => $evaluation->status,
			'note' => $evaluation->note,
			'comment' => $evaluation->comment
		];
	}
}