<?php

namespace App\Services\Location;

use Validator;
use App\Models\Address;
use App\Exceptions\ValidationException;
use App\Services\Service;

class UpdateAddressService extends Service
{

	public static function registerPolicies() {}

	public function update(Address $address, $data) 
	{	
		$this->validator = Validator::make($data, $this->getRules(), $this->getMessages());
		if ($this->validator->fails()) {
			throw new ValidationException();
		}
		return $address->update([
			'state_id' => $data['state'],
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
			'state' => 'required|integer|exists:states,id',  
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