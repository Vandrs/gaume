<?php

namespace App\Services\Location;

use Validator;
use App\Models\Address;
use App\Exceptions\ValidationException;
use App\Services\Service;
use App\Utils\StringUtil;

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
			'state' => $data['state'],
			'city'  => $data['city'],
			'neighborhood' => $data['neighborhood'],
			'street' => $data['street'],
			'number' => $data['number'],
			'complement' => isset($data['complement']) ? $data['complement'] : null
		]);
	}

	public function getRules()
	{
		return [
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