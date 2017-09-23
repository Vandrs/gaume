<?php 

namespace App\Transformers;

use League\Fractal;
use App\Models\User;
use App\Models\TeacherGame;
use App\Enums\EnumStatus;

class TeacherTransformer extends Fractal\TransformerAbstract
{

	private $gameId; 

	public function __construct($gameId = null)
	{
		$this->gameId = $gameId;
	}

	public function transform(User $teacher)
	{
		return [
			'id' 	 	 => $teacher->id,
			'name' 	 	 => $teacher->name,
			'photo'		 => $teacher->getPhotoProfileUrl(),
			'nickname' 	 => $teacher->nickname,
			'status' 	 => $teacher->status,
			'is_online'  => $teacher->is_online,
			'games'  	 => $this->parseGames($teacher),
			'evaluation' => $this->parseEvaluation($teacher)
		];
	}

	public function parseGames(User $teacher)
	{
		$games = [];
		$gameId = $this->gameId;

		$teacher->teacherGames->each( function($teacherGame) use (&$games, $gameId) {
			if ($teacherGame->status == EnumStatus::ACTIVE && 
				$teacherGame->game->status == EnumStatus::ACTIVE ) {
				$platforms = [];
				$teacherGame->teacherGamePlatforms->each(function($teacherGamePlatform) use (&$platforms) {
					$platformData = [
						'id' 	   => $teacherGamePlatform->platform->id,
						'name' 	   => $teacherGamePlatform->platform->name,
						'nickname' => $teacherGamePlatform->nickname
					];
					array_push($platforms, $platformData);
				});
				$gameData = [
					'id' => $teacherGame->game->id,
					'name' => $teacherGame->game->name,
					'photo' => $teacherGame->game->photo,
					'description' => $teacherGame->description,
					'platforms' => $platforms,
				];
				array_push($games, $gameData);
			}
		});

		return $games;	
	}

	public function parseEvaluation(User $teacher)
	{
		if ($teacher->evaluation) {
			return [
				'id' => $teacher->evaluation->id,
				'note' => $teacher->evaluation->note,
				'qtd_evaluations' => $teacher->evaluation->qtd_evaluations
			];
		} else {
			return [
				'id' => null,
				'note' => 0,
				'qtd_evaluations' => 0
			]; 
		}
	}
}