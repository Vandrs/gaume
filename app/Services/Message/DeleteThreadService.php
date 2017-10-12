<?php 

namespace App\Services\Message;

use App\Models\User;
use Cmgmyr\Messenger\Models\Thread;

class DeleteThreadService
{
	public function delete(User $user, Thread $thread)
	{	
		$thread->removeParticipant($user->id);
		if ($thread->participants()->count() == 0) {
			$thread->delete();
		}
	}
}