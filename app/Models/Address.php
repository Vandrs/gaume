<?php 

namespace App\Models;

use App\Models\User;
use App\Models\State;
use App\Models\City;
use App\Models\Neighborhood;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
	protected $table = 'address';
	
	protected $fillable = [
		'user_id', 'state', 'city', 'neighborhood', 'street', 'number', 'complement', 'zipcode'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}	