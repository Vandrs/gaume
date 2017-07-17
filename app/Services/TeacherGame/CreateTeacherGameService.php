<?php 

namespace App\Services\TeacherGame;

use App\Services\Service;
use App\Models\User;
use App\Models\TeacherGame;
use App\Enums\EnumActiveInactive;
use App\Exceptions\ValidationException;
use App\Services\TeacherGame\CreateTeacherGamePlatformService;
use Validator;

class CreateTeacherGameService extends Service
{

	public static function registerPolicies() {}

	public function create(User $user, int $gameId, array $data)
	{
		$data = [
			'game_id'	  => $gameId,
			'description' => isset($data['description']) ? $data['description']  : null,
			'platforms'   => isset($data['platforms']) ? $data['platforms'] : []
		];

		$this->validator = Validator::make($data, $this->getValidation(), $this->getMessages());
		if ($this->validator->fails()) {
			throw new ValidationException('Falha ao criar TeacherGame: '.json_encode($this->validator->errors()->all()));
		}

		$teacherGame = TeacherGame::create([
			'teacher_id'  => $user->id,
			'game_id' 	  => $gameId,
			'description' => $data['description'],
			'status' 	  => EnumActiveInactive::ACTIVE
		]);

		$gamePlatformService = new CreateTeacherGamePlatformService();

		try {
			foreach ($data['platforms'] as $platformId => $platformData) {
				$platformData['platform_id'] = $platformId;
				$gamePlatformService->create($teacherGame, $platformData);
			}	
			return $teacherGame;
		} catch (ValidationException $e) {
			$this->validator->messages()->merge($gamePlatformService->getValidator()->messages());	
			throw $e;
		}
	}

	public function getValidation()
	{
		return [
			'game_id' 	  => 'required|exists:games,id',
			'description' => 'required',
			'platforms'   => 'required|array'
		];
	}

	public function getMessages()
	{
		return [
			'description.required' => __('site.registration.game_description')
		];
	}
}