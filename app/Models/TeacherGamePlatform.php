<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TeacherGame;
use App\Models\Platform;

class TeacherGamePlatform extends Model
{

	protected $fillable = [
		'platform_id', 'teacher_game_id', 'nickname'
	];

	public function teacherGame()
	{
		return $this->belongsTo(TeacherGame::class);
	}

	public function platform()
	{
		return $this->belongsTo(Platform::class);	
	}

}