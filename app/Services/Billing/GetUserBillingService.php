<?php 

namespace App\Services\Billing;

use App\Models\Lesson;
use App\Models\User;
use App\Enums\EnumLessonStatus;
use App\Enums\EnumRole;
use App\Services\Lesson\GetLessonService;
use Carbon\Carbon;
use DB;

class GetUserBillingService
{

	public static function getByPeriod($data)
	{

		$dateIni = null;
		$dateEnd = null;

		$query = User::with(['bankAccount','address'])
					 ->where('role_id','=', EnumRole::TEACHER_ID)
					 ->whereExists(function ($query) use ($data, &$dateIni, &$dateEnd) {

                	 	$query->select(DB::raw(1))
                      		  ->from('lessons')
                      		  ->whereRaw('lessons.teacher_id = users.id')
                      		  ->where('lessons.status','=', EnumLessonStatus::FINISHED);
                      	if (isset($data['dt_ini']) && !empty($data['dt_ini'])) {
							$dateIni = Carbon::createFromFormat('Y-m-d', $data['dt_ini']);
							$query->where('lessons.created_at', '>=', $dateIni->format('Y-m-d H:i:s'));
						}

						if (isset($data['dt_end']) && !empty($data['dt_end'])) {
							$dateEnd = Carbon::createFromFormat('Y-m-d', $data['dt_end']);			
							$dateEnd->addDay();
							$query->where('lessons.created_at', '<=', $dateEnd->format('Y-m-d H:i:s'));
						}
            		});

		if (isset($data['name']) && !empty($data['name'])) {
			$query->where('users.name','LIKE','%'.$data['name'].'%');
		}

		$paginator = $query->paginate(20);
		$queryParams = array_diff_key($data, array_flip(['page']));
		$paginator->appends($queryParams);

		$paginator->getCollection()->each(function ($user) use ($dateIni, $dateEnd) {
			$user->lessons_by_period = GetLessonService::getUserLessonsByPeriod($user, EnumLessonStatus::FINISHED, $dateIni, $dateEnd);
		});
		
		return $paginator;
	}
}