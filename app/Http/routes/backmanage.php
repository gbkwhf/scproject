<?php
// 	Route::get('admin', ['as' => 'home','middleware' => 'auth', function () {

		
// 		return view('home');
// 	}]);

	// Authentication routes...
	Route::get('auth/login', 'Auth\AuthController@getLogin');
	Route::post('auth/login', 'Auth\AuthController@postLogin');
	Route::get('auth/logout', 'Auth\AuthController@getLogout');
	Route::get('captcha/{tmp}', 'Auth\AuthController@captcha');
	
	// Registration routes...
	Route::get('auth/register', 'Auth\AuthController@getRegister');
	Route::post('auth/register', 'Auth\AuthController@postRegister');

    //Route::get('home', 'BackManage\HomeController@HomeList',['as' => 'home','middleware' => 'auth']);
	
	
	
	
	Route::group(['namespace' => 'BackManage' ,'middleware'=> ['auth']], function () {
		
		Route::get('admin', 'HomeController@HomeList');

		Route::Post('ajax/citylist', 'AjaxController@cityList');
		Route::Post('ajax/getuserinfo', 'AjaxController@getUserInfo');
		

    });
	
	
	




