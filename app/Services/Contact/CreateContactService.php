<?php 

namespace App\Services\Contact;

use Validator;
use Illuminate\Validation\Rule;
use App\Enums\EnumContactType;
use App\Exceptions\ValidationException;
use App\Models\Contact;

class CreateContactService 
{

	private $validator;

	public function create(array $data)
	{
		$data['type'] = isset($data['type']) ? $data['type'] : null;
		$this->validator = Validator::make($data, $this->validation($data['type']), $this->messages());
		if ($this->validator->fails()) {
			throw new ValidationException('Parâmetros inválidos');
		}
		return Contact::create([
			'name' 	  => $data['name'], 
			'email'   => $data['email'], 
			'comment' => $data['comment'], 
			'type' 	  => $data['type']
		]);
	}

	public function validation($type = null)
	{
		$validation = [
			'name' 	  => 'required|max:100',
	        'email'   => 'required|email|max:100',
	        'type' => [
	        	'required',
	        	Rule::in([
		        	EnumContactType::SITE,
					EnumContactType::TEACHER_INTEREST,
					EnumContactType::TEACHER,
					EnumContactType::STUDENT
	        ])]
        ];
        if ($type && in_array($type, [EnumContactType::TEACHER, EnumContactType::STUDENT])) {
        	$validation['comment'] = 'required';
        }
		return $validation;
	}

	public function messages()
	{
		return [
			'name.required'    => __('validation.required', ['attribute' => 'Nome']),
			'name.max' 		   => __('validation.max.string',['attribute' => 'Nome', 'max' => 100]),
			'email.required'   => __('validation.required', ['attribute' => 'Email']),
			'email.max' 	   => __('validation.max.string',['attribute' => 'Email', 'max' => 100]),
			'email.email' 	   => __('validation.email', ['attribute' => 'Email']),
			'type.required'    => __('validation.required', ['attribute' => 'Type']),
			'type.in' 	   	   => __('validation.invalid', ['attribute' => 'Type']),
			'comment.required' => __('validation.required', ['attribute' => 'Comentário']),
		];
	}

	public function getValidator()
	{
		return $this->validator;
	}


}