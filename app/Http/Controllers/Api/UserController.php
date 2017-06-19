<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestController;
use Illuminate\Http\Request;
use App\Services\User\GetUserService;
use League\Fractal;
use App\Transformers\ApiItemSerializer;
use App\Transformers\UserProfileTransformer;

class UserController extends RestController
{
	public function getMe(Request $request)
	{
		$user = GetUserService::get($request->user()->id);
		$fractal = new Fractal\Manager();
		$fractal->setSerializer(new ApiItemSerializer);
		$item = new Fractal\Resource\Item($user, new UserProfileTransformer);
		$data = $fractal->createData($item)->toArray(); 
		return $this->success($data);
	}

	public function update(Request $request) 
	{
		
	}

}