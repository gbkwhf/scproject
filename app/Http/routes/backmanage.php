<?php
	Route::get('/', ['as' => 'admin','middleware' => 'auth', function () {
		return redirect('admin');
	}]);

	
	
	// Authentication routes...
	Route::get('auth/login', 'Auth\AuthController@getLogin');
	Route::post('auth/login', 'Auth\AuthController@postLogin');
	Route::get('auth/logout', 'Auth\AuthController@getLogout');
	Route::get('captcha/{tmp}', 'Auth\AuthController@captcha');
	
	// Registration routes...
	Route::get('auth/register', 'Auth\AuthController@getRegister');
	Route::post('auth/register', 'Auth\AuthController@postRegister');

    //Route::get('home', 'BackManage\HomeController@HomeList',['as' => 'home','middleware' => 'auth']);
    


	Route::group(['namespace' => 'BackManage'], function () {
		Route::Post('ajax/citylist', 'AjaxController@cityList');
		Route::Post('ajax/getuserinfo', 'AjaxController@getUserInfo');
	});
	
	
	
	Route::group(['namespace' => 'BackManage' ,'middleware'=> ['auth','role:1']], function () {
		
		Route::get('admin', 'HomeController@HomeList');		




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
		Route::get('manage/suppliercashlist', 'SupplierController@SupplierCashList');
		Route::post('manage/suppliercashexcel', 'SupplierController@SupplierCashExcel');
		Route::get('manage/suppliercashedit/{id}', 'SupplierController@SupplierCashEdit');
		Route::post('manage/suppliercashsave', 'SupplierController@SupplierCashSave');				
		Route::get('manage/joinsupplier', 'SupplierController@joinSupplierList');		
		Route::get('manage/joinsupplierdetial/{id}', 'SupplierController@joinSupplierDetial');
		Route::post('manage/joinsupplierexcel', 'SupplierController@joinSupplierExcel');
		Route::post('manage/joinsuppliersave', 'SupplierController@joinSupplierSave');
		Route::get('member/cashbacklist', 'MemberController@CashBackList');
		
		
		
		//经销商
		Route::get('agencylist', 'AgencyController@agencyList');
		Route::get('agencyedit/{id}', 'AgencyController@agencyEdit');
		Route::Post('agencysave', 'AgencyController@agencySave');
		Route::get('agencyadd', 'AgencyController@agencyAdd');
		Route::Post('agencycreate', 'AgencyController@agencyCreate');
		Route::get('agencydelete/{id}', 'AgencyController@agencyDelete');	
		Route::get('manage/membercashlist', 'AgencyController@MemberCashList');
		Route::post('manage/membercashexcel', 'AgencyController@MemberCashExcel');
		//商品
        Route::get('goodslist', 'GoodsController@Goodslist');//商品列表
        Route::get('goods/goodsadd', 'GoodsController@Goodsadd');//添加商品
        Route::post('goods/goodscreate', 'GoodsController@GoodsCreate');//提交商品
        Route::get('goods/goodsedit/{id}', 'GoodsController@GoodsEdit');//编辑商品
        Route::post('goods/goodssave', 'GoodsController@Goodssave');//编辑商品保存
        Route::get('goods/goodsdel/{id}', 'GoodsController@Goodsdel');//删除商品
        Route::Post('ajax/getgoodsclass', 'AjaxController@getGoodsClass');        
        Route::get('manage/orderlist', 'OrderController@OrderList');//订单列表
        Route::post('manage/getorderexcel', 'OrderController@getOrderExcel');//导出订单列表
        Route::get('manage/orderdetial/{id}', 'OrderController@OrderDetial');//订单详情
        Route::get('manage/sendmemberbalance', 'MemberController@SendMemberBalance');//后台给用户返现
        Route::post('manage/sendmemberbalancesave', 'MemberController@SendMemberBalanceSave');//后台给用户返现
        Route::post('manage/manageremarksave', 'OrderController@manageRemarkSave');//客服订单备注
        
        Route::get('manage/managepassword', 'HomeController@managePassword');//管理员密码修改
        Route::post('manage/managepasswordsave', 'HomeController@managePasswordSave');//管理员密码修改
        Route::get('manage/invitemember', 'MemberController@inviteMember');
        
    });	
		Route::group(['namespace' => 'BackManage' ,'middleware'=> ['auth','role:3']], function () {
			//供应商功能			
			Route::get('supplieradmin', 'HomeController@supplierIndex');
			Route::get('supplier/orderlist', 'SupplierManageController@orderList');//订单列表
			Route::get('supplier/orderdetial/{id}', 'SupplierManageController@orderDetial');//订单发货
			Route::post('supplier/ordersend', 'SupplierManageController@orderSend');//订单发货
			Route::post('supplier/getorderexcel', 'SupplierManageController@getOrderExcel');//导出订单列表
			Route::get('supplier/supplierbillslist', 'SupplierManageController@billsList');//提现记录
			Route::get('supplier/suppliercashadd', 'SupplierManageController@supplierCashAdd');//申请提现
			Route::post('supplier/suppliercash', 'SupplierManageController@supplierCash');//提交
		});
		Route::group(['namespace' => 'BackManage' ,'middleware'=> ['auth','role:2']], function () {
			//经销商功能
			Route::get('agencyadmin', 'HomeController@agencyIndex');
			Route::get('agency/orderlist', 'AgencyManageController@orderList');//订单列表
			Route::post('agency/orderlistexcel', 'AgencyManageController@orderListExcel');			
			Route::get('agency/orderdetial/{id}', 'AgencyManageController@orderDetial');//订单发货
			Route::get('agency/setemployee', 'AgencyManageController@setEmployee');
			Route::post('agency/setemployeesave', 'AgencyManageController@setEmployeeSave');
			Route::get('agency/deleteemployee/{id}', 'AgencyManageController@DeleteEmployee');	
			Route::get('agency/membercashlist', 'AgencyManageController@MemberCashList');
			Route::post('agency/membercashexcel', 'AgencyManageController@MemberCashExcel');
			Route::get('agency/memberlist', 'AgencyManageController@MemberList');
			Route::get('agency/memberedit/{id}', 'AgencyManageController@memberEdit');
			//Route::Post('agency/membersave', 'MemberController@memberSave');
		});



