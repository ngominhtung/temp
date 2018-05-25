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

// Route::group(function() {

// });

Route::get('/', function () {
    return view('layouts.app');
});

Route::get('gk/{group_key}', 'MainController@checkGroupKey')->name('main.check.groupKey');

Route::get('user/regist', 'Admin\RegisterController@view')->name('user.register.view');
Route::get('user/download', 'Admin\RegisterController@download')->name('user.register.download');
Route::post('user/import', 'Admin\RegisterController@import')->name('user.register.import');

Route::get('user/regist/confirm','Admin\RegisterController@registerConfirm')->name('user.register.list');
Route::get('user/regist/confirm/search','Admin\RegisterController@registerConfirm')->name('user.register.search');
Route::post('user/regist/confirm/delete','Admin\RegisterController@deleteRegister')->name('user.register.delete');
Route::post('user/regist/confirmed', 'Admin\RegisterController@confirmed')->name('user.register.confirmed');

Route::middleware(['auth'])->group(function () {
//import
    Route::get('contact/regist', 'Admin\ContactController@index')->name('contact.index');
    Route::post('contact/regist/import', 'Admin\ContactController@import')->name('contact.import');
    Route::get('contact/regist/download', 'Admin\ContactController@download')->name('contact.download');
    Route::get('contact/regist/register', 'Admin\ContactController@confirmed')->name('contact.register');
//company
    Route::get('company', 'Admin\ContactController@company')->name('contact.register.company');
    Route::get('company/search','Admin\ContactController@company')->name('contact.register.company.search');
    Route::post('company/delete','Admin\ContactController@deleteContactByCompany')->name('contact.register.company.delete');
//group
    Route::get('company/{idCompany}/group', 'Admin\ContactController@group')->name('contact.register.group');
    Route::get('company/{idCompany}/group/search','Admin\ContactController@group')->name('contact.register.group.search');
    Route::post('company/{idCompany}/group/delete','Admin\ContactController@deleteGroup')->name('contact.register.group.delete');
//contact
    Route::get('company/{idCompany}/group/{idGroup}/contact', 'Admin\ContactController@contact')->name('contact.register.list');
    Route::get('company/{idCompany}/group/{idGroup}/contact/search', 'Admin\ContactController@contact')->name('contact.register.search');
    Route::post('company/{idCompany}/group/{idGroup}/contact/delete', 'Admin\ContactController@deleteContact')->name('contact.register.delete');
});


//Auth::routes();

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login')->name('login.post');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
