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
}