<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PreRegistrationPlatform;

class PreRegistration extends Model
{

	protected $table = 'pre_registration';

	protected $dates = [
		'created_at', 'updated_at', 'mailed_at'
	];

	protected $fillable = [
		'name', 'email', 'code', 'mailed_at'
	];

	public function preRegistrationPlatforms()
	{
		return $this->hasMany(PreRegistrationPlatform::class);
	}
	
}