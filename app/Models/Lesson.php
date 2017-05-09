<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Period;

class Lesson extends Model 
{
	protected $hidden = [
		'finished_at'
	];

	protected $dates = [
		'created_at', 'updated_at', 'finished_at'
	];

	public function teacher()
	{
		return $this->belongsTo(User::class, 'id', 'teacher_id');
	}

	public function student()
	{
		return $this->belongsTo(User::class, 'id', 'student_id');	
	}

	public function periods()
	{
		return $this->hasMany(Period::class)->orderBy('created_at','ASC');
	}
}