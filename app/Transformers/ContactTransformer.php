<?php

namespace App\Transformers;

use League\Fractal;
use App\Models\Contact;
use Lang;

class ContactTransformer extends Fractal\TransformerAbstract
{
	public function transform(Contact $contact)
	{
		return [
			'id' 		 => $contact->id,
			'name' 		 => $contact->name,
			'email' 	 => $contact->email,
			'type' 		 => $contact->type,
			'status'	 => $contact->status,
			'type_label' => Lang::get('app.contact_type.'.$contact->type),
			'comment' 	 => $contact->comment
		];
	}
}