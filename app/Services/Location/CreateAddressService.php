<?php

namespace App\Services\Location;

use App\Models\City;
use App\Models\State;
use App\Models\Neighborhood;
use App\Models\User;
use App\Models\Address;
use App\Exceptions\ValidationException;
use Validator;
use App\Services\Service;
use App\Utils\StringUtil;

class CreateAddressService extends Service
{

	public static function registerPolicies() {}

	public function create(User $user, $data) 
	{
		$data['user_id'] = $user->id;
		$this->validator = Validator::make($data, $this->getRules(), $this->getMessages());
		if ($this->validator->fails()) {
			throw new ValidationException();
		}
		$state = State::query()->where('uf','=',StringUtil::toupper($data['state']))->first();
		if (empty($state)) {
			$this->validator->errors()->add('state',__('validation.exists', ['attribute' => __('site.registration.state')]));
			throw new ValidationException();
		}
		return Address::create([
			'user_id'  => $user->id,
			'state_id' => $state->id,
			'city_id'  => $data['city'],
			'neighborhood_id' => $data['neighborhood'],
			'street' => $data['street'],
			'number' => $data['number'],
			'complement' => isset($data['complement']) ? $data['complement'] : null
		]);
	}

	public function getRules()
	{
		return [
			'user_id' => 'required|integer|exists:users,id',
			'state' => 'required',  
			'city' => 'required|integer|exists:cities,id', 
			'neighborhood' => 'required|integer|exists:neighborhoods,id',
			'street' => 'required',
			'number' => 'required'
		];
	}

	public function getMessages()
	{
		return [
			'state.required' => __('validation.required', ['attribute' => __('site.registration.state')]),
			'state.exists'   => __('validation.exists', ['attribute' => __('site.registration.state')]),
			'city.required'  => __('validation.required', ['attribute' => __('site.registration.city')]),
			'city.exists'    => __('validation.exists', ['attribute' => __('site.registration.city')]), 
			'neighborhood.required' => __('validation.required', ['attribute' => __('site.registration.neighborhood')]),
			'neighborhood.exists'   => __('validation.exists', ['attribute' => __('site.registration.neighborhood')]),
			'street.required' => __('validation.required', ['attribute' => __('site.registration.street')]), 
			'number.required' => __('validation.required', ['attribute' => __('site.registration.number')])
		];
	}
}