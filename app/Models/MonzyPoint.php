<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonzyPoint extends Model
{

	protected $fillable = ['amount'];

	public function description($html = false)
	{
		$prefix = $html ? 'html_' : '';
		if ($this->bonus) {
			return __('wallet.'.$prefix.'point_description_with_bonus', ['name' => $this->name, 'points' => $this->points, 'bonus' => $this->bonus]);
		} else {
			return __('wallet.'.$prefix.'point_description', ['name' => $this->name, 'points' => $this->points]);
		}
		
	}

}

