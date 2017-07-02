<?php 

namespace App\Services\Game;

use App\Services\Service;
use App\Services\Game\GamePhotoUploadService;
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
			'name' => $data["name"],
			'description' => $data["description"],
			'photo' => "CUSTOM",
			'developer_site' => str_replace(["http://","https://"], "", $data["developer_site"]),
			'status' => $data["status"]
		]);

		try {
			$photoUploadService = new GamePhotoUploadService();
			$game = $photoUploadService->uploadPhoto($game, $data['photo']);
			return $game;
		} catch (ValidationException $e) {
			$this->validator->messages()->merge($photoUploadService->getValidator()->messages());	
			throw new ValidationException;
		}
	}

	public function getRules()
	{
		return [
			'name' => 'required|max:100',
			'description' => 'required',
			'developer_site' => 'required',
			'status' => 'required|boolean',
			
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
			'status.boolean' => __('games.messages.status')
		];
	}
}