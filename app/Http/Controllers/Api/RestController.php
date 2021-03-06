<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Lang;
use Symfony\Component\HttpFoundation\Response;

class RestController extends Controller
{
	private function getResponse(int $status)
	{
		return response()->json(null, $status);
	}	    

	public function internalError()
	{
		$response = $this->getResponse(Response::HTTP_INTERNAL_SERVER_ERROR);
		$response->setData(['msg' => Lang::get('rest.responses.internal_error')]);
		return $response;
	}

	public function unauthorized($msg = null)
	{
		$response = $this->getResponse(Response::HTTP_UNAUTHORIZED);
		$response->setData(['msg' => empty($msg) ? Lang::get('rest.responses.unauthorized') : $msg]);
		return $response;
	}

	public function forbidden()
	{
		$response = $this->getResponse(Response::HTTP_FORBIDDEN);
		$response->setData(['msg' => Lang::get('rest.responses.forbidden')]);
		return $response;
	}

	public function notFound()
	{
		$response = $this->getResponse(Response::HTTP_NOT_FOUND);
		$response->setData(['msg' => Lang::get('rest.responses.not_found')]);
		return $response;
	}

	public function success($data = null)
	{
		if (empty($data)) {
			$response = $this->getResponse(Response::HTTP_NO_CONTENT);
		} else {
			$response = $this->getResponse(Response::HTTP_OK);
			$response->setData($data);
		}
		return $response;
	}

	public function created(int $id)
	{
		$response = $this->getResponse(Response::HTTP_CREATED);
		$response->setData(['id' => $id]);
		return $response;
	}

	public function badRequest($data = [])
	{
		$response = $this->getResponse(Response::HTTP_BAD_REQUEST);
		$response->setData(['errors' => $data]);
		return $response;
	}


}
