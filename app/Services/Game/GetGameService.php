<?php

namespace App\Services\Game;

use App\Models\Game;

class GetGameService
{
	public function get($id)
	{
		return Game::findOrFail($id);
	}
}