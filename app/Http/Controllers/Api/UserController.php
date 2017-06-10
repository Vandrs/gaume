<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestController;
use Illuminate\Http\Request;
use App\Services\User\GetUserService;

class UserController extends RestController
{
	public function getMe(Request $request)
	{
		$user = GetUserService::get($request->user()->id);
		return $this->success($user->toArray());
	}

}