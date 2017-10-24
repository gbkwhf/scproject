<?php
//测试部署正式服务器
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//测试服务器域名 http://apiv3-test.skyhospital.net/
//接口地址事例   http://apiv3-test.skyhospital.net/api/v1/user/register



//客户端服务订单清理 !!!!!!!!!!!!
//Route::get('clear/{id}',function($id){
//
//    \DB::beginTransaction();
//    try {
//
//        $booking_ids = \App\ClinicBooking::where('user_id',$id)->lists('id')->toArray();
//        $order_ids = \App\Order::where('user_id',$id)->lists('id')->toArray();
//
//        \App\ClinicBooking::destroy($booking_ids);
//        \App\Order::destroy($order_ids);
//
//        DB::commit();
//
//        return response()->json('用户ID为 '.$id. '订单删除成功.');
//
//    } catch (Exception $e){
//
//        DB::rollback();
//
//        return response()->json('用户ID为 '.$id. '订单删除失败.');
//
//    }
//});




Route::get('test111',function(){
	echo "222";
});