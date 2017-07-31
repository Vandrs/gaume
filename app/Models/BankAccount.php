<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class BankAccount extends Model
{
	protected $fillable = [
		'user_id', 'bank', 'agency', 'account', 'digit'
	];

	public function user() 
	{
		return $this->belongsTo(User::class);
	}
}