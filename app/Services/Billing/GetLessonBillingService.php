<?php 

namespace App\Services\Billing;

use App\Models\Lesson;
use App\Enums\EnumLessonStatus;

class GetLessonBillingService
{
	public static function getAll()
	{
		return Lesson::where('status','=', EnumLessonStatus::FINISHED)
					    ->where(function($query){
							$query->whereNull('value')
						  		  ->orWhere('value','=', 0);
					    })
					    ->get();
	}
}

