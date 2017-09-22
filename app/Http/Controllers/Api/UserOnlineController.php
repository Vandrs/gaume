<?php

namespace App\Http\Controllers\Api;

use Log;
use Illuminate\Http\Request;
use App\Services\User\UserOnlineService;

class UserOnlineController extends RestController
{
	public function online(Request $request) 
	{
		try {
			UserOnlineService::setOnline($request->user());
			return $this->success();
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function offline(Request $request)
	{
		try {
			UserOnlineService::setOffline($request->user());
			return $this->success();
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}
}