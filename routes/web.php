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
	if (Auth::check()) {
		return redirect('/'.Auth::user()->role);
		
	}else{
		return redirect('/login');
	}
    
}); 



Route::get('/home', function () {
	if (Auth::check()) {
		return redirect('/'.Auth::user()->role);
		
	}else{
		return redirect('/login');
	} 
    
});

Route::get('/pdf', function () {
	
   return view('pdf.invoice');
});
Route::get('reciept/{history}', 'Controller@reciept');
Route::get('notify', 'AdminController@notify');
Route::post('notify', 'AdminController@notifypost');
Route::get('/verify/{email}/{token}', 'VerifyController@verify');

//Auth::routes();
// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
//$this->post('login', 'Auth\LoginController@login');
$this->post('login', 'LoginController@login');
$this->any('logout', 'LoginController@logout')->name('logout');

// Registration Routes...
$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
$this->post('register', 'RegisterController@register');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');

//Route::get('/home', 'HomeController@index')->name('home');
Route::get('/mail', 'HomeController@mail');


//Customer Routes
Route::get('/cus', 'CustomerController@index');
Route::get('/cus/edit', 'CustomerController@editget');
Route::post('/cus/edit', 'CustomerController@editpost');
Route::get('/cus/invest', 'CustomerController@investget');
Route::post('/cus/invest', 'CustomerController@investpost');
Route::get('/cus/history', 'CustomerController@transactions');
Route::get('/cus/contact', 'CustomerController@getcontact');
Route::get('/cus/refund', 'CustomerController@getrefund');
Route::post('/cus/contact', 'CustomerController@postcontact');
Route::get('/cus/referals', 'CustomerController@referals')->middleware('mentor');
Route::get('/cus/mentorcus/{user}', 'CustomerController@mentorcus')->middleware('mentor');
Route::get('/cus/mentorcus/history/{user}', 'CustomerController@mentorcushistory')->middleware('mentor');

//Admin Routes
//Route::get('/invest', 'AdminController@investapprove');

Route::get('/admin', 'AdminController@index');
Route::get('/admin/invest/reject/{history}', 'AdminController@investreject');
Route::get('/admin/invest/approve/{history}', 'AdminController@investapprove');
Route::get('/admin/invest/delete/{history}', 'AdminController@investdelete');
Route::get('/admin/approvepaid/{history}', 'AdminController@approvepaid');
Route::get('/admin/referals', 'AdminController@referals');
Route::get('/admin/identity', 'AdminController@identity');
Route::get('/admin/mentors', 'AdminController@mentors');
Route::get('/admin/historys', 'AdminController@transactions');
Route::get('/admin/customers', 'AdminController@customers');
Route::get('/admin/admins', 'AdminController@admins');
Route::get('/admin/edit', 'AdminController@editget');
Route::post('/admin/edit', 'AdminController@editpost');
Route::get('/admin/addadmin', 'AdminController@addadminget');
Route::post('/admin/addadmin', 'AdminController@addadminpost');

//Admin to Customer Routes
Route::get('/admin/cus/{user}', 'ViewcusController@index');
Route::get('/admin/cus/edit/{user}', 'ViewcusController@editget');
Route::post('/admin/cus/edit/{user}', 'ViewcusController@editpost');
Route::get('/admin/cus/history/{user}', 'ViewcusController@transactions');
Route::get('/admin/cus/referals/{user}', 'ViewcusController@referals');
Route::get('/admin/changeid/{user}', 'ViewcusController@changeid');
Route::get('/admin/verifyid/{user}', 'ViewcusController@verifyid');
Route::get('/admin/delete/{user}', 'ViewcusController@delete');
Route::get('/admin/suspend/{user}', 'ViewcusController@suspend');
Route::get('/admin/unsuspend/{user}', 'ViewcusController@unsuspend');
Route::get('/admin/makementor/{user}', 'ViewcusController@makementor');
Route::get('/admin/invest/{user}', 'ViewcusController@getinvest');
Route::post('/admin/invest/{user}', 'ViewcusController@postinvest');
Route::get('/admin/dues', 'AdminController@getDues');
Route::get('/admin/notify_change/{user}', 'AdminController@notify_change');
Route::post('/admin/dues', 'AdminController@postDues');

Route::get('/dues/{month}/{year}', 'AdminController@dues');
Route::get('user/{email}/{type}/{change}/{token}', 'Controller@user');
Route::get('history/{tran}/{type}/{change}/{token}', 'Controller@history');
Route::get('sms', 'Controller@custom_sms');