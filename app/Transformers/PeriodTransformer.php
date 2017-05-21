<?php 

namespace App\Transformers;

use League\Fractal;
use App\Models\Period;

class PeriodTransformer extends Fractal\TransformerAbstract
{
	public function transform(Period $period)
	{
		return [
			'id' => $period->id,
			'lesson_id' => $period->lesson_id,
			'hours'  => $period->hours,
			'hour_value' => $period->hour_value,
			'status' => $period->status,
			'created_at' => $period->created_at->__toString(),
			'updated_at' => $period->updated_at->__toString()
		];
	}
}