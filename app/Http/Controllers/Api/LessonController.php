<?php 

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class LessonController extends RestController 
{
	public function get(Request $request, $id)
	{
		return $this->success(['id' => $id]);
	}		

	public function list()
	{

	}

	public function create()
	{
		return $this->created(1);
	}

	public function update()
	{

	}
}

