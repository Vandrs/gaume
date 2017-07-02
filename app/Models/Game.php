<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class Game extends Model
{

	use SoftDeletes;

	protected $dates = [
		'created_at', 'updated_at', 'deleted_at'
	];

	protected $fillable = [
		"name", "description", "status", "photo", "developer_site" 
	];

	public function getPhotoUrl() 
	{ 
        if ($this->photo) {
            return Storage::disk('public')->url($this->photo);
        }
    }
}