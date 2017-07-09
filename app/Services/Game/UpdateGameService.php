<?php 

namespace App\Services\Game;

use App\Services\Service;
use App\Services\Game\GamePhotoUploadService;
use App\Models\Game;
use App\Exceptions\ValidationException;
use App\Services\Platform\SaveGamePlatformService;
use Validator;

class UpdateGameService extends Service
{

	public static function registerPolicies() {}

	public function update(Game $game, array $data)
	{
		$this->validator = Validator::make($data, $this->getRules(), $this->getMessages());
		if ($this->validator->fails()) {
			throw new ValidationException();
		}
		$game->update([
			'name' => $data["name"],
			'description' => $data["description"],
			'developer_site' => str_replace(["http://","https://"], "", $data["developer_site"]),
			'status' => $data["status"]
		]);
		try {
			$gamePlatformService = new SaveGamePlatformService();
			$gamePlatformService->save($game, $data['platforms']);
		} catch (ValidationException $e) {
			$this->validator->messages()->merge($gamePlatformService->getValidator()->messages());
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