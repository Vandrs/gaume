<?php 

namespace App\Services\User;

use App\Models\User;

class GetUserAdminService
{
	public static function getAll(array $filters)
	{
		$query = User::query()->orderBy('name', 'ASC');
		if (isset($filters['name']) && !empty($filters['name'])) {
			$query->where('name', 'LIKE', '%'.$filters['name'].'%');
		}
		if (isset($filters['nickname']) && !empty($filters['nickname'])) {
			$query->where('nickname', 'LIKE', '%'.$filters['nickname'].'%');
		}
		if (isset($filters['status']) && !empty($filters['status'])) {
			$query->where('status', '=', $filters['status']);
		}
		if (isset($filters['role_id']) && !empty($filters['role_id'])) {
			$query->where('role_id', '=', $filters['role_id']);
		}
		$paginator = $query->paginate(20);
		$queryParams = array_diff_key($filters, array_flip(['page']));
		$paginator->appends($queryParams);
		return $paginator;
	}
}
