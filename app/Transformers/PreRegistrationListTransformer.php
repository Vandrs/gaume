<?php 

namespace App\Transformers;

use League\Fractal;
use App\Models\PreRegistration;

class PreRegistrationListTransformer extends Fractal\TransformerAbstract
{
	public function transform(PreRegistration $preRegistration)
	{
		return [
			'id' 		    => $preRegistration->id,
			'name' 		    => $preRegistration->name,
			'email' 	    => $preRegistration->email,
			'mailed_at'     => $preRegistration->mailed_at ? $preRegistration->mailed_at->__toString() : null, 
			'created_at'    => $preRegistration->created_at->__toString(),
			'updated_at'    => $preRegistration->updated_at->__toString()
		];
	}
};