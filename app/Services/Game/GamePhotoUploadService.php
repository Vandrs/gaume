<?php 

namespace App\Services\game;

use App\Models\Game;
use Illuminate\Http\UploadedFile;
use Storage;
use Config;
use App\Exceptions\ValidationException;
use Carbon\Carbon;
use Validator;

class GamePhotoUploadService 
{

	private $validator;
	private $path;
	private $publicDisk;

	public function __construct()
	{
		$this->path = Config::get('filesystems.game_photo_path');
		$this->publicDisk = Storage::disk('public');
	}

	public function uploadPhoto(Game $game, UploadedFile $file = null)
	{

		$this->validator = Validator::make(['photo' => $file], $this->getRules(), $this->getMessages());
		if ($this->validator->fails()) {
			throw new ValidationException();
		}

		$salt = Carbon::now()->format('ymdhis');
		$imgName = $game->id.'-'.$salt.'.'.$file->guessClientExtension();

		$path = $this->publicDisk->putFileAs(
    		$this->path, $file, $imgName
		);

		if ($game->photo) {
			$this->removePhoto($game->photo);
		}

		$game->update(['photo' => $path]);
		return $game->fresh();
	}

	public function removePhoto($strFile)
	{
		if ($this->publicDisk->exists($strFile)) {
			$this->publicDisk->delete($strFile);
		}
	}

	public function getMessages()
	{
		return [
			'photo.required' => __('validation.required', ['attribute' => 'Foto de Capa']),
			'photo.image' => __('validation.image', ['attribute' => 'Foto de Capa']),
			'photo.dimension_min' => __('validation.dimension_min',['attribute' => 'Foto de Capa', 'width' => 700, 'height' => 300])
		];
	}

	public function getRules()
	{
		return [
			'photo' => 'required|image|dimension_min:700,300'
		];
	}

	public function getValidator()
	{
		return $this->validator;
	}
}