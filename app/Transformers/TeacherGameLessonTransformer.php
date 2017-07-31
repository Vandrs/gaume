<?php 

namespace App\Transformers;

use League\Fractal;
use App\Models\TeacherGame;

class TeacherGameLessonTransformer extends Fractal\TransformerAbstract
{
	public function transform(TeacherGame $teacherGame)
	{
		return [
			'id' => $teacherGame->game->id,
			'description' => $teacherGame->description,
			'game' => $teacherGame->game->name,
			'platforms'   => $this->parsePlatform($teacherGame)
		];
	}

	private function parsePlatform(TeacherGame $teacherGame)
	{
		$data = [];
		$teacherGame->teacherGamePlatforms->each(function($tacherGamePlatform) use (&$data) {
			$data[] = [
				'id' 	   => $tacherGamePlatform->platform->id,
				'platform' => $tacherGamePlatform->platform->name,
				'nickname' => $tacherGamePlatform->nickname
			];
		});
		return $data;
	}
}