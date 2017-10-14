<?php

namespace App\Transformers;

use League\Fractal;
use App\Models\User;
use Cmgmyr\Messenger\Models\Thread;
use App\Utils\StringUtil;

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
			'id' 		      => $thread->id,
			'subject' 	      => $thread->subject,
			'updated_at'      => $thread->updated_at->__toString(),
			'updated_at_text' => $thread->updated_at->format('d/m/Y H:i'),
			'is_read'         => !$thread->isUnread($this->user->id),
			'last_message'    => $this->parseLastMessage($thread),
			'participants'    => $this->parseParticipantsString($thread),
			'recipients'	  => $this->parseParticipantsIds($thread),
			'contact'  	  	  => $this->getContact($thread),
			'selected'		  => false
		];
	}

	private function parseLastMessage(Thread $thread)
	{
		$lastMessage = $thread->latestMessage;
		return [
			'id'				=> $lastMessage->id,
			'thread_id'			=> $lastMessage->thread_id, 
			'message' 		    => $lastMessage->body,
			'truncated_message' => StringUtil::limitaCaracteres(strip_tags($lastMessage->body), 50, '...'),
			'user' 	  => [
				'id' 	   => $lastMessage->user->id,
				'name'	   => $lastMessage->user->name,
				'nickname' => $lastMessage->user->nickname,
				'photo'	   => $lastMessage->user->getPhotoProfileUrl()
			]
		];
	}

	private function parseParticipantsString(Thread $thread)
	{
		$participants = explode(',', $thread->participantsString($this->user->id));
		$users = [];
		foreach ($participants as $participant) {
			$nameParts = explode(" ", $participant);
			array_push($users, $nameParts[0]);
		}
		return implode(',', $users);
	}

	private function getContact(Thread $thread)
	{
		$users = $thread->users;
		$user = $users->filter(function($user) {
			return $user->id != $this->user->id;
		})->first();
		return [
			'text'  => $user->nickname,
			'photo' => $user->getPhotoProfileUrl()
		];
	}

	private function parseParticipantsIds($thread)
	{
		$users = $thread->users;
		$ids = $users->filter(function($user) {
			return $user->id != $this->user->id;
		})->pluck('id');
		return $ids;
	}
}