<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Game;
use App\Models\Platform;

class GamePlatform extends Model
{

	protected $table = 'game_platform';

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