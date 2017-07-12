<?php 

namespace App\Models\PreRgistration;

use Illuminate\Database\Model;
use App\Models\PreRegistration;
use App\Models\GamePlatform;


class PreRegistrationPlatform extends Model
{

	protected $table = "pre_registration_platforms";

	protected $dates = [
		'created_at', 'updated_at', 'mailed_at'
	];

	protected $fillable = [
		'name', 'email', 'code', 'mailed_at'
	];

	public function preRegistration
	{
		return $this->hasOne(PreRegistration::class);
	}

	public function gamePlatform()
	{
		return $this->hasOne(GamePlatform::class);
	}

}