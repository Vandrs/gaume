<?php 

namespace App\Services\User;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Storage;
use Config;
use App\Exceptions\ValidationException;
use Carbon\Carbon;
use Validator;

class UserProfilePhotoService 
{

	private $validator;
	private $path;
	private $publicDisk;

	public function __construct()
	{
		$this->path = Config::get('filesystems.user_photo_profile_path');
		$this->publicDisk = Storage::disk('public');
	}

	public function uploadPhoto(User $user, UploadedFile $file)
	{

		$this->validator = Validator::make(['image' => $file], $this->getRules(), $this->getMessages());
		if ($this->validator->fails()) {
			throw new ValidationException();
		}

		$salt = Carbon::now()->format('ymdhis');
		$imgName = $user->id.'-'.$salt.'.'.$file->guessClientExtension();

		$path = $this->publicDisk->putFileAs(
    		$this->path, $file, $imgName
		);

		if ($user->photo_profile) {
			$this->removePhoto($user->photo_profile);
		}

		$user->update(['photo_profile' => $path]);
	}

	public function removePhoto($strFile)
	{
		if ($this->publicDisk->exists($user->photo_profile)) {
			$this->publicDisk->delete($user->photo_profile);
		}
	}

	public function getMessages()
	{
		return ['image.image' => __('validation.image', ['attribute' => __('site.registration.profile_image')])];
	}

	public function getRules()
	{
		return ['image' => 'image'];
	}

	public function getValidator()
	{
		return $this->validator;
	}
}