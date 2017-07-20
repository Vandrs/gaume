<?php 

namespace App\Services\TeacherGame;

use App\Services\Service;
use App\Models\TeacherGame;
use App\Models\TeacherGamePlatform;
use App\Exceptions\ValidationException;
use Validator;

class UpdateTeacherGamePlatformService extends Service
{

	public static function registerPolicies() {}

	public function update(TeacherGame $teacherGame, array $data)
	{
		foreach ($data as $teacherGamePlatformData) {

			$this->validator = Validator::make($teacherGamePlatformData, $this->getValidation());
			if ($this->validator->fails()) {
				throw new ValidationException('Falha ao atualizar TeacherGamePlatform: '.json_encode($this->validator->errors()->all()));
			}

			$teacherGamePlatform = TeacherGamePlatform::where('id', $teacherGamePlatformData['id'])
							   						  ->where('teacher_game_id', $teacherGame->id)
							   						  ->first();
			$teacherGamePlatform->update(['nickname' => $teacherGamePlatformData['nickname']]);
		}
	}

	public function getValidation()
	{
		return [
			'id'		  => 'required|exists:teacher_game_platforms,id',
			'nickname'    => 'required'
		];
	}
}