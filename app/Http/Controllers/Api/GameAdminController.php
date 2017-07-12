<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestController;
use App\Exceptions\ValidationException;
use App\Services\Game\CreateGameService;
use App\Services\Game\UpdateGameService;
use App\Services\Game\GetGameService;
use App\Services\Game\GamePhotoUploadService;
use App\Services\Game\GetAllGameAdminService;
use App\Services\Game\DeleteGameService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Transformers\GameTransformer;
use App\Transformers\ApiItemSerializer;
use App\Transformers\GameAvailableTransformer;
use League\Fractal;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Log;
use DB;

class GameAdminController extends RestController
{
	public function create(Request $request)
	{
		try {
			DB::beginTransaction();
			$service = new CreateGameService();
			$game = $service->create($request->all());
			DB::commit();
			return $this->created($game->id);
		} catch (ValidationException $e) {
			DB::rollback();
			$errors = $service->getValidator()->errors()->jsonSerialize();
			Log::error($e->getMessage().': '.json_encode($errors));
			return $this->badRequest($errors);
		} catch (\Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function update(Request $request, $id)
	{
		try {	
			DB::beginTransaction();
			$getService = new GetGameService();
			$game = $getService->get($id);
			$updateService = new UpdateGameService();
			$updateService->update($game, $request->all());
			DB::commit();
			return $this->success(['msg' => __('games.messages.update_success')]);
		} catch (ModelNotFoundException $e) {
			DB::rollback();
			Log::error($e->getMessage());
			return $this->notFound();
		} catch (ValidationException $e) {
			DB::rollback();
			$errors = $updateService->getValidator()->errors()->jsonSerialize();
			Log::error($e->getMessage().': '.json_encode($errors));
			return $this->badRequest($errors);
		} catch (\Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function updatePhoto(Request $request, $id)
	{
		try {
			$getService = new GetGameService();
			$game = $getService->get($id);
			$photoService = new GamePhotoUploadService();
			$game = $photoService->uploadPhoto($game, $request->file('photo'));
			return $this->success(['url' => $game->getPhotoUrl()]);
		} catch (ModelNotFoundException $e) {
			DB::rollback();
			Log::error($e->getMessage());
			return $this->notFound();
		} catch (ValidationException $e) {
			DB::rollback();
			$errors = $photoService->getValidator()->errors()->jsonSerialize();
			Log::error($e->getMessage().': '.json_encode($errors));
			return $this->badRequest($errors);
		} catch (\Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function get($id)
	{
		try {
			$service = new GetGameService();
			$game = $service->get($id);
			$fractal = new Fractal\Manager();
			$fractal->setSerializer(new ApiItemSerializer);
			$item = new Fractal\Resource\Item($game, new GameTransformer);
			$data = $fractal->createData($item)->toArray(); 
			return $this->success($data);
		} catch (ModelNotFoundException $e) {
			Log::error($e->getMessage());
			return $this->notFound();
		} catch (\Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function getAvailables()
	{
		try {
			$games = GetAllGameAdminService::getAllAvailablesWithPlatform();
			$fractal = new Fractal\Manager();
			$fractal->setSerializer(new ApiItemSerializer);
			$item = new Fractal\Resource\Collection($games, new GameAvailableTransformer);
			$data = $fractal->createData($item)->toArray(); 
			return $this->success($data);
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function delete(Request $request, $id) 
	{
		try {
			$service = new GetGameService();
			$game = $service->get($id);
			$deleteService = new DeleteGameService();
			$deleteService->delete($game);
			return $this->success(['msg' => __('games.messages.delete_success')]);
		} catch (ModelNotFoundException $e) {
			Log::error($e->getMessage());
			return $this->notFound();
		} catch (\Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function list(Request $request)
	{
		try {
			$gamesPaginator = GetAllGameAdminService::getAll($request->all());
			$paginatorAdapter = new IlluminatePaginatorAdapter($gamesPaginator);
			$fractal = new Fractal\Manager();
			$items = new Fractal\Resource\Collection($gamesPaginator->getCollection(), new GameTransformer);
			$items->setPaginator($paginatorAdapter);
			$data = $fractal->createData($items)->toArray(); 
			return $this->success($data);	
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}
}
