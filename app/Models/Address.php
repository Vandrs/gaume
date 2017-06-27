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
		'user_id', 'state_id', 'city_id', 'neighborhood_id', 'street', 'number', 'complement'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function state()
	{
		return $this->belongsTo(State::class);
	}

	public function city()
	{
		return $this->belongsTo(City::class);
	}

	public function neighborhood()
	{
		return $this->belongsTo(Neighborhood::class);
	}
}	