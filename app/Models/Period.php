<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lesson;

class Period extends Model 
{
	protected $hidden = [
		'finished_at'
	];

	protected $fillable = [
		'lesson_id', 'hours', 'hour_value', 'status', 'billed'
	];

	protected $dates = [
		'created_at', 'updated_at', 'finished_at'
	];

	public function lesson()
	{
		return $this->belongsTo(Lesson::class);
	}
}