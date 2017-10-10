<?php 

namespace App\Services\Message;

use App\Models\User;
use Cmgmyr\Messenger\Models\Thread;
use App\Enums\EnumModeThreads;

class GetThreadMessageService
{

	private $validator;

	public static function getList(User $user, $data = [])
	{	
		if (!isset($data['mode']) || empty($data['mode'])) {
			$threadsQuery = Thread::forUser($user->id)->latest('updated_at');
		} else if ($data['mode'] == EnumModeThreads::ALL) {
        	$threadsQuery = Thread::forUser($user->id)->latest('updated_at');
		} else if ($data['mode'] == EnumModeThreads::UNREAD) {
			$threadsQuery = Thread::forUserWithNewMessages($user->id)->latest('updated_at');
		}
		$paginator = $threadsQuery->paginate(20);
		$queryParams = array_diff_key($data, array_flip(['page']));
		$paginator->appends($queryParams);
		return $paginator;
	}

}