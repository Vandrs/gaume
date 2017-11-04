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

Route::get('notifications/last', 'NotificationController@last');
Route::post('notifications/{id}/dismiss', 'NotificationController@dismiss');
Route::post('contact', 'ContactController@createGuestContact');

Route::group(['middleware' => ['auth:api']] , function () {

	#users
	Route::post('/users/{id}/online', 'UserOnlineController@online');
	Route::post('/users/{id}/offline', 'UserOnlineController@offline');

	#Lesson
	Route::patch('/lessons/{id}','LessonController@confirm');
	Route::post('/lessons','LessonController@create');
	Route::get('/lessons','LessonController@getAll');
	Route::get('/lessons/{id}','LessonController@get');

	#Games
	Route::get('/games', 'GameController@list');

	#Teachers
	Route::get('/teachers', 'TeacherController@list');
	Route::get('/teachers/{id}', 'TeacherController@get');

	#LessonEvaluation
	Route::get('/lesson-evaluations/{id}','LessonEvaluationController@get');
	Route::put('/lesson-evaluations/{id}','LessonEvaluationController@evaluate');

	#Period
	Route::post('/lessons/{lessonId}/periods','PeriodController@create');
	Route::patch('/lessons/{lessonId}/periods/{id}','PeriodController@confirm');
	Route::patch('/periods/{id}','PeriodController@confirm');

	#User 
	Route::get('/me','UserController@getMe');
	Route::get('/me/games','TeacherGameController@get');
	Route::put('/me/games','TeacherGameController@update');
	Route::post('/me','UserController@update');
	Route::post('/me/photo','UserController@updatePhoto');
	Route::get('/teacher/games/{id}','TeacherGameController@getGamesForLesson');

	#Transactions
	Route::get('/transactions','TransactionController@list');

	#Messages
	Route::post('/message/threads','MessageController@createThread');
	Route::get('/message/threads','MessageController@getThreads');
	Route::get('/message/threads/{id}','MessageController@getThread');
	Route::get('/message/threads/{id}/messages','MessageController@getMessages');
	Route::delete('/message/threads/{id}','MessageController@delete');
	Route::put('/message/threads/{id}','MessageController@updateThread');
	Route::put('/message/threads/{id}/read','MessageController@readThread');


	#Notifications
	Route::get('/notifications', 'NotificationController@index');
	Route::patch('/notifications/{id}/read', 'NotificationController@markAsRead');
	Route::post('/notifications/mark-all-read', 'NotificationController@markAllRead');

	#Notifications Subscriptions
	Route::post('/subscriptions', 'PushSubscriptionController@update');
	Route::post('/subscriptions/delete', 'PushSubscriptionController@destroy');

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
		Route::post('/users/teachers/pre-registration/{id}/send-email','PreRegistrationController@reSendRegistrationEmail');

		# Billing
		Route::get('/billing','BillingController@listBillingUsers');

		# Contact
		Route::get('/contacts','ContactController@list');
		Route::delete('/contacts/{id}','ContactController@delete');
		Route::post('/contacts/{id}/read','ContactController@markAsRead');

		#Users
		Route::get('/users','UserAdminController@list');
		
	});

	#Contact 
	Route::post('/faq-contact','ContactController@createContact');
	
});

// Public routes
Route::get('/address/{cep}','LocationController@getAddressByCep');
Route::get('/platforms','PlatformController@getAll');