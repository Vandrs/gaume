<?php

namespace App\Http\Controllers\Api;

use Log;
use Illuminate\Http\Request;
use App\Services\User\UserOnlineService;
use App\Models\User;

class UserOnlineController extends RestController
{
	public function online(Request $request, $id) 
	{
		try {
			$user = User::findOrFail($id);
			UserOnlineService::setOnline($user);
			return $this->success();
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function offline(Request $request, $id)
	{
		try {
			$user = User::findOrFail($id);
			UserOnlineService::setOffline($user);
			return $this->success();
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}
}