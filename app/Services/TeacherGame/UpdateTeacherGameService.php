<?php 

namespace App\Services\TeacherGame;

use App\Models\TeacherGame;
use App\Models\User;
use App\Services\Service;
use App\Exceptions\ValidationException;
use Validator;
use Log;
use App\Services\TeacherGame\UpdateTeacherGamePlatformService;

class UpdateTeacherGameService extends Service
{

	public static function registerPolicies() {}

	public function update(User $teacher, array $data)
	{
		$updateGamePlatformService = new UpdateTeacherGamePlatformService();
		foreach ($data as $teacherGameData) {
			$this->validator = Validator::make($teacherGameData, $this->getValidation());
			if ($this->validator->fails()) {
				throw new ValidationException('Parâmetros inválidos');
			}
			$teacherGame = TeacherGame::where('id', $teacherGameData['id'])
									  ->where('teacher_id', $teacher->id)
									  ->firstOrFail();
			$teacherGame->update(['description' => $teacherGameData['description']]);		
			try {
				$updateGamePlatformService->update($teacherGame, $teacherGameData['platforms']);
			} catch (ValidationException $e) {
				$this->validator->messages()->merge($updateGamePlatformService->getValidator()->messages());
				throw $e;
			}
		}
	}

	public function getValidation()
	{
		return [
			'id' 	  	  => 'required|exists:teacher_game,id',
			'description' => 'required',
			'platforms'   => 'required|array'
		];
	}


}