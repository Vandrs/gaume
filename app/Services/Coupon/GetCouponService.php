<?php 

namespace App\Services\Coupon;

use App\Models\Coupon;
use App\Exceptions\ValidationException;
use Validator;
use DB;

class GetCouponService 
{

	private $validator;

	public function getAll($params)
	{

		if (isset($params['dt_ini']) && empty($params['dt_ini'])) {
			unset($params['dt_ini']);
		}
		if (isset($params['dt_end']) && empty($params['dt_end'])) {
			unset($params['dt_end']);
		}

		$this->validator = Validator::make($params, $this->validation(), $this->messages());
		if ($this->validator->fails()) {
			throw new ValidationException('Parâmetros de pesquisa inválidos');
		}
		$query = Coupon::query()->orderBy('valid_until','DESC');

		$formatQuery = DB::raw("DATE_FORMAT(valid_until,'%Y-%m-%d')");

		if (isset($params['dt_ini']) && !empty($params['dt_ini'])) {
			$query->where($formatQuery, '>=', $params['dt_ini']);
		}
		if (isset($params['dt_end']) && !empty($params['dt_end'])) {
			$query->where($formatQuery, '<=', $params['dt_end']);
		}
		
		$paginator = $query->paginate(20);
		$queryParams = array_diff_key($params, array_flip(['page']));
		$paginator->appends($queryParams);
		return $paginator;
	}

	public static function getByCode($code) 
	{
		return Coupon::query()
					 ->where('code', '=', $code)
					 ->firstOrFail(); 
	}

	public function validation()
	{
		return [
			'dt_ini' => 'date',
			'dt_end' => 'date'
		];
	}

	public function messages()
	{
		return [
			'dt_ini.date' => __('validation.date', ['attribute' => __('coupon.valid_until')]),
			'dt_end.date' => __('validation.date', ['attribute' => __('coupon.valid_until')])
		];
	}

	public function getValidator()
	{
		return $this->validator;
	}
}