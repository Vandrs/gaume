<?php

namespace App\Services\Game;

use App\Models\Game;
use DB;

use App\Services\Platform\DeleteGamePlatformService;

class DeleteGameService
{
	public function delete(Game $game)
	{
		DeleteGamePlatformService::deleteByGame($game);
		return $game->delete();
	}
}