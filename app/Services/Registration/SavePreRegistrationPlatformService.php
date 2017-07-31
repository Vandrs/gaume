<?php 

namespace App\Services\Registration;

use App\Services\Service;
use App\Models\PreRegistration;
use App\Models\GamePlatform;
use App\Models\PreRegistrationPlatform;
use App\Exceptions\ValidationException;
use Illuminate\Support\Collection;
use Validator;

class SavePreRegistrationPlatformService
{
	public static function registerPolicies() {}

	public function save(PreRegistration $preRegistration, array $gamePlatforms)
	{
		$this->validator = Validator::make([],[],[]);
		if ($preRegistration->exists) {
			$this->deletePreRegistrationPlatforms($preRegistration, $gamePlatforms);
			$this->removeIfExists($preRegistration, $gamePlatforms);
		}
		$preRegistrationPlatforms = [];
		foreach ($gamePlatforms as $id) {
			$gamePlatform = $this->findGamePlatform($id);
			$preRegistrationPlatform = $this->createPreRegistrationPlatform($preRegistration, $gamePlatform);
			array_push($preRegistrationPlatforms, $preRegistrationPlatform);
		}	
		return Collection::make($preRegistrationPlatforms);
	}

	private function createPreRegistrationPlatform(PreRegistration $preRegistration, GamePlatform $gamePlatform)
	{
		return PreRegistrationPlatform::create([
			'pre_registration_id' => $preRegistration->id,
			'game_platform_id' 	  => $gamePlatform->id
		]);
	}

	private function findGamePlatform($id) 
	{
		try {
			return GamePlatform::findOrfail($id);
		} catch (ModelNotFoundException $e) {
			$msg = __('games.massages.invalid_platform');
			$this->validator->errors()->add('platforms', $msg);
			throw new ValidationException($msg);
		}
	}

	private function removeIfExists(PreRegistration $preRegistration, array &$ids)
	{
		$registereds = PreRegistrationPlatform::query()
											  ->where('pre_registration_id', $preRegistration->id)
											  ->get();	
		foreach ($ids as $idx => $id) {
			$exists = $registereds->contains(function($preRegistrationPlatform) use ($id){
				return $preRegistrationPlatform->game_platform_id == $id;
			});
			if ($exists) {
				unset($ids[$idx]);
			}
		}
	}

	private function deletePreRegistrationPlatforms(PreRegistration $preRegistration, array $excludeIds = null) 
	{
		$query = PreRegistrationPlatform::query()
		   		   						->where('pre_registration_id', $preRegistration->id);
		if (!empty($excludeIds)) {
			$query->whereNotIn('game_platform_id', $excludeIds);
		}
	}
}