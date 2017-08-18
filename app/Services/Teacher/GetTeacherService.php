<?php

namespace App\Services\Teacher;

use App\Models\User;
use App\Enums\EnumRole;
use App\Enums\EnumStatus;
use DB;

class GetTeacherService
{
	public static function getForClass(array $data)
	{
		$query = User::query()
			   		 ->select(DB::raw('DISTINCT users.*'))
			   		 ->join('teacher_game','users.id','=','teacher_game.teacher_id')
			   		 ->join('games','teacher_game.game_id','=','games.id')
			   		 ->where('users.status','=', EnumStatus::ACTIVE)
			   		 ->where('users.role_id','=', EnumRole::TEACHER_ID)
			   		 ->where('teacher_game.status','=',EnumStatus::ACTIVE)
			   		 ->where('games.status','=',EnumStatus::ACTIVE);

		if (isset($data['game_id']) && !empty($data['game_id'])) {
			$query->where('games.id','=',$data['game_id']);
		}

		if (isset($data['name']) && !empty($data['name'])) {
			$query->where('users.name','LIKE','%'.$data['name'].'%');
		}

		$paginator = $query->paginate(12);

		$queryParams = array_diff_key($data, array_flip(['page']));
		$paginator->appends($queryParams);
		
		return $paginator;
	}
}