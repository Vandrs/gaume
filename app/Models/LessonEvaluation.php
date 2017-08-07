<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Lesson;

class LessonEvaluation extends Model
{
	protected $fillable = [
		'lesson_id', 'user_id', 'type', 'status', 'note', 'comment', 'code'
	];

	public function lesson()
	{
		return $this->belongsTo(Lesson::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}