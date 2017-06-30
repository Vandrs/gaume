<?php 

namespace App\Services\Game;

use App\Services\Service;
use App\Exceptions\ValidationException;
use Illuminate\Http\UploadedFile;
use Validator;

class CreateGameService extends Service
{

	public static function registerPolicies() {}

	public function create(array $data, UploadedFile $file = null)
	{
		$this->validator = Validator::make($data, $this->getRules(), $this->getMessages());
		if ($this->validator->fails()) {
			throw new ValidationException();
		}
	}

	public function getRules()
	{
		return [
			'name' => 'required',
			'description' => 'required',
			'developer_site' => 'required',
			'status' => 'required|boolean'
		];
	}

	public function getMessages()
	{
		return [
			'name.required' => __('validation.required', ['attribute' => 'Título']),
			'description.required' => __('validation.required', ['attribute' => 'Descrição']),
			'developer_site.required' => __('validation.required', ['attribute' => 'Site']),
			'status.required' => __('validation.required', ['attribute' => 'Status']),
			'status.boolean' => __('games.messages.status')
		];
	}


}