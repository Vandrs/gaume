<?php

namespace App\Transformers;

use League\Fractal;
use App\Models\Game;

class GameAvailableTransformer extends Fractal\TransformerAbstract
{
	public function transform(Game $game)
	{
		return [
			'id' => $game->id,
			'name' => $game->name,
			'description' => $game->description,
			'developer_site' => $game->developer_site,
			'status' => $game->status,
			'photo' => $game->getPhotoUrl(),
			'platforms' => $this->parsePlatforms($game)
		];
	}

	private function parsePlatforms(Game $game)
	{
		$gamePlatforms = [];
		$game->gamePlatforms->each(function ($gamePlatform) use (&$gamePlatforms, $game){
			array_push($gamePlatforms, [
				'id'      => $gamePlatform->id,
				'name'    => $gamePlatform->platform->name,
				'code'    => $gamePlatform->platform->code,
				'game_id' => $game->id
			]);
		});
		return $gamePlatforms;
	}
}