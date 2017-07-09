<?php 

namespace App\Services\Platform;

use App\Services\Service;

use App\Models\Platform;
use App\Models\GamePlatform;
use App\Models\Game;
use App\Exceptions\ValidationException;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use DB;
use Validator;
use Log;

class SaveGamePlatformService extends Service
{

	public static function registerPolicies() {}


	public function save(Game $game, array $platforms) 
	{
		$this->validator = Validator::make([],[],[]);
		if ($game->exists) {
			$this->deleteGamePlatforms($game);
		}
		$gamePlatforms = [];
		foreach ($platforms as $idPlatform) {
			$platform = $this->findPlatform($idPlatform);
			$gamePlatform = $this->createGamePlatform($game, $platform);
			array_push($gamePlatforms, $gamePlatform);
		}
		return Collection::make($gamePlatforms);
	}

	private function createGamePlatform(Game $game, Platform $platform)
	{
		return GamePlatform::create([
			'game_id' 	  => $game->id,
			'platform_id' => $platform->id
		]);
	}

	private function findPlatform($id) 
	{
		try {
			return Platform::findOrfail($id);
		} catch (ModelNotFoundException $e) {
			$msg = __('games.massages.invalid_platform');
			$this->validator->errors()->add('platforms', $msg);
			throw new ValidationException($msg);
		}
	}

	private function deleteGamePlatforms(Game $game) 
	{
		DB::table('game_platform')
		  ->where('game_id', '=', $game->id)
		  ->delete();
	}

}