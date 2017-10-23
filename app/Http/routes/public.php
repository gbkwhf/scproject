<?php

Route::get('/', function()
{	
	if($_SERVER['SERVER_PORT'] == 443){
		header('location:'.'https://'.$_SERVER['HTTP_HOST'].'/website/');
	}else{
		header('location:'.'http://'.$_SERVER['HTTP_HOST'].'/website/');
	}	
	//header('location:'.'http://'.$_SERVER['HTTP_HOST'].'/shengtejia/public/website/');	
	exit;
});
Route::get('image', function(){
	return returnImage(Request::get('image'));
});




Route::group([

    'prefix' => 'api/stj','namespace' => 'Baseuser'

],function (){

	//用户实现注册
	Route::post('auth/register','AuthController@register');
	//普通用户名密码登陆
	Route::post('auth/login','AuthController@login');
	//重发短信接口
	Route::post('auth/send/sms/again','AuthController@sendSMSAgain');


    //我要咨询
    Route::post('question/newquestion','NewsController@newQuestion');
    //申请合作
    Route::post('cooperation_apply','CooperationApplyController@Cooperation_apply');

    //机构分布
    Route::post('org_distribution/org_list','OrgDistributionController@Org_list');
    Route::post('org_distribution/therr_list','OrgDistributionController@Therr_list');

    Route::post('org_distribution/tow_list','OrgDistributionController@Tow_list');

    //santeja俱乐部和发现
    Route::post('list/newlist','NewsController@NewList');
    Route::post('info/newinfo','NewsController@NewInfo');

    //健康福利
    //商品列表
    Route::post('goods/goods_list','GoodsController@Goods_list');
    //商品详情
    Route::post('goods/goods_info','GoodsController@Goods_info');
    //获取合作类型和咨询类型
    Route::post('getquestiontype','NewsController@getQuestionType');    
    //病例列表和详情
    Route::post('user_case/case_list','MyCasesFunctionController@Case_list');
    Route::post('user_case/case_info','MyCasesFunctionController@Case_info');
    //获取医院科室
    Route::post('getrecollection','NewsController@getRecollection');

    //服务订单
    //服务列表
    Route::post('service_order/order_list','ServiceOrderController@Order_list');
    //服务可选项列表
    Route::post('service_order/option_list','ServiceOrderController@Option_list');
    //精准服务选择医院类型
    Route::post('service_order/hospital_class','ServiceOrderController@Hospital_class');


	//重置密码
	Route::post('auth/reset/password','AuthController@resetPassword');
    //免密登录前发送短信
    //Route::post('auth/login/send/sms','AuthController@sendSMSBeforeLoginByMobile');
    //进行短信验证，进而免密登陆
    //Route::post('auth/mobile/login','AuthController@postLoginByMobile');
    //用户注册前发送短信
    /*Route::post('auth/register/send/sms','AuthController@beforeRegisterSendSMS');*/
    Route::post('auth/register/send/sms','AuthController@sendSMSAgain');


    //重置密码发短信
    Route::post('auth/reset/password/send/sms','AuthController@resetPasswordBySMS');

    //版本检查
    Route::post('validate/version','AuthController@validateVersion');




});




Route::group([

    'prefix' => 'api/stj','namespace' => 'Baseuser', 'middleware'=> ['check.session:stj_session_info','check.version']

],function (){
    //修改密码
    Route::post('auth/update/password','AuthController@changePassword');
    //我要咨询
   // Route::post('question/newquestion','NewsController@newQuestion');
    //历史咨询列表
    Route::post('question','NewsController@questionList');
    //历史咨询详情页
    Route::post('question/info','NewsController@questionInfo');

    //站内消息
    Route::post('messagelist','MessageController@messageList');
    //申请合作
    //Route::post('cooperation_apply','CooperationApplyController@Cooperation_apply');
    //申请成为会员
    Route::post('member_apply','MemberApplyController@Member_apply');


    //我的病例功能
    //授权码
    Route::post('auth_code','MyCasesFunctionController@Auth_code');
    //添加病例
    Route::post('user_case/add_case','MyCasesFunctionController@Add_case');
    //删除病例
    Route::post('user_case/del_case','MyCasesFunctionController@Del_case');
    //编辑病例
    //Route::post('user_case/edit_case','MyCasesFunctionController@Edit_case');
    //更新病例
    //Route::post('user_case/update_case','MyCasesFunctionController@Update_case');
    //添加图片
    Route::post('user_case/add_img','MyCasesFunctionController@Add_img');
    //删除图片
    Route::post('user_case/del_img','MyCasesFunctionController@Del_img');





    //健康福利
    //提交订单
    Route::post('goods/order_sub','OrderController@Order_sub');
    //订单列表
    Route::post('goods/order_list','OrderController@Order_list');




    //服务订单
    //服务列表
    //Route::post('service_order/order_list','ServiceOrderController@Order_list');
    //服务可选项列表
    //Route::post('service_order/option_list','ServiceOrderController@Option_list');
    //提交服务需求
    Route::post('service_order/sub_order','ServiceOrderController@Sub_order');
    //服务订单列表
    Route::post('service_order/orders_list','ServiceOrderController@Orders_list');
    //服务订单详情
    Route::post('service_order/order_info','ServiceOrderController@Order_info');
    /*精准服务订单提交
    Route::post('service_order/order_info','ServiceOrderController@Order_info');*/
    //精准服务选择医院类型
    //Route::post('service_order/hospital_class','ServiceOrderController@Hospital_class');



    
    //获取用户头像
    Route::post('user/avatar','AuthController@getAvatar');
    //更新用户头像
    Route::post('user/update/updateuser_img','AuthController@UpdateUser_img');

    //获取用户基本信息
    Route::post('user/profile','AuthController@Profile');
    //更新个人基本信息
    Route::post('user/update/updateProfile','AuthController@UpdateProfile');
    
    




});



Route::group(['prefix' => 'api/stj'], function () {
	
	
	//获取订单状态接口
	Route::get('getorderstate/{order_id}','PaymentController@getOrderState');

	//商品订单支付回调
    Route::any('stj-goods-notify/{type}',['as'=>'stj::goods_notify','uses'=>'NotifyController@goodsNotify']);
    //服务订单支付回调
    Route::any('stj-service-notify/{type}',['as'=>'stj::service_notify','uses'=>'NotifyController@serviceNotify']);


});





Route::group([

    'prefix' => 'api/stj', 'middleware'=> ['check.session:stj_session_info']

],function () {

         Route::post('pay/goods', 'PaymentController@payGoodsOrder');

         Route::post('pay/service', 'PaymentController@payServiceOrder');

});





