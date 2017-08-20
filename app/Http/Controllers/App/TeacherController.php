<?php 

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\EnumRole;

class TeacherController extends Controller
{
	public function index(Request $request)
	{
		$gameId = $request->game;
		return view('app.teacher.index',['gameId' => $gameId]);	
	}

	public function show(Request $request, $id)
	{
		$url = route('teachers.page',['id' => $id]);
		$page_id = 'teacher_'.$id;
		return view('app.teacher.show',[
			'id' 	   => $id,
			'page_url' => $url,
			'page_id'  => $page_id
		]);
	}
}