<?php 

namespace App\Services\Platform;

use App\Models\Platform;

class GetAllPlatformService
{
	public static function getAll() 
	{
		return Platform::query()
					   ->orderBy('name', 'ASC')
					   ->get();
	}
}