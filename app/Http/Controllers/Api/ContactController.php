<?php 

namespace App\Http\Controllers\Api;

use Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\RestController;
use App\Exceptions\ValidationException;
use App\Services\Contact\CreateContactService;
use App\Services\Contact\GetContactService;
use App\Transformers\ContactTransformer;
use App\Transformers\ApiItemSerializer;
use League\Fractal;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

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
		try {
			$data = $request->all();
			$user = $request->user();
			$data['name'] = $user->name;
			$data['email'] = $user->email;
			$service = new CreateContactService();
			$contact = $service->create($data);
			return $this->success([
				'msg' => __('site.contact_faq_response',['name' => $contact->name])
			]);
		} catch (ValidationException $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->badRequest($service->getValidator()->errors()->jsonSerialize());
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function list(Request $request)
	{
		try {
			$paginator = GetContactService::get($request->all());
			$paginatorAdapter = new IlluminatePaginatorAdapter($paginator);
			$fractal = new Fractal\Manager();
			$items = new Fractal\Resource\Collection($paginator->getCollection(), new ContactTransformer());
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