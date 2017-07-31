<?php

namespace App\Services\Location;

use App\Models\City;
use App\Models\State;
use App\Models\Neighborhood;
use App\Models\User;
use App\Models\Address;
use App\Exzipcodetions\ValidationExzipcodetion;
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
			throw new ValidationExzipcodetion('Errors: '.json_encode($this->validator->errors()->all()));
		}
		
		return Address::create([
			'user_id' => $user->id,
			'zipcode' => StringUtil::justNumbers($data['zipcode']),
			'state' => $data['state'],
			'city' => $data['city'],
			'neighborhood' => $data['neighborhood'],
			'street' => $data['street'],
			'number' => $data['number'],
			'complement' => isset($data['complement']) ? $data['complement'] : null
		]);
	}

	public function getRules()
	{
		return [
			'user_id' => 'required|integer|exists:users,id',
			'zipcode' => 'required|cep',
			'state' => 'required',  
			'city' => 'required', 
			'neighborhood' => 'required',
			'street' => 'required',
			'number' => 'required'
		];
	}

	public function getMessages()
	{
		return [
			'zipcode.required' => __('validation.required', ['attribute' => __('site.registration.zipcode')]),
			'state.required' => __('validation.required', ['attribute' => __('site.registration.state')]),
			'city.required'  => __('validation.required', ['attribute' => __('site.registration.city')]),
			'neighborhood.required' => __('validation.required', ['attribute' => __('site.registration.neighborhood')]),
			'street.required' => __('validation.required', ['attribute' => __('site.registration.street')]), 
			'number.required' => __('validation.required', ['attribute' => __('site.registration.number')])
		];
	}
}