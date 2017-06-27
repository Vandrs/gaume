<?php 

namespace App\Services\Location;

use App\Models\State;

class GetStateService 
{
	public static function getAll()
	{
		return State::query()->orderBy('name','ASC')->get();
	}
}