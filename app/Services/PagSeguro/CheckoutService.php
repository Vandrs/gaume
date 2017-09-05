<?php 

namespace App\Services\PagSeguro;

use App\Models\User;
use App\Models\MonzyPoint;
use PagSeguro;
use Config;

class CheckoutService 
{
	public function makeCheckout(User $user, MonzyPoint $monzyPoint)
	{
		$data = $this->makeRequestData($user, $monzyPoint);
		$checkout = PagSeguro::checkout()->createFromArray($data);
		$credentials = PagSeguro::credentials()->get();
		$information = $checkout->send($credentials);
		return $information;
	}

	private function makeRequestData(User $user, MonzyPoint $monzyPoint)
	{
		return [
			'sender'   => $this->makeSenderData($user),
			'shipping' => $this->makeShipping($user),
			'items'    => $this->makeItemData($monzyPoint),
			'acceptedPaymentMethod' => [
				'include' => [
					'paymentMethod' => [
						'group' => 'CREDIT_CARD'
					]
				]
			],
			'paymentMethodConfigs' => [
				[
		            'paymentMethod' => [
		                'group' => 'CREDIT_CARD'
		            ],
		            'configs' => [
		                [
		                    'key' => 'MAX_INSTALLMENTS_LIMIT',
		                    'value' => '1'
		                ]
		            ]
		        ]
			]
		];
	}

	private function makeSenderData(User $user) 
	{

		if (Config::get('app.env') == 'production'){
			$email = $user->email;
		} else {
			$email = Config::get('laravelpagseguro.sandbox-email');
		}

		return [
			'email' => $email,
        	'name'  => $user->name,
        	'documents' => [
            	[
                	'number' => $user->cpf,
                	'type'   => 'CPF'
            	]
        	],
        	'bornDate' => $user->birth_date->format('Y-m-d')
		];

	}

	private function makeItemData(MonzyPoint $monzyPoint)
	{
		return [
			[
	            'id' => $monzyPoint->id,
	            'description' => $monzyPoint->description(),
	            'shippingCost' => '0',
	            'quantity' => 1,
	            'amount' => $monzyPoint->value,
            ]
		];
	}

	private function makeShipping(User $user)
	{
		return [
			'type' => 1,
			'cost' => 0,
			'address' => [
				'postalCode' => $user->address->zipcode,
            	'street' 	 => $user->address->street,
            	'number' 	 => $user->address->number,
            	'complement' => $user->address->complement,
            	'district' 	 => $user->address->neighborhood,
            	'city' 		 => $user->address->city,
            	'state' 	 => $user->address->state,
            	'country' 	 => 'BRA',
			]
		];
	}
}