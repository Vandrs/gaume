<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestController;
use App\Services\Game\GetGameService;
use App\Transformers\ApiItemSerializer;
use App\Transformers\GameTransformer;
use League\Fractal;
use Log;

class GameController extends RestController
{
	public function list()
	{
		try {
			$games = GetGameService::getHomeGames();
			$manager = new Fractal\Manager();
			$manager->setSerializer(new ApiItemSerializer);
			$items = new Fractal\Resource\Collection($games->all(), new GameTransformer);
			$data = $manager->createData($items)->toArray(); 
			return $this->success($data);
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}
}