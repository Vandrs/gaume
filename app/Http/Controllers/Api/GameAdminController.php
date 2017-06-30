<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestController;
use App\Exceptions\ValidationException;
use App\Services\Game\CreateGameService;
use Illuminate\Http\Request;
use Log;

class GameAdminController extends RestController
{
	public function create(Request $request)
	{
		try {
			$service = new CreateGameService();
			$game = $service->create($request->all());
			return $this->created($game->id);
		} catch (ValidationException $e) {
			$errors = $service->getValidator()->errors()->jsonSerialize();
			Log::error($e->getMessage().': '.json_encode($errors));
			return $this->badRequest($errors);
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}
}