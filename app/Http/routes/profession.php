<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/26
 * Time: 15:00
 */

Route::group([

    'prefix' => 'api/gxsc','namespace' => 'Weixin'//, 'middleware'=> ['check.session:ys_session_info','check.version']

],function (){


    /**
     * 微信公众号功能模块  (需要获取和生成这三个值：openId   access_token   apiTicket)
     */
        //1.获取openId
        Route::get('get/user/openId','JsApiPay@GetOpenid');
        //2.获取签名包
        Route::post('get/user/sign/package','WeixinInfoController@GetSingPackage');
        //3.获取公众号首页
        Route::get('get/official/accounts/index','WeixinInfoController@GetOfficalIndex');
        //4.判断该手机号码是否已经绑定了openId
        Route::post('scan/this/phone/bind/openId','WeixinInfoController@getBindState');
        //5.获取微信头像和姓名
        Route::post('get/user/weixin/info','WeixinInfoController@getOwnWeixinInfo');




});





Route::group([

    'prefix' => 'api/gxsc','namespace' => 'HandleProfession', 'middleware'=> ['check.session:ys_session_info']//,'check.version']

],function (){


    /**
     * 地址管理模块 登陆状态才可以访问
     */
        //1.添加收货地址
        Route::post('add/delivery/goods/address','AddressManageController@addGoodsAddress');
        //2.获取地址（分为获取所有地址和单条地址（单条主要用于修改用））
        Route::post('get/delivery/goods/address','AddressManageController@getGoodsAddressInfo');
        //3.编辑收货地址（主要处理编辑的表单数据）
        Route::post('edit/delivery/goods/address','AddressManageController@editGoodsAddress');
        //4.设置默认地址
        Route::post('handle/delivery/goods/default/address','AddressManageController@handleDefaultAddress');
        //5.删除收货地址
        Route::post('delete/delivery/goods/address','AddressManageController@deleteAddress');


     /**
      * 商品分类以及商品详情模块  登陆状态才可以访问
      */
         //4.增加商品评价（暂时不做）


      /**
       * 购物车模块  登陆状态才可以访问
       */
         //1.创建购物车，给购物车中添加商品信息
          Route::post('add/goods/car/commodity','CreateGoodsCarController@addGoodsCar');
         //2.更改购物车中某条商品的数量（1:加号   2:减号 ）
          Route::post('update/goods/car/commodity/number','CreateGoodsCarController@updateGoodsNumber');
         //3.获取购物车中商品信息
          Route::post('get/goods/car/commodity/info','CreateGoodsCarController@getGoodsCarInfo');
          //4.删除购物车中的商品
          Route::post('delete/goods/car/commodity','CreateGoodsCarController@deleteGoodsCar');
          //5.更改购物车：选中该商品的标志（选中/不选中）
          Route::post('update/goods/car/commodity/state','CreateGoodsCarController@updateGoodsCar');




       /**
        * 订单模块 ：创建订单和获取订单信息
        *
        * (1)用户自己创建订单，自己走线上支付流程                               ---->走线上支付
        * (2)用户自己创建订单，走线下支付流程，员工调接口确认该笔订单支付完成         ---->走线下支付
        * (3)员工替用户创建订单，需要传用户手机号码，然后直接下单支付一体，直接完成    ---->走线下支付
        *
        */
           //1.会员主动创建订单（1.直接购买   2，加入购物车购买） 注：这里暂时不支持直接购买  ---->走线上支付
           Route::post('user/create/commodity/order','CreateOrdersController@createOrders');
           //2.员工给会员创建订单  ---->走线下支付
           Route::post('employee/give/user/create/commodity/order','CreateOrdersController@employeeGivCreateOrders');
           //3.员工确认完成用户自己创建的订单（线下收钱，使得订单结束，变成已支付）
           Route::post('employee/ack/complete/user/commodity/order','CreateOrdersController@employeeAckCompleteOrder');
           //4.获取订单列表（已完成的--并且拆单成子订单格式）
           Route::post('get/commodity/order/info/list','CreateOrdersController@getOrderLists');
           //5.获取订单详情(根据子订单id获取订单详情---拆分后)
           Route::post('get/commodity/sub_order/info','CreateOrdersController@getSubOrderInfo');
           //6.获取订单详情（根据主订单id获取详情----拆分前）
           Route::post('get/commodity/base_order/info','CreateOrdersController@getBaseOrderInfo');


        /**
         * 个人中心模块
         */
             //1.获取我邀请的用户列表
             Route::post('get/invite/user/info/list','GetUserOwnInfoController@getInviteList');
             //2.获取我的物流信息
             //3.替用户提现（员工才具有该功能）
             Route::post('replace/user/withdraw/deposit','GetUserOwnInfoController@replaceUserMoney');
             //4.获取我的操作记录（流水） ----员工
             Route::post('get/own/operate/bills/list','GetUserOwnInfoController@getOwnOpeBill');
             //5.获取我的体现记录（流水） --->会员
             Route::post('get/user/withdraw/deposit/bills/list','GetUserOwnInfoController@getBillsList');
             //6.确认收款（会员）
             Route::post('user/ack/get/money','GetUserOwnInfoController@ackGetMoney');
             //7.根据用户手机号码获取余额
             Route::post('get/balance/by/mobile','GetUserOwnInfoController@getBalance');





});


/**
 * 商品分类以及商品详情模块   非登陆状态也可以访问
 */
    //1.获取商品二级分类
    Route::get('api/gxsc/get/commodity/secondary/classification/{first_id}','HandleProfession\GetShopsInfoController@getSecondClass');
    //2.根据二级分类id获取商品列表
    Route::get('api/gxsc/get/commodity/lists/{second_id}','HandleProfession\GetShopsInfoController@getCommodityLists');
    //3.根据商品id获取商品详情
    Route::get('api/gxsc/get/commodity/info/{goods_id}','HandleProfession\GetShopsInfoController@getCommodityInfo');


  //邀请分享
   Route::get('api/gxsc/invite/others/register','Weixin\JsApiPay@inviteRegister');