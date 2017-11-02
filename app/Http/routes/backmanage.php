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
		Route::get('agencyadmin', 'HomeController@agencyIndex');
		Route::get('supplieradmin', 'HomeController@supplierIndex');

		Route::Post('ajax/citylist', 'AjaxController@cityList');
		Route::Post('ajax/getuserinfo', 'AjaxController@getUserInfo');

		//用户列表
		Route::get('memberlist', 'MemberController@memberList');
		Route::get('memberedit/{id}', 'MemberController@memberEdit');
		Route::Post('membersave', 'MemberController@memberSave');
	
		
		//供应商
		Route::get('supplierlist', 'SupplierController@supplierList');
		Route::get('supplieredit/{id}', 'SupplierController@supplierEdit');
		Route::Post('suppliersave', 'SupplierController@supplierSave');
		Route::get('supplieradd', 'SupplierController@supplierAdd');
		Route::Post('suppliercreate', 'SupplierController@supplierCreate');
		Route::get('supplierdelete/{id}', 'SupplierController@supplierDelete');
		
		//经销商
		Route::get('agencylist', 'AgencyController@agencyList');
		Route::get('agencyedit/{id}', 'AgencyController@agencyEdit');
		Route::Post('agencysave', 'AgencyController@agencySave');
		Route::get('agencyadd', 'AgencyController@agencyAdd');
		Route::Post('agencycreate', 'AgencyController@agencyCreate');
		Route::get('agencydelete/{id}', 'AgencyController@agencyDelete');		
		//商品
        Route::get('goodslist', 'GoodsController@Goodslist');//商品列表
        Route::get('goods/goodsadd', 'GoodsController@Goodsadd');//添加商品
        Route::post('goods/goodscreate', 'GoodsController@GoodsCreate');//提交商品
        Route::get('goods/goodsedit/{id}', 'GoodsController@GoodsEdit');//编辑商品
        Route::post('goods/goodssave', 'GoodsController@Goodssave');//编辑商品保存
        Route::get('goods/goodsdel/{id}', 'GoodsController@Goodsdel');//删除商品
        Route::Post('ajax/getgoodsclass', 'AjaxController@getGoodsClass');
        
        
        //供应商功能
        Route::get('supplier/orderlist', 'SupplierManageController@orderList');//订单列表
        Route::get('supplier/orderdetial/{id}', 'SupplierManageController@orderDetial');//订单发货
        Route::post('supplier/ordersend', 'SupplierManageController@orderSend');//订单发货
        
        
        //经销商功能
        Route::get('agency/orderlist', 'AgencyManageController@orderList');//订单列表
        Route::get('agency/orderdetial/{id}', 'AgencyManageController@orderDetial');//订单发货
        Route::get('agency/setemployee', 'AgencyManageController@setEmployee');
		Route::post('agency/setemployeesave', 'AgencyManageController@setEmployeeSave');
		Route::get('agency/deleteemployee/{id}', 'AgencyManageController@DeleteEmployee');	
        
		

    });
	

// 		Route::group(['namespace' => 'BackManage', 'middleware'=> ['auth']], function () {
		
// 		Route::get('testsupplier', 'SupplierController@test');
		
		
		
// 		});	
	
	
	




