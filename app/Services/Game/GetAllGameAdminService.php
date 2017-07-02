<?php 

namespace App\Services\Game;

use App\Models\Game;

class GetAllGameAdminService
{
	public function getAll($filters)
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
}