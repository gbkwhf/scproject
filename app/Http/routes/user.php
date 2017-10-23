<?php
//用户路由

Route::group(['prefix' => 'api/v1/baseuser','namespace' => 'Baseuser' ,
'middleware'=> ['print.request','check.version']
],
function () {
	//用户首页
	Route::post('storelist','StoreController@storeList');
	//店面详情
	Route::post('storeinfo','StoreController@storeInfo');
	//取得该店所有评价
	Route::post('storecomments','StoreController@storeComments');
	//取得该店所有督脉正阳是列表
	Route::post('employeelist','StoreController@employeeList');		
	//取得该店指定日期上班的所有督脉正阳是列表
	Route::post('employeelistbydate','StoreController@employeeListByDate');	
	
	
});

Route::group(['prefix' => 'api/v1/baseuser','namespace' => 'Baseuser' ,
    'middleware'=> ['check.session:ysbt_session_info','print.request','check.version']
],
    function () {
	//提交订单
	Route::post('sendorder','StoreController@sendOrder');		
	//评价订单
	Route::post('evaluateorder','StoreController@evaluateOrder');	
	//订单详情
	Route::post('orderdetial','StoreController@orderDetial');	
	//订单列表
	Route::post('orderlist','StoreController@orderList');	
	//用户发送抢单
	Route::post('rushorder','StoreController@rushOrder');	
	//用户取消抢单
	Route::post('rushordercancle','StoreController@rushOrderCancle');	
	//用户确认抢单
	Route::post('rushorderconfirm','StoreController@rushorderConfirm');	
	//查看用户余额
	Route::post('userbalance','StoreController@userBalance');	
	//查看用户账户明细
	Route::post('userbalancelist','StoreController@userBalanceList');


    //xupan  获取用户评价页面上半部分的督脉正阳师基本信息
    Route::post('get/gover/base_info','StoreController@getGovBaseInfo');
});

