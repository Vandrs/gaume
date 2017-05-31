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

Route::group(['middleware' => ['auth'], 'prefix' => 'app'] , function(){
	Route::get('/home', 'App\HomeController@index')->name('home');
	Route::get('/professores','App\TeacherController@index')->name('teachers.list');
	Route::get('/aulas','App\LessonController@index')->name('lessons.list');
	Route::get('/aula/{id}','App\LessonController@show')->name('lessons.show');
	Route::post('/subscriptions', 'PushSubscriptionController@update');
	Route::post('/subscriptions/delete', 'PushSubscriptionController@destroy');
});