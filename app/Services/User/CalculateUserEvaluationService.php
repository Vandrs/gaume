<?php 

namespace App\Services\User;

use App\Models\User;
use App\Models\UserEvaluation;
use App\Models\LessonEvaluation;
use App\Enums\EnumStatus;
use App\Enums\EnumRole;
use Illuminate\Support\Collection;
use DB;

class CalculateUserEvaluationService
{
	public function calculate(User $user)
	{
		$evaluations = $this->getLessonEvaluations($user);
		if ($evaluations->count()) {
			$avgNote = $evaluations->avg('note');
			$qtdEvaluations = $evaluations->count();
			if ($user->evaluation) {
				$this->update($user->evaluation, $qtdEvaluations, $avgNote);
			} else {
				$this->create($user, $qtdEvaluations, $avgNote);
			}
		}
	}

	private function update(UserEvaluation $evaluation, $qtd, $avgNote)
	{
		return $evaluation->update([
			'note' => $avgNote,
			'qtd_evaluations' => $qtd
		]);
	}

	private function create(User $user, $qtd, $avgNote)
	{
		return UserEvaluation::create([
			'user_id' => $user->id,
			'note' => $avgNote,
			'qtd_evaluations' => $qtd
		]);
	}


	private function getLessonEvaluations(User $user) 
	{
		$query = LessonEvaluation::query()
								 ->select([DB::raw('lesson_evaluations.*')])
								 ->join('lessons','lessons.id','=','lesson_evaluations.lesson_id')
								 ->where('lesson_evaluations.status','=', EnumStatus::ACTIVE);

		if ($user->hasRole(EnumRole::STUDENT)) {
			$query->where('lesson_evaluations.type','=',EnumRole::STUDENT)
				  ->where('lessons.student_id','=',$user->id);
			return $query->get();
		} else if ($user->hasRole(EnumRole::TEACHER)) {
			$query->where('lesson_evaluations.type','=',EnumRole::TEACHER)
				  ->where('lessons.teacher_id','=',$user->id);
			return $query->get();
		} else {
			return new Collection();
		}		 
	}

}