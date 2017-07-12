<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestController;
use App\Exceptions\ValidationException;
use App\Services\Registration\CreatePreRegistrationService;
use Illuminate\Http\Request;
use Log;
use DB;

class PreRegistrationController extends RestController
{
	public function create(Request $request)
	{
		try {
			DB::beginTransaction();
			$createService = new CreatePreRegistrationService();
			$preRegistration = $createService->create($request->all());
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
} 