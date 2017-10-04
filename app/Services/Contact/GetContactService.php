<?php 

namespace App\Services\Contact;

use App\Models\Contact;

class GetContactService 
{
	public static function get($data, $size = 20)
	{
		$query = Contact::query()->orderBy('name','ASC');
		if (isset($data['name']) && !empty($data['name'])) {
			$query->where('name', 'LIKE', '%'.$data['name'].'%');
		}
		if (isset($data['type']) && !empty($data['type'])) {
			$query->where('type', '=', $data['type']);
		}
		if (isset($data['status']) && is_numeric($data['status'])) {
			$query->where('status', '=', $data['status']);
		}
		$paginator = $query->paginate($size);
		$queryParams = array_diff_key($data, array_flip(['page']));
		$paginator->appends($queryParams);
		return $paginator;
	}
}