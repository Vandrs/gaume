<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => ['guest']], function(){
	Route::get('/professor/cadastro','Auth\RegisterController@showTeacherRegistrationForm')->name('teacher.registration.form');	
	Route::post('/professor/cadastro','Auth\RegisterController@showTeacherRegistrationForm')->name('teacher.registration');	
});

Route::group(['middleware' => ['auth'], 'prefix' => 'app'] , function() {
	Route::get('/home', 'App\HomeController@index')->name('home');
	Route::get('/professores','App\TeacherController@index')->name('teachers.list');
	Route::get('/aulas','App\LessonController@index')->name('lessons.list');
	Route::get('/aula/{id}','App\LessonController@show')->name('lessons.show');
	Route::post('/subscriptions', 'App\PushSubscriptionController@update');
	Route::post('/subscriptions/delete', 'App\PushSubscriptionController@destroy');
	Route::get('/perfil','App\ProfileController@index')->name('profile');

	if (config('app.env') == 'local') {
		Route::get('/teste','App\TestController@index');
	}

	Route::group(['middleware' => ['auth','adminOnly'], 'prefix' => 'admin'] , function() { 
		Route::get('/games','App\GameAdminController@index')->name('game-admin.list');
		Route::get('/games/cadastro','App\GameAdminController@create')->name('game-admin.create');
		Route::get('/games/editar/{id}','App\GameAdminController@update')->name('game-admin.update');
		Route::get('/usuarios','App\UserAdminController@index')->name('user-admin.list');
		Route::get('/usuarios/professor/pre-cadastro','App\PreRegistrationController@create')->name('user-admin.create-teacher');
		Route::get('/usuarios/professor/pre-cadastro/lista','App\PreRegistrationController@index')->name('user-admin.list-pre-registration');
		Route::get('/usuarios/professor/pre-cadastro/{id}','App\PreRegistrationController@show')->name('user-admin.edit-teacher-registration');
	});

});