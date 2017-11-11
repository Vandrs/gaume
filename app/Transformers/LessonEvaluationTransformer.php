<?php 

namespace App\Transformers;

use App\Models\LessonEvaluation;
use League\Fractal;

class LessonEvaluationTransformer extends Fractal\TransformerAbstract
{
	public function transform(LessonEvaluation $evaluation)
	{
		return [
			'id' 		=> $evaluation->id,
			'lesson_id' => $evaluation->lesson_id,
			'user_id'   => $evaluation->user_id,
			'user_name' => $evaluation->user->name,
			'type'      => $evaluation->type,
			'status'    => $evaluation->status,
			'note' 	    => $evaluation->note,
			'comment'   => $evaluation->comment,
			'lesson'    => $this->parseLesson($evaluation)
		];
	}

	private function parseLesson(LessonEvaluation $evaluation)
	{
		return [
			'teacher' 	 => $evaluation->lesson->teacher->name,
			'student' 	 => $evaluation->lesson->student->name,
			'game' 	  	 => $evaluation->lesson->game->name,
			'created_at' => $evaluation->lesson->created_at->__toString()	
		];
	}
}