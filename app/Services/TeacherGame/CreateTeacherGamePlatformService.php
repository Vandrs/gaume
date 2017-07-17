<?php 

namespace App\Services\TeacherGame;

use App\Services\Service;
use App\Models\TeacherGame;
use App\Models\TeacherGamePlatform;
use App\Exceptions\ValidationException;
use Validator;

class CreateTeacherGamePlatformService extends Service
{

	public static function registerPolicies() {}

	public function create(TeacherGame $teacherGame, array $data)
	{
		$this->validator = Validator::make($data, $this->getValidation());
		if ($this->validator->fails()) {
			throw new ValidationException('Falha ao criar TeacherGamePlatform: '.json_encode($this->validator->errors()->all()));
		}

		return TeacherGamePlatform::create([
			'teacher_game_id' => $teacherGame->id,
			'platform_id' 	  => $data['platform_id'],
			'nickname' 	  	  => $data['nickname']
		]);		

	}

	public function getValidation()
	{
		return [
			'platform_id' => 'required|exists:platforms,id',
			'nickname'    => 'required'
		];
	}
}