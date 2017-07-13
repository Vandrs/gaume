<?php 

namespace App\Transformers;

use League\Fractal;
use App\Models\PreRegistration;

class PreRegistrationTransformer extends Fractal\TransformerAbstract
{
	public function transform(PreRegistration $preRegistration)
	{
		return [
			'id' 		    => $preRegistration->id,
			'name' 		    => $preRegistration->name,
			'email' 	    => $preRegistration->email,
			'gamePlatforms' => $this->parseGamePlatforms($preRegistration),
			'mailed_at'     => $preRegistration->mailed_at ? $preRegistration->mailed_at->__toString() : null, 
			'created_at'    => $preRegistration->created_at->__toString(),
			'updated_at'    => $preRegistration->updated_at->__toString()
		];
	}

	private function parseGamePlatforms(PreRegistration $preRegistration) 
	{
		$gamePlatofrms = [];
		$preRegistration->preRegistrationPlatforms->each(function($preRegistrationPlatform) use (&$gamePlatofrms) {
			array_push($gamePlatofrms, $preRegistrationPlatform->game_platform_id);
		});	
		return $gamePlatofrms;
	}
};