<?php

namespace App\Http\Controllers\Api;

use Log;
use DB;
use App\Http\Controllers\Api\RestController;
use Illuminate\Http\Request;
use App\Exceptions\ValidationException;
use App\Services\Message\CreateThreadMessageService;
use App\Services\Message\GetThreadMessageService;
use App\Services\Message\DeleteThreadService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use League\Fractal;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\MessageThreadTransformer;
use Cmgmyr\Messenger\Models\Thread;

class MessageController extends RestController
{

	public function createThread(Request $request)
	{
		DB::beginTransaction();
		try {
			$threadService =  new CreateThreadMessageService();
			$threadService->create($request->user(), $request->all());
			DB::commit();
			return $this->success(['msg' => __('messages.message_created')]);
		} catch (ValidationException $e) {
			DB::rollback();
			$errors = $threadService->getValidator()->errors()->all();
			Log::error($e->getMessage().': '.json_encode($errors));
			return $this->badRequest($errors);
		} catch (\Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}	

	public function getThreads(Request $request)
	{
		try{
			$user = $request->user();
			$paginator = GetThreadMessageService::getList($user, $request->all());
			$paginatorAdapter = new IlluminatePaginatorAdapter($paginator);
			$fractal = new Fractal\Manager();
			$items = new Fractal\Resource\Collection($paginator->getCollection(), new MessageThreadTransformer($user));
			$items->setPaginator($paginatorAdapter);
			$data = $fractal->createData($items)->toArray(); 
			return $this->success($data);
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function getThread(Request $request, $id)
	{

	}

	public function delete(Request $request, $id)
	{
		try {
			$thread = Thread::findOrFail($id);
			$deleteService = new DeleteThreadService();
			$deleteService->delete($request->user(), $thread);
		} catch (ModelNotFoundException $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->notFound();
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
		
	}
}