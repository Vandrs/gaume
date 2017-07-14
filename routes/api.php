<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
*/

Route::group(['middleware' => ['auth:api']] , function () {

	#Lesson
	Route::patch('/lessons/{id}','LessonController@confirm');
	Route::post('/lessons','LessonController@create');
	Route::get('/lessons','LessonController@getAll');
	Route::get('/lessons/{id}','LessonController@get');

	#Period
	Route::post('/lessons/{lessonId}/periods','PeriodController@create');
	Route::patch('/lessons/{lessonId}/periods/{id}','PeriodController@confirm');
	Route::patch('/periods/{id}','PeriodController@confirm');

	#User 
	Route::get('/me','UserController@getMe');
	Route::post('/me','UserController@update');
	Route::post('/me/photo','UserController@updatePhoto');

	Route::group(['middleware' => ['adminOnly'], 'prefix' => 'admin'] , function () {
		#Game Admin
		Route::post('/game','GameAdminController@create');
		Route::get('/game/{id}','GameAdminController@get');
		Route::get('/games','GameAdminController@list');
		Route::get('/games/availables','GameAdminController@getAvailables');
		Route::put('/game/{id}','GameAdminController@update');
		Route::delete('/game/{id}','GameAdminController@delete');
		Route::post('/game/{id}/photo','GameAdminController@updatePhoto');

		# Teacher Admin
		Route::post('/users/teachers/pre-registration','PreRegistrationController@create');
		Route::get('/users/teachers/pre-registration','PreRegistrationController@getAll');
		Route::get('/users/teachers/pre-registration/{id}','PreRegistrationController@get');
		Route::put('/users/teachers/pre-registration/{id}','PreRegistrationController@update');
	});
	
});

// Public routes
Route::get('/states','LocationController@getStates');
Route::get('/cities/{uf}','LocationController@getCitiesByStateUf');
Route::get('/neighborhoods/{uf}','LocationController@getNeighborhoodsByStateUf');
Route::get('/platforms','PlatformController@getAll');