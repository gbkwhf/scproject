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
	
	//邀请注册送礼品
	'invite_goods'=>[
		1=>'现金红包',
		2=>'时令土特产',
		3=>'爱菊面粉',
		4=>'爱菊大米',
		5=>'鸡蛋10枚',
		6=>'香油一瓶',
	],

	'user_lvs'=>[
		1=>[
			'min'=>300,
			'max'=>4999,
			'month_min'=>300,
			'rate'=>0.08,
			],
		2=>[
			'min'=>5000,
			'max'=>14999,
			'month_min'=>988,
			'rate'=>0.06,
			],
		3=>[
			'min'=>15000,
			'max'=>49999,
			'month_min'=>3888,
			'rate'=>0.04,
			],
		4=>[
			'min'=>50000,
			'max'=>99999,
			'month_min'=>6888,
			'rate'=>0.03,
			],
		5=>[
			'min'=>100000,
			'max'=>999999999,
			'month_min'=>0,
			'rate'=>0.02,
			],
		],
	//会员等级押金
	'deposit'=>[
		1=>300,
		2=>1000,
		3=>5000,
		4=>10000,
		5=>20000,
	],
	//微信提现手续费
	'wei_cost'=>0.5

];