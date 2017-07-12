<?php 

namespace App\Services\Game;

use App\Models\Game;
use App\Enums\EnumActiveInactive;

class GetAllGameAdminService
{
	public static function getAll($filters)
	{
		$query = Game::query()->orderBy('name', 'asc');
		if (isset($filters['name']) && !empty($filters['name'])) {
			$query->where('name','LIKE','%'.$filters['name'].'%');
		}
		if (isset($filters['status']) && is_numeric($filters['status'])) {
			$query->where('status', $filters['status']);
		}
		$paginator = $query->paginate(10);
		$queryParams = array_diff_key($filters, array_flip(['page']));
		$paginator->appends($queryParams);
		return $paginator;
	}

	public static function getAllAvailablesWithPlatform()
	{
		$games = Game::with(['gamePlatforms.platform'])
					 ->where('status', EnumActiveInactive::ACTIVE)
					 ->get();
		return $games->filter(function($game) {
			return $game->platforms->count() > 0;
		});
	}

	public static function getAllAvailables()
	{
		$games = Game::with(['platforms'])
					 ->where('status', EnumActiveInactive::ACTIVE)
					 ->get();
		return $games->filter(function($game) {
			return $game->platforms->count() > 0;
		});
	}
}