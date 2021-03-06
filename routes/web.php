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
Auth::routes();

Route::get('/','Site\SiteController@index')->name('site.home');
Route::get('/professor/contato','Site\SiteController@teacherContact')->name('site.teacher.contact');

Route::group(['middleware' => ['guest']], function(){
	Route::get('/professor/cadastro','Auth\RegisterController@showTeacherRegistrationForm')->name('teacher.registration.form');	
	Route::post('/professor/cadastro','Auth\RegisterController@teacherRegister')->name('teacher.registration');	
});

Route::post('/pagseguro/notification', [
	'uses' => '\laravel\pagseguro\Platform\Laravel5\NotificationController@notification',
	'as' => 'pagseguro.notification',
]);

Route::group(['middleware' => ['auth','activeUserOnly'], 'prefix' => 'app'] , function() {
	Route::get('/home', 'App\HomeController@index')->name('home');
	Route::get('/treinadores','App\TeacherController@index')->name('teachers.list');
	Route::get('/treinadores/{id}','App\TeacherController@show')->name('teachers.page');
	Route::get('/aulas','App\LessonController@index')->name('lessons.list');
	Route::get('/aula/{id}','App\LessonController@show')->name('lessons.show');
	Route::get('/perfil','App\ProfileController@index')->name('profile');
	Route::get('/meus-jogos','App\TeacherGameController@index')->name('my-games');
	Route::get('/jogos','App\GameController@index')->name('games');
	Route::get('/carteira', 'App\WalletController@index')->name('pagseguro.redirect');
	Route::post('/carteira/pagamentos/{id}', 'App\WalletController@makePaymentRequest')->name('pagseguro.payment');
	Route::get('/faq', 'App\FaqController@index')->name('app.faq');
	Route::get('/mensagens','App\MessageController@index')->name('messages');

	if (config('app.env') == 'local') {
		Route::get('/teste','App\TestController@index');
	}

	Route::group(['middleware' => ['auth','adminOnly'], 'prefix' => 'admin'] , function() { 
		Route::get('/games','App\GameAdminController@index')->name('game-admin.list');
		Route::get('/games/cadastro','App\GameAdminController@create')->name('game-admin.create');
		Route::get('/games/editar/{id}','App\GameAdminController@update')->name('game-admin.update');
		Route::get('/usuarios','App\UserAdminController@index')->name('user-admin.list');
		Route::get('/usuarios/{id}','App\UserAdminController@view')->name('user-admin.view');
		Route::get('/usuarios/professor/pre-cadastro','App\PreRegistrationController@create')->name('user-admin.create-teacher');
		Route::get('/usuarios/professor/pre-cadastro/lista','App\PreRegistrationController@index')->name('user-admin.list-pre-registration');
		Route::get('/usuarios/professor/pre-cadastro/{id}','App\PreRegistrationController@show')->name('user-admin.edit-teacher-registration');
		Route::get('/pagamentos','App\BillingController@index')->name('billing');
		Route::get('/contatos','App\FaqController@contactList')->name('contact-list');
		Route::get('/cupons','App\CouponController@index')->name('coupon');
	});
});