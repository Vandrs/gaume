<?php

namespace App\Transformers;

use League\Fractal;
use Cmgmyr\Messenger\Models\Thread;
use Cmgmyr\Messenger\Models\Message;
use App\Utils\StringUtil;

class MessageTransformer extends Fractal\TransformerAbstract 
{
	public function transform(Message $message)
	{
		return [
			'id'				  => $message->id,
			'thread_id'			  => $message->thread_id, 
			'message' 		      => $message->body,
			'truncated_message'   => StringUtil::limitaCaracteres(strip_tags($message->body), 50, '...'),
			'created_at_human'    => $message->created_at->diffForHumans(),
			'created_at_formated' => $message->created_at->format('d/m/Y H:i'),
			'created_at'  => $message->created_at->__toString(),
			'user' 	  => [
				'id' 	   => $message->user->id,
				'name'	   => $message->user->name,
				'nickname' => $message->user->nickname,
				'photo'	   => $message->user->getPhotoProfileUrl()
			]
		];
	}
}