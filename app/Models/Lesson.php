<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Period;
use App\Models\Game;
use App\Models\LessonEvaluation;

class Lesson extends Model 
{
	protected $hidden = [
		'finished_at'
	];

	protected $fillable = [
		'teacher_id', 'student_id', 'status', 'game_id', 'platform_id'
	];

	protected $dates = [
		'created_at', 'updated_at', 'finished_at'
	];

	public function teacher()
	{
		return $this->belongsTo(User::class, 'teacher_id', 'id');
	}

	public function student()
	{
		return $this->belongsTo(User::class, 'student_id', 'id');	
	}

	public function game()
	{
		return $this->belongsTo(Game::class);
	}

	public function periods()
	{
		return $this->hasMany(Period::class)->orderBy('created_at','ASC');
	}

	public function evaluations()
	{
		return $this->hasMany(LessonEvaluation::class);
	}
}