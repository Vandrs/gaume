<?php 

namespace App\Services\Lesson;

use DB;
use App\Models\Lesson;
use App\Models\
use Carbon\Carbon;

class EndLessonService
{
	public function mustEndLesson(Lesson $lesson)
	{
		$exists = DB::table('periods')
		  			->where('lesson_id','=',$lesson->id)
		  			->whereIn('status',[EnumLessonStatus::PENDING, EnumLessonStatus::IN_PROGRESS])
		  			->exists();
		return !$exists;
	}

	public function endLesson(Lesson $lesson)
	{
		$existsFinishedPeriod = DB::table('periods')
		  						  ->where('lesson_id','=',$lesson->id)
		  				    	  ->where('status','=',EnumLessonStatus::FINISHED)
		  					      ->exists();
		$finishedAt = Carbon::now();
		$status = $existsFinishedPeriod ? EnumLessonStatus::FINISHED : EnumLessonStatus::CANCELED;
		$lesson->finished_at = $finishedAt;
		$lesson->status = $status;
		return $lesson->save();
	}
}