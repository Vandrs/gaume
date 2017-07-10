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
		$platforms = [];
		$game->platforms->each(function ($platform) use (&$platforms){
			array_push($platforms, [
				'id'   => $platform->id,
				'name' => $platform->name,
				'code' => $platform->code
			]);
		});
		return $platforms;
	}
}