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
			$this->deleteGamePlatforms($game, $platforms);
			$this->removeIfExists($game, $platforms);
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

	private function removeIfExists(Game $game, array &$ids)
	{
		$gamePlatforms = GamePlatform::query()
									 ->where('game_id', $game->id)
									 ->get();
		foreach ($ids as $idx => $id) {
			$exists = $gamePlatforms->contains(function($gamePlatform) use ($id){
				return $gamePlatform->platform_id == $id;
			});
			if ($exists) {
				unset($ids[$idx]);
			}
		}
	}

	private function deleteGamePlatforms(Game $game, array $excludeIds = null) 
	{
		$query = GamePlatform::query()
		  		   			 ->where('game_id', '=', $game->id);
		if (!empty($excludeIds)) {
			$query->whereNotIn('platform_id', $excludeIds);
		}
		$query->delete();
	}

}