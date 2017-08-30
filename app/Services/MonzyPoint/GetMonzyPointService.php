<?php

namespace App\Services\MonzyPoint;

use App\Models\MonzyPoint;

class GetMonzyPointService
{
	public static function getAll()
	{
		return MonzyPoint::query()->orderBy('value','ASC')->get();
	}
}