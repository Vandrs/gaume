<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PreRegistration;
use App\Models\GamePlatform;

class PreRegistrationPlatform extends Model
{

	protected $table = "pre_registration_platforms";

	protected $dates = [
		'created_at', 'updated_at', 'mailed_at'
	];

	protected $fillable = [
		'pre_registration_id', 'game_platform_id'
	];

	public function preRegistration()
	{
		return $this->belongsTo(PreRegistration::class);
	}

	public function gamePlatform()
	{
		return $this->belongsTo(GamePlatform::class);
	}

}