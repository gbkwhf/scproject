<?php

// Route::get('/', function()
// {	
// 	if($_SERVER['SERVER_PORT'] == 443){
// 		header('location:'.'https://'.$_SERVER['HTTP_HOST'].'/website/');
// 	}else{
// 		header('location:'.'http://'.$_SERVER['HTTP_HOST'].'/website/');
// 	}	
// 	//header('location:'.'http://'.$_SERVER['HTTP_HOST'].'/shengtejia/public/website/');	
// 	exit;
// });
Route::get('image', function(){
	return returnImage(Request::get('image'));
});

Route::get('api/gxsc/show-ico/{fileName}','Baseuser\AuthController@showIco');


Route::group([

    'prefix' => 'api/gxsc','namespace' => 'Baseuser'

],function (){

	//用户实现注册（完成）
	Route::post('auth/register','AuthController@register');
	//普通用户名密码登陆（完成）
	Route::post('auth/login','AuthController@login');
    //免密登陆（完成）
    Route::post('auth/mobile/login','AuthController@postLoginByMobile');
    //重置密码(完成)
    Route::post('auth/reset/password','AuthController@resetPassword');

    //版本检查
    Route::post('validate/version','AuthController@validateVersion');


    //xupan  新（完成）
    //发送短信验证码，完成验证逻辑
    Route::post('auth/send/user/sms','SendToUserSMSController@sendToUserSMS');


});




Route::group([

    'prefix' => 'api/gxsc','namespace' => 'Baseuser', 'middleware'=> ['check.session:ys_session_info']//,'check.version']

],function (){
    //修改密码(完成)
    Route::post('auth/update/password','AuthController@changePassword');
    //获取用户头像(完成)
    Route::post('user/avatar','AuthController@getAvatar');
    //更新用户头像（完成）
    Route::post('user/update/updateuser_img','AuthController@UpdateUser_img');


    //获取用户基本信息
    Route::post('user/profile','AuthController@Profile');
    //更新个人基本信息（完成）
    Route::post('user/update/updateProfile','AuthController@UpdateProfile');
    
    




});



Route::group(['prefix' => 'api/gxsc'], function () {
	
	
	//获取订单状态接口
	Route::get('getorderstate/{order_id}','PaymentController@getOrderState');

	//商品订单支付回调
    Route::any('stj-goods-notify/{type}',['as'=>'stj::goods_notify','uses'=>'NotifyController@goodsNotify']);
    //服务订单支付回调
    Route::any('stj-service-notify/{type}',['as'=>'stj::service_notify','uses'=>'NotifyController@serviceNotify']);


});





Route::group([

    'prefix' => 'api/gxsc', 'middleware'=> ['check.session:stj_session_info']

],function () {

         Route::post('pay/goods', 'PaymentController@payGoodsOrder');

         Route::post('pay/service', 'PaymentController@payServiceOrder');

});





