<?php 

namespace App\Services\Platform;

use App\Models\GamePlatform;
use App\Models\Game;

class DeleteGamePlatformService
{
	public static function deleteByGame(Game $game) 
	{
		return GamePlatform::query()
						   ->where('game_id', $game->id)
						   ->delete();
	}

	public function delete(GamePlatform $gamePlatform)
	{
		$gamePlatform->delete();
	}
}