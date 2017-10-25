<?php 

namespace App\Services\User;

use App\Models\User;
use App\Models\UserMedia;
use App\Exceptions\ValidationException;
use App\Services\User\SaveUserMediaService;
use Validator;

class UpdateUserMediaService
{
	private $validator;

	public function update(User $user, $data)
	{
		$data = $this->buildData($data);
		$this->validator = Validator::make($data, $this->getRules(), $this->getMessages());
		if ($this->validator->fails()) {
			throw new ValidationException();
		}
		if ($this->shouldDeleteData($data)) {
			if ($user->media) {
				$user->media->delete();
			}
		} else {
			$service = new SaveUserMediaService();
			$service->save($user, $data['media_type'], $data['media_user']);
		}
	}

	private function shouldDeleteData($data)
	{
		return empty($data['media_user']) && empty($data['media_type']);
	}

	public function getRules()
	{
		return [
            'media_type' => 'required_with:media_user',
            'media_user' => 'required_with:media_type' 
		];
	}

	public function getMessages()
	{
		return [
			'media_type.required_with'	      => __('validation.required', ['attribute' => __('site.registration.media_type')]),
			'media_user.required_with'	      => __('validation.required', ['attribute' => __('site.registration.media_user')])
		];
	}

	public function getValidator()
	{
		return $this->validator;
	}

	private function buildData($data)
	{	
		return [
			'media_user' => $data['nickname'] ? $data['nickname'] : null,
			'media_type' => $data['media'] ? $data['media'] : null
		];
	}
}
