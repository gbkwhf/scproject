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
        Route::get('get/user/sign/package','WeixinInfoController@GetSingPackage');
        //3.获取公众号首页
        Route::get('get/official/accounts/index','WeixinInfoController@GetOfficalIndex');
        //4.判断该手机号码是否已经绑定了openId
        Route::post('scan/this/phone/bind/openId','WeixinInfoController@getBindState');




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

          //5.更改购物车：选中该商品的标志（选中/不选中）

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