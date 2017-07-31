<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Game;
use App\Models\Platform;

class GamePlatform extends Model
{
	use SoftDeletes;

	protected $table = 'game_platform';

	protected $dates = [
		'created_at','updated_at','deleted_at'
	];

	protected $fillable = [
		'game_id', 'platform_id'
	];

	public function game()
	{
		return $this->belongsTo(Game::class);
	}

	public function platform()
	{
		return $this->belongsTo(Platform::class);
	}
}