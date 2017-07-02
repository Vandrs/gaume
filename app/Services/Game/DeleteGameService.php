<?php

namespace App\Services\Game;

use App\Models\Game;

class DeleteGameService
{
	public function delete(Game $game)
	{
		return $game->delete();
	}
}