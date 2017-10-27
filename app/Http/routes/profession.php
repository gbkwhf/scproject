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
//    Route::post('get/user/openId','WeixinInfoController@getOpenId');
    Route::get('get/user/openId','JsApiPay@GetOpenid');




});





Route::group([

    'prefix' => 'api/gxsc','namespace' => 'HandleProfession', 'middleware'=> ['check.session:ys_session_info']//,'check.version']

],function (){


    /**
     * 地址管理模块
     */
        //1.添加收货地址
        Route::post('add/delivery/goods/address','AddressManageController@addGoodsAddress');
        //2.获取地址（分为获取所有地址和单条地址（单条主要用于修改用））
        Route::post('get/delivery/goods/address','AddressManageController@getGoodsAddressInfo');
        //3.编辑收货地址（主要处理编辑的表单数据）
        Route::post('edit/delivery/goods/address','AddressManageController@editGoodsAddress');
        //4.设置默认地址
        Route::post('handle/delivery/goods/default/address','AddressManageController@handleDefaultAddress');






});
