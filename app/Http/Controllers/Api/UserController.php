<?php 

namespace App\Http\Controllers\Api;

use DB;
use Log;
use League\Fractal;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\RestController;
use App\Services\User\GetUserService;
use App\Services\User\UpdateUserProfileService;
use App\Services\User\UserProfilePhotoService;
use App\Transformers\ApiItemSerializer;
use App\Transformers\UserProfileTransformer;
use App\Exceptions\ValidationException;

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
		try {
			DB::beginTransaction();
			$userService = new UpdateUserProfileService();
			$userService->update($request->user(), $request->all());
			DB::commit();
			return $this->success(['msg' => __('app.messages.profileUpdateSuccess')]);
		} catch (ValidationException $e) {
			DB::rollback();
			$errors = $userService->getValidator()->errors()->jsonSerialize();
			Log::error($e->getMessage().': '.json_encode($errors));
			return $this->badRequest($errors);
		} catch (\Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function updatePhoto(Request $request)
	{
		try {
			DB::beginTransaction();
			$userService = new UserProfilePhotoService();
			$user = $userService->uploadPhoto($request->user(), $request->file('photo_profile'));
			DB::commit();
			return $this->success(['url' => $user->getPhotoProfileUrl()]);
		} catch (ValidationException $e) {
			DB::rollback();
			$errors = $userService->getValidator()->errors()->jsonSerialize();
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