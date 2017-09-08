<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MonzyPoint;
use App\Models\User;


class TransactionReference extends Model
{
	protected $fillable = [
		'user_id', 'monzy_point_id', 'code'
	];


	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function monzyPoint()
	{
		return $this->belongsTo(MonzyPoint::class);	
	}
}