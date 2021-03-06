<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestController;
use App\Exceptions\ValidationException;
use App\Services\Registration\CreatePreRegistrationService;
use App\Services\Registration\UpdatePreRegistrationService;
use App\Services\Registration\GetPreRegistrationService;
use App\Services\Registration\ReSendPreRegistrationEmailService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Transformers\ApiItemSerializer;
use App\Transformers\PreRegistrationTransformer;
use App\Transformers\PreRegistrationListTransformer;
use App\Models\PreRegistration;
use Log;
use Lang;
use DB;
use League\Fractal;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class PreRegistrationController extends RestController
{
	public function create(Request $request)
	{
		try {
			DB::beginTransaction();
			$createService = new CreatePreRegistrationService();
			$preRegistration = $createService->create($request->all());
			DB::commit();
			return $this->created($preRegistration->id);
		} catch (ValidationException $e) {
			DB::rollback();
			$errors = $createService->getValidator()->errors()->jsonSerialize();
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
			$getService = new GetPreRegistrationService();
			$preRegistration = $getService->get($id);
			$updateService = new UpdatePreRegistrationService();
			$updateService->update($preRegistration, $request->all());
			DB::commit();
			return $this->success();
		} catch (ValidationException $e) {
			DB::rollback();
			$errors = $updateService->getValidator()->errors()->jsonSerialize();
			Log::error($e->getMessage().': '.json_encode($errors));
			return $this->badRequest($errors);
		} catch (ModelNotFoundException $e) {
			return $this->notFound();
		} catch (\Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function get(Request $request, $id)
	{
		try {
			$getService = new GetPreRegistrationService();
			$preRegistration = $getService->get($id);
			$fractal = new Fractal\Manager();
			$fractal->setSerializer(new ApiItemSerializer);
			$item = new Fractal\Resource\Item($preRegistration, new PreRegistrationTransformer);
			$data = $fractal->createData($item)->toArray(); 
			return $this->success($data);
		} catch (ModelNotFoundException $e) {
			return $this->notFound();
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function getAll(Request $request)
	{
		try {
			$getService = new GetPreRegistrationService();
			$paginator = $getService->getAll($request->all());
			$paginatorAdapter = new IlluminatePaginatorAdapter($paginator);
			$fractal = new Fractal\Manager();
			$items = new Fractal\Resource\Collection($paginator->getCollection(), new PreRegistrationListTransformer);
			$items->setPaginator($paginatorAdapter);
			$data = $fractal->createData($items)->toArray(); 
			return $this->success($data);	
		} catch (ValidationException $e) {
			$errors = $getService->getValidator()->errors()->jsonSerialize();
			Log::error($e->getMessage().': '.json_encode($errors));
			return $this->badRequest($errors);
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function reSendRegistrationEmail(Request $request, $id)
	{
		try {
			$preRegistration = PreRegistration::firstOrFail();
			ReSendPreRegistrationEmailService::send($preRegistration);
			return $this->success(['msg' => Lang::get('pre_registration.send_email_confirmation')]);
		} catch (ModelNotFoundException $e) {
			return $this->notFound();
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}
} 