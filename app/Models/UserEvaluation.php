<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserEvaluation extends Model
{
	protected $table = 'user_evaluation';

	protected $fillable = [
		'user_id','note','qtd_evaluations'
	];

	public function user() 
	{
		return $this->belongsTo(User::class);
	}
}