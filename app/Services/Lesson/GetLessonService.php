<?php 

namespace App\Services\Lesson;

use Gate;
use DB;
use Validator;
use App\Services\Service;
use App\Enums\EnumPolicy;
use App\Enums\EnumRole;
use App\Enums\EnumLessonStatus;
use App\Models\User;
use App\Models\Lesson;
use Illuminate\Validation\Rule;
use App\Exceptions\ValidationException;


class GetLessonService extends Service
{
	public static function registerPolicies()
	{
		$service = new self();
		Gate::define(EnumPolicy::GET_LESSON, function(User $user, Lesson $lesson) use ($service) {
			return $service->getPolicy($user, $lesson);
		});
	}

	public function getPolicy(User $user, Lesson $lesson)
	{
		if ($user->hasRole(EnumRole::ADMIN)) {
			return true;
		}
		return in_array($user->id,[$lesson->teacher_id, $lesson->student_id]);
	}

	public function get($id, $includes = null)
	{
		if ($includes) {
			return Lesson::with(explode(',', $includes))
						 ->where('id', $id)
						 ->firstOrFail();
		} else {
			return Lesson::findOrfail($id);
		}
	}

	public function getAll(User $user, $data, $size = 20)
	{
		$this->validator = Validator::make($data, $this->getAllValidation(), $this->getAllValidationMessages());
		if ($this->validator->fails()) {
			throw new ValidationException('FALHA AO VALIDAR: '.json_encode($this->validator->errors()->all()));
		}



		$selectData = [
			'lessons.id',
			'lessons.teacher_id',
			'lessons.student_id',
			'lessons.created_at',
			'lessons.status',
			'lessons.value',
			DB::raw('teacher.name as teacher_name'),
			DB::raw('teacher.email as teacher_email'),
			DB::raw('student.name as student_name'),
			DB::raw('student.email as student_email')
		];

		$query = DB::table('lessons')
					->select($selectData)   	
					->join(DB::raw('users AS teacher'), 'lessons.teacher_id', '=', 'teacher.id')
		  		   	->join(DB::raw('users AS student'), 'lessons.student_id', '=', 'student.id');
		
		if (!$user->hasRole(EnumRole::ADMIN)) {
			$query->where(function($query) use ($user) {
				$query->where('teacher.id','=',$user->id)
					 ->orWhere('student.id','=',$user->id);
			});
		}

		if (isset($data['teacher']) && !empty($data['teacher'])) {
			$query->where('teacher.name','like','%'.$data['teacher'].'%');
		}

		if (isset($data['student']) && !empty($data['student'])) {
			$query->where('student.name','like','%'.$data['student'].'%');
		}

		if (isset($data['status']) && !empty($data['status'])) {
			$query->where('lessons.status', '=', $data['status']);
		} else {
			$query->where('lessons.status', '<>', EnumLessonStatus::CANCELED);
		}

		if (isset($data['start_date']) && !empty($data['start_date'])) {
			$query->where('lessons.created_at', '>=', $data['start_date']);
		}

		if (isset($data['end_date']) && !empty($data['end_date'])) {
			$query->where('lessons.created_at', '<=', $data['end_date']);
		}

		$query->orderBy('lessons.created_at','DESC');

		$paginator = $query->paginate(10);

		$paginator->getCollection()->each(function ($data) {
			$data->periods = DB::table('periods')->where('lesson_id', '=', $data->id)->get()->all();
		});

		$queryParams = array_diff_key($data, array_flip(['page']));
		$paginator->appends($queryParams);
		return $paginator;

	}

	public function getAllValidation()
	{
		$status = [
			EnumLessonStatus::PENDING, 
			EnumLessonStatus::IN_PROGRESS, 
			EnumLessonStatus::FINISHED, 
			EnumLessonStatus::CANCELED
		];
		return [
			'teacher' => 'nullable|alpha|min:3',
			'student' => 'nullable|alpha|min:3',
			'status' =>  ['nullable',Rule::in($status)],
			'start_date' => 'nullable|date_format:Y-m-d',
			'end_date' => 'nullable|date_format:Y-m-d'
		];
	}

	public function getAllValidationMessages()
	{
		return [
			'teacher.alpha' => __('validation.alpha',['attribute' => __('app.labels.teacher')]),
			'teacher.min' => __('validation.min.string',['attribute' => __('app.labels.teacher'), 'min' => 3]),
			'student.alpha' => __('validation.alpha',['attribute' => __('app.labels.student')]),
			'student.min' => __('validation.min.string',['attribute' => __('app.labels.student'), 'min' => 3]),
			'status.in' =>  __('validation.in',['attribute' => 'Status']),
			'start_date.date_format' => __('validation.date_format', ['attribute' => __('app.labels.start'), 'format' => 'yyyy-mm-dd']),
			'end_date.date_format' => __('validation.date_format', ['attribute' => __('app.labels.start'), 'format' => 'yyyy-mm-dd'])
		];
	}
}