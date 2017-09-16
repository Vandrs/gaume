<?php 

namespace App\Transformers;

use App\Utils\StringUtil;
use App\Models\User;
use App\Services\Lesson\GetLessonService;
use Illuminate\Support\Collection;
use App\Enums\EnumLessonStatus;
use League\Fractal;

class UserBillingTransformer extends Fractal\TransformerAbstract
{
	
	public function transform(User $user)
	{
		return [
			'name' 		   => $user->name,
			'cpf' 		   => StringUtil::mask($user->cpf, "###.###.###-##"),
			'email' 	   => $user->email,
			'bankAccount'  => $this->getBankAccount($user),
			'lessons_info' => $this->getLessonsInfo($user)
		];
	}

	private function getBankAccount(User $user)
	{
		if ($user->bankAccount) {
			return [
				'bank'    => $user->bankAccount->bank,
				'agency'  => $user->bankAccount->agency,
				'account' => $user->bankAccount->account,
				'digit'   => $user->bankAccount->digit
			];
		} 
		return null;
	}

	private function getLessonsInfo(User $user)
	{
		$value = $user->lessons_by_period->sum('value');
		return [
			'qtd_lessons' => $user->lessons_by_period->count(),
			'hours' 	  => $this->sumHours($user->lessons_by_period),
			'value' 	  => $value,
			'formated_value' => "R$".number_format($value, 2, '.',',')
		];
	}

	private function sumHours(Collection $lessons)
	{
		$totalHours = 0;
		$lessons->each(function($lesson) use (&$totalHours) {
			$finishedPeriods = $lesson->periods->filter(function($period) {
				return $period->status == EnumLessonStatus::FINISHED;
			});
			$totalHours += $finishedPeriods->sum('hours');
		});
		return $totalHours;
	}	
}