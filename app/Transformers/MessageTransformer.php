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
			'message' 		    =>   $message->body,
			'truncated_message' => StringUtil::limitaCaracteres(strip_tags($message->body), 50, '...'),
			'user' 	  => [
				'id' 	   => $message->user->id,
				'name'	   => $message->user->name,
				'nickname' => $message->user->nickname,
				'photo'	   => $message->user->getPhotoProfileUrl()
			]
		];
	}
}