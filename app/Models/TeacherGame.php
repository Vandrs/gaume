<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Game;
use App\Models\TeacherGamePlatform;

class TeacherGame extends Model
{

	protected $table = 'teacher_game';

	protected $fillable = [
		'teacher_id', 'game_id', 'description', 'status'
	];

	public function teacher()
	{
		return $this->belongsTo(User::class, 'teacher_id');
	}

	public function game()
	{
		return $this->belongsTo(Game::class);
	}

	public function teacherGamePlatforms()
	{
		return $this->hasMany(TeacherGamePlatform::class);
	}
}