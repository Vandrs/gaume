<?php 

namespace App\Models\PreRgistration;

use Illuminate\Database\Model;

class PreRgistration extends Model
{

	protected $table = 'pre_registration';

	protected $dates = [
		'created_at', 'updated_at', 'mailed_at'
	];
	
}