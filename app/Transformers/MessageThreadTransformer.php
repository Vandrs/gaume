<?php

namespace App\Transformers;

use League\Fractal;
use App\Models\User;
use Cmgmyr\Messenger\Models\Thread;

class MessageThreadTransformer extends Fractal\TransformerAbstract 
{

	private $user;

	public function __construct(User $user)
	{
		$this->user = $user;
	}

	public function transform(Thread $thread)
	{
		return [
			'id' 		   => $thread->id,
			'subject' 	   => $thread->subject,
			'updated_at'   => $thread->updated_at->__toString(),
			'is_read'      => !$thread->isUnread($this->user->id),
			'last_message' => $this->parseLastMessage($thread)
		];
	}

	private function parseLastMessage(Thread $thread)
	{
		$lastMessage = $thread->latestMessage;

		return [
			'message' =>   $lastMessage->body,
			'user' 	  => [
				'id' 	   => $lastMessage->user->id,
				'name'	   => $lastMessage->user->name,
				'nickname' => $lastMessage->user->nickname
			]
		];
	}
}