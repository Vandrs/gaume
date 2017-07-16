<?php 

namespace App\Services\Game;

use App\Services\Service;
use App\Services\Game\GamePhotoUploadService;
use App\Services\Platform\SaveGamePlatformService;
use App\Models\Game;
use App\Exceptions\ValidationException;
use Validator;

class CreateGameService extends Service
{

	public static function registerPolicies() {}

	public function create(array $data)
	{
		$this->validator = Validator::make($data, $this->getRules(), $this->getMessages());
		if ($this->validator->fails()) {
			throw new ValidationException();
		}
		$game = Game::create([
			'name' 			 => $data["name"],
			'description' 	 => $data["description"],
			'photo' 		 => "CUSTOM",
			'developer_site' => str_replace(["http://","https://"], "", $data["developer_site"]),
			'status' 		 => $data["status"]
		]);
		try {
			$photoUploadService = new GamePhotoUploadService();
			$photo = isset($data['photo']) ? $data['photo'] : null;
			$game = $photoUploadService->uploadPhoto($game, $photo);
			$gamePlatformService = new SaveGamePlatformService();
			$gamePlatformService->save($game, $data['platforms']);
			return $game;
		} catch (ValidationException $e) {
			$this->validator->messages()->merge($photoUploadService->getValidator()->messages());
			if (isset($gamePlatformService)) {
				$this->validator->messages()->merge($gamePlatformService->getValidator()->messages());	
			}
			throw $e;
		}
	}

	public function getRules()
	{
		return [
			'name' => 'required|max:100',
			'description' => 'required',
			'developer_site' => 'required',
			'status' => 'required|boolean',
			'platforms' => 'required|array'
			
		];
	}

	public function getMessages()
	{
		return [
			'name.required' => __('validation.required', ['attribute' => 'Título']),
			'name.max' => __('validation.max.string', ['attribute' => 'Título', 'max' => 100]),
			'description.required' => __('validation.required', ['attribute' => 'Descrição']),
			'developer_site.required' => __('validation.required', ['attribute' => 'Site']),
			'status.required' => __('validation.required', ['attribute' => 'Status']),
			'status.boolean' => __('games.messages.status'),
			'platforms.required' => __('validation.required', ['attribute' => 'Plataforma']),
			'platforms.array' => __('validation.array', ['attribute' => 'Plataforma'])
		];
	}
}