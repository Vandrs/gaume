<?php 

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lang;


class SiteController extends Controller
{
	public function index(Request $request) 
	{
		$data = [
			'pageTitle' 	  => Lang::get('site.pages.home.title'),
			'pageDescription' => Lang::get('site.pages.home.description'),
			'ogDescription'   => Lang::get('site.pages.home.og_description'),
			'bodyClass'		  => 'set-bg',
			'fixedNavBar'	  => true
		];
		return view('welcome', $data);
	}

	public function teacherContact()
	{
		$data = [
			'bodyClass' => 'set-bg-green',
			'pageTitle' 	  => Lang::get('site.contact.title'),
			'pageDescription' => Lang::get('site.contact.description'),
		];
		return view('teacher-contact', $data);
	}
}