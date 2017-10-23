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
		
		Route::get('memberlist', 'MemberController@memberList');
		Route::get('member/memberedit/{id}', 'MemberController@memberEdit');
		Route::Post('member/membersave', 'MemberController@memberSave');	
		Route::get('member/memberdelete/{id}', 'MemberController@memberDelete');		
		Route::get('member/memberadd', 'MemberController@memberAdd'); //后台新用户注册
        Route::Post('member/memberaddsave', 'MemberController@memberAddSave');//注册保存
		
		
		Route::get('managelist', 'ManageController@manageList');
		Route::get('manage/manageedit/{id}', 'ManageController@manageEdit');
		Route::Post('manage/managesave', 'ManageController@manageSave');
		Route::get('manage/manageadd', 'ManageController@manageAdd');
		Route::Post('manage/managecreate', 'ManageController@manageCreate');
		Route::Post('manage/managedelete', 'ManageController@manageDelete');		
		
		//医疗机构分类
		Route::get('orgclasslist', 'OrgController@orgClassList');
		Route::get('orgsecondclasslist/{id}', 'OrgController@orgSecondClassList');
		Route::get('orgsecondclass/classedit/{id}', 'OrgController@classEdit');
		Route::Post('orgsecondclass/classsave', 'OrgController@classSave');
		Route::get('orgsecondclass/classadd/{id}', 'OrgController@classAdd');
		Route::Post('orgsecondclass/classcreate', 'OrgController@classCreate');
		Route::get('orgsecondclass/classdelete/{id}', 'OrgController@classDelete');		
		
		//医疗机构
		Route::get('orglist/{id}', 'OrgController@orgList');
		Route::get('org/orgedit/{id}', 'OrgController@orgEdit');
		Route::Post('org/orgsave', 'OrgController@orgSave');
		Route::get('org/orgadd/{id}', 'OrgController@orgAdd');
		Route::Post('org/orgcreate', 'OrgController@orgCreate');
		Route::get('org/orgdelete/{id}', 'OrgController@orgDelete');	

		//用户咨询
		Route::get('jmsapplylist', 'JmsApplyController@jmsApplyList');
		Route::get('jmsapply/jmsapplyedit/{id}', 'JmsApplyController@jmsApplyEdit');
		Route::Post('jmsapply/jmsapplysave', 'JmsApplyController@jmsApplySave');

		//合作申请
		Route::get('hzapplylist', 'HzApplyController@hzApplyList');
		Route::get('hzapply/hzapplyedit/{id}', 'HzApplyController@hzApplyEdit');
		Route::Post('hzapply/hzapplysave', 'HzApplyController@hzApplySave');		

        //申请会员
        Route::get('memberapply/list', 'MemberApplyController@MemberApplyList');
        Route::get('memberapply/edit/{id}', 'MemberApplyController@MemberApplyEdit');
        Route::Post('memberapply/save', 'MemberApplyController@Save');

        //用户咨询
		Route::get('questionlist', 'UserQuestionController@questionList');
		Route::get('question/questionedit/{id}', 'UserQuestionController@questionEdit');
		Route::Post('question/questionsave', 'UserQuestionController@questionSave');		
		
		//健康咨询
        Route::get('newslist', 'NewsController@NewsList');//资讯列表
        Route::get('news/newadd', 'NewsController@Newadd');//添加资讯
        Route::post('news/store', 'NewsController@Store');//提交资讯
        Route::get('news/edit/{id}', 'NewsController@Edit');//编辑资讯
        Route::post('news/newsave', 'NewsController@Newsave');//编辑资讯保存
        Route::get('news/newdel/{id}', 'NewsController@Newdel');//删除资讯

        //健康福利
        //商品
        Route::get('goodslist', 'GoodsController@Goodslist');//商品列表
        Route::get('goods/goodsadd', 'GoodsController@Goodsadd');//添加商品
        Route::post('goods/store', 'GoodsController@Store');//提交商品
        Route::get('goods/edit/{id}', 'GoodsController@Edit');//编辑商品
        Route::post('goods/goodssave', 'GoodsController@Goodssave');//编辑商品保存
        Route::get('goods/goodsdel/{id}', 'GoodsController@Goodsdel');//删除商品
		//订单
        Route::get('orderlist', 'OrderController@Orderlist');// 订单列表
        Route::get('order/deliver/{id}', 'OrderController@Deliver');//发货
        Route::post('order/ordersave', 'OrderController@Ordersave');//发货提交
        Route::get('order/orderinfo/{id}', 'OrderController@Orderinfo');//订单详情
        Route::get('order/excel', 'OrderController@Excel');//导出excel
        

        //尊享服务
        Route::get('memberservice/{id}', 'MemberServiceController@serviceList');//服务列表
        Route::get('memberservice/serviceedit/{id}', 'MemberServiceController@serviceEdit');
        Route::Post('memberservice/servicesave', 'MemberServiceController@serviceSave');
        Route::get('memberservice/serviceadd/{id}', 'MemberServiceController@serviceAdd');
        Route::Post('memberservice/servicecreate', 'MemberServiceController@serviceCreate');
        Route::get('memberservice/servicedelete/{id}', 'MemberServiceController@serviceDelete');        
        Route::get('memberservice/optionlist/{id}', 'MemberServiceController@optionlist');//服务可选项列表
        Route::get('memberservice/optionadd/{id}', 'MemberServiceController@optionAdd');//添加可选项
        Route::Post('memberservice/optioncreate', 'MemberServiceController@optionCreate');//添加保存可选项
        Route::get('memberservice/optionedit/{id}', 'MemberServiceController@optionEdit');//编辑可选项
        Route::Post('memberservice/optionesave', 'MemberServiceController@optioneSave');//编辑保存可选项
        Route::get('memberservice/optionedelete/{id}', 'MemberServiceController@optioneDelete');//删除可选项
        Route::get('memberservice/hospitalcalss/{id}', 'MemberServiceController@hospitalCalss');//医院类别

        //需求订单
        Route::get('orders/orderslist', 'OrdersController@ordersList');//需求订单
        Route::get('orders/ordersinfo/{id}', 'OrdersController@ordersInfo');//需求详情
        Route::get('orders/feedback/{id}', 'OrdersController@Feedback');//反馈
        Route::post('orders/feedback_save', 'OrdersController@Feedback_save');//保存反馈
    });
	
	
	




