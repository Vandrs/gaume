<?php 

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\EnumRole;

class TeacherController extends Controller
{
	public function index()
	{
		$teachers = User::where('role_id','=',EnumRole::TEACHER_ID)
						->orderBy('name','ASC')
					    ->get();
		return view('app.teacher.index',['teachers' => $teachers]);	
	}
}