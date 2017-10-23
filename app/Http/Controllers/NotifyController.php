<?php

namespace App\Http\Controllers;

use App\OrderModel;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class NotifyController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function goodsNotify($type=null)
    {

        $object=new \Acme\Repository\UnitePay($type,'goods_order');

        $result=$object->completePurchase();

        \Log::info('the '.$type.' notify  request_params is',$_REQUEST);

        //验证失败
        if($result===false){
            \Log::info('the '.$type.' check sign  fail ');
            return 'fail';
        }
        //返回相关关订单信息
        if($result){

    		$order=\App\OrderModel::where('id',$result['order_id'])->first();
        	
            if($result['price']<$order->price){
                \Log::info('the '.$type.' maybe hack');
                exit;
            }
            DB::beginTransaction(); //开启事务
            //成功更新订单
            
            
            $updateOrder=\App\OrderModel::where('id',$result['order_id'])->update(array('state'=>1,'payment_at'=>date('Y-m-d H:i:s',time())));
			//商品销量+1
			//商品库存-1
			\App\GoodsModel::where('id',$order->goods_id)->increment('sales',$order['num']);
			\App\GoodsModel::where('id',$order->goods_id)->decrement('num',$order['num']);

            if ($updateOrder) {
                DB::commit();
                \Log::info('goodsorder_success');
                return $result['return_msg_ok'];
            } else {
                DB::rollBack();
                \Log::info('goodsorder_fail');
                return $result['return_msg _fail'];
            }

        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serviceNotify($type=null)
    {
    
    	$object=new \Acme\Repository\UnitePay($type,'service_order');
    
    	$result=$object->completePurchase();
    
    	\Log::info('the '.$type.' notify  request_params is',$_REQUEST);
    
    	//验证失败
    	if($result===false){
    		\Log::info('the '.$type.' check sign  fail ');
    		return 'fail';
    	}
    	//返回相关关订单信息
    	if($result){
    
    		$order=\App\OrdersModel::where('order_id',$result['order_id'])->first();
    		 
    		if($result['price']<$order->price){
    			\Log::info('the '.$type.' maybe hack');
    			exit;
    		}
    		DB::beginTransaction(); //开启事务
    		//成功更新订单
    
    
    		$updateOrder=\App\OrdersModel::where('order_id',$result['order_id'])->update(array('pay_state'=>1,'payment_at'=>date('Y-m-d H:i:s',time())));
    
    
    
    		if ($updateOrder) {
    			DB::commit();
    			\Log::info('serviceorder_success');
    			return $result['return_msg_ok'];
    		} else {
    			DB::rollBack();
    			\Log::info('serviceorder_fail');
    			return $result['return_msg _fail'];
    		}
    
    	}
    }


}
