<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\MonzyPoint;

class Transaction extends Model
{

	use SoftDeletes;

	protected $fillable = [
		'transaction_id', 'monzy_point_id', 'status', 'type', 'last_event', 'must_update'
	];

	protected $dates = [
		'created_at','updated_at','last_event','deleted_at'
	];
	
}