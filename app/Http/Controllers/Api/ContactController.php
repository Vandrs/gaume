<?php 

namespace App\Http\Controllers\Api;

use Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\RestController;
use App\Exceptions\ValidationException;
use App\Services\Contact\CreateContactService;

class ContactController extends RestController
{
	public function createGuestContact(Request $request)
	{
		try {
			$service = new CreateContactService();
			$contact = $service->create($request->all());
			return $this->success([
				'msg' => __('site.contact_response',['name' => $contact->name])
			]);
		} catch (ValidationException $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->badRequest($service->getValidator()->errors()->all());
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function createContact(Request $request) 
	{

	}
}