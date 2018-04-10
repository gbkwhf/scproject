<?php

return [
    // 默认支付网关
    'default' => 'alipay',

    'gateways' => [

        /**
         * APP支付宝支付配置
         */
        'alipay' => [
            'driver' => 'Alipay_MobileExpress',
            'options' => [
                'partner' => '2088521379452343',
                'key'=>'rdmyx36yo1833qnpar2jl8bq421z3u58',
                'alipayPublicKey'=>'-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnxj/9qwVfgoUh/y2W89L6BkRA
FljhNhgPdyPuBV64bfQNN1PjbCzkIM6qRdKBoLPXmKKMiFYnkd6rAoprih3/PrQE
B/VsW8OoM8fxn67UDYuyBTqA23MML9q1+ilIZwBC2AQ2UBVOrFXfFl75p6/B5Ksi
NG9zpgmLCUYuLkxpLQIDAQAB
-----END PUBLIC KEY-----',
                'privateKey'=>'-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQCp53N9rijq98nVXJ6+iMkjXAiw+NOXaGwwV4oCa5bKMObpg4b9
Rg1eOxIVj9cp6pBG+RvIrjyhaxebWt68MXU7/oH7t/W/w1VVNv6mWIm782jMWrD1
u2VAq5PbA8FTP6MzJ8JZaTteegjBjr16FBHl1zoLO84KELW7Q8/P2VQ3oQIDAQAB
AoGBAItDcYC0vklKbZ97WYrnZ6aaC22zpwvVxcgiGLc/JMv6JWuGKaF0SATS+lG+
IyjyYOwrx8BIQN42f5UReLxc9a+uqLqXGLH4GCpLkYJi+wydXQIkCarZH1+BSnV1
hTDwQ3tO9ETGOOKLGk8UCBKs3PsQKwesd36Ar4Qh4j3DMcYBAkEA3kTFKjsYQivI
SYn76ZLDTovuMGAm/A3R0RZXyc095O39EzlYbvZXsTmR0cHkgRbNX3Rs7jvHkeuN
klEq+aQLEQJBAMOwTAwlRA+p2U/z4TAs7fxwD7abbHAz1K6n/WXrBFppDv2OrRwZ
Aexsbql1DoflyyeEsTS1T6NVHwmBKXc8w5ECQGVGBmQwmuQDmPXQ9kQ9x9mxLm1C
UmEEVvai7IsZhjsaKPaqaV3dxqUMqyFc7CXZYug8XynJeSxGnLHvY9ptYVECQCGb
SkQftkOWYMq5UGgMn0ZmHFy47nOKgzEHip4RMWsxdbCX6yTTKqBmss6JWth931jH
EgPCS436VV01gGCI3/ECQGc2GFAcBkRLXeyztWTskj82HaDkHI2pLQ9w6lpdJCG0
9rEGzdM6cNMQkz3l8xLM/oixygo+dhiI4BY58vxCOLQ=
-----END RSA PRIVATE KEY-----'
            ]
        ],
        /**
         * 网站支付宝支付配置
         */
        'alipay_web' => [
	        'driver' => 'Alipay_Express',
	        'options' => [
                'partner' => '2088521379452343',
                'key'=>'rdmyx36yo1833qnpar2jl8bq421z3u58',                
				'SellerEmail'=>'santeja8688@santeja.cn',
				'ReturnUrl'=>'',		
            ]
        		],
        /**
         * APP微信支付配置
         */
        'wechatpay' => [
            'driver' => 'WechatPay_App',
            'options' => [
                'appId' => 'wx699dfab276feeddb',
                'mchId' =>'1420660902',
                'apiKey' => '3UTW5BlTngsfswchveswacIHHtA3mb4v',
                'tradeType'=>'APP'
            ]
        ],
        /**
         * 微信公众号支付配置
         */
        'wechatpay_js' => [
	        'driver' => 'WechatPay_Js',
	        'options' => [
	        'appId' => 'wx39e732f7f323536f',
	        'mchId' =>'1501765761',
	        'apiKey' => '1a492c226370e4136af4ca35e3cefe03',
	        ]
        ],  
        /**
         * 微信网站支付配置
         */
        'wechatpay_web' => [
	        'driver' => 'WechatPay_Native',
	        'options' => [
		        'appId' => 'wxfd67f896c1314a12',
		        'mchId' =>'1416340802',
		        'apiKey' => 'kFSDB1TtBE0JgNnwdHXlSA603t4mi4PW',
		        //'tradeType'=>'APP'
	        ]
        ]        
        
        
        
    ]

];