<?php 

namespace App\Services\User;

use App\Models\User;
use App\Models\UserMedia;

class SaveUserMediaService
{
	public function save(User $user, $media, $nickname)
	{
		$userMedia = UserMedia::firstOrNew([
			'user_id' => $user->id
		]);
		$userMedia->nickname = $nickname;
		$userMedia->media = $media;
		$userMedia->save();
		return $userMedia;
	}
}
