<?php

namespace App\Services\Game;

use App\Models\Game;
use App\Enums\EnumActiveInactive;
use App\Enums\EnumUserStatus;
use DB;

class GetGameService
{
	public function get($id)
	{
		return Game::findOrFail($id);
	}

	public static function getHomeGames()
	{
		return Game::query()
		    ->select(DB::raw('DISTINCT games.* '))		
		    ->join('teacher_game','games.id','=','teacher_game.game_id')
		    ->join('users','teacher_game.teacher_id','=','users.id')
		    ->where('games.status', '=', EnumActiveInactive::ACTIVE)
		    ->where('teacher_game.status', '=', EnumActiveInactive::ACTIVE)
		    ->where('users.status', '=', EnumUserStatus::ACTIVE)
		    ->orderBy('games.name')
		    ->get();
	}
}