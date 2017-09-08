<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\MonzyPoint;
use App\Models\User;

class Transaction extends Model
{

	use SoftDeletes;

	protected $fillable = [
		'user_id', 'transaction_id', 'monzy_point_id', 'transaction_reference_id', 
		'status', 'type', 'last_event', 'must_update'
	];

	protected $dates = [
		'created_at','updated_at','last_event','deleted_at'
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