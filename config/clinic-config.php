<?php
/**
 * 诊所业务配置文件
 */

return [
     //默认充值订单名
    'default_recharge_name'=>'充值',
    //默认购买服务名
    'default_service_name'=>'购买商品',
//    'filling_type'=>[
//        '1'=>'alipay', //支付宝支付
//        '2'=>'wechatpay', //微信支付
//        '3'=>'wechatpay_js',//微信公众号支付
//        '4'=>'alipay_web',//网站支付宝支付
//        '5'=>'wechatpay_web',//网站微信支付
//    ],

    'filling_type'=>[
        '1'=>'wechatpay', //微信支付
        '2'=>'xianxiapay',//线下支付
        '3'=>'wechatpay_js',//微信公众号支付
        '4'=>'alipay_web',//网站支付宝支付
        '5'=>'wechatpay_web',//网站微信支付
    ],
	//邀请返利和经销商返利的比例    
    'rate'=>[
    	'user_rate'=>0.5,
		'invice_rate'=>0.1,
		'agency_rate'=>0,
	],
	
	//邀请注册送礼品
	'invite_goods'=>[
		1=>'现金红包',
		2=>'时令土特产',
		3=>'爱菊面粉',
		4=>'爱菊大米',
		5=>'鸡蛋10枚',
		6=>'香油一瓶',
	]




];