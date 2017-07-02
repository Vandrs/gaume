<?php

namespace App\Transformers;

use League\Fractal;
use App\Models\Game;

class GameTransformer extends Fractal\TransformerAbstract
{
	public function transform(Game $game)
	{
		return [
			'id' => $game->id,
			'name' => $game->name,
			'description' => $game->description,
			'developer_site' => $game->developer_site,
			'status' => $game->status,
			'photo' => $game->getPhotoUrl()
		];
	}
}