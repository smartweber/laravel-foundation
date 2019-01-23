<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');

Route::get('home', 'HomeController@index');

Route::get('report', 'HomeController@report');

Route::get('loginas/{id}', 'HomeController@loginas');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::resource('crt', 'CrtController');

Route::post('crt/{id}', 'CrtController@update');

Route::post('crt/approve/{id}', 'CrtController@approve');

Route::post('crt/precertify/{id}', 'CrtController@precertify');

Route::post('crt/certify/{id}', 'CrtController@certify');

Route::get('crt/decertify/{id}', 'CrtController@decertify');

Route::post('crt/destroy/{id}', 'CrtController@destroy');

Route::get('section/{crtid}/{section}', 'SectionController@show');

Route::get('section/{crtid}/{section}/edit', 'SectionController@edit');

Route::post('section/{crtid}/{section}', 'SectionController@update');

Route::get('answer/{answerId}/{field}/{value?}', 'SectionController@answer');

Route::get('restaurants/getMfpDfp/{restid}', 'CrtController@getMfpDfp');
Route::get('crt/getCrts/{restid}', 'CrtController@getCrts');
Route::get('crt/poachCrts/{restid}/{crts}', 'CrtController@poachCrts');

// Redirects for common 404's
Route::pattern('dm', '[dmDM]{2}');
Route::pattern('mfp', '[mfpMFP]{3}');
Route::get('{dm}', function(){ return redirect('auth/register/DM'); });
Route::get('{mfp}', function(){ return redirect('auth/register/MFP'); });