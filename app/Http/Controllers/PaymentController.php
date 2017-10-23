<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{

    /**
     * 支付商品订单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function payGoodsOrder(Request $request){

    	$validator = $this->setRules([
    			'ss' => 'required|string',
    			'order_id' => 'required|string',  //订单号
    			'filling_type' => 'required|in:1,2,3,4,5',    //支付方式 1支付宝2微信
    			])
    			->_validate($request->all());
    	if (!$validator) throw new ValidationErrorException;
    	$user_id=\App\Session::where('session',$request->ss)->first()->user_id;
    	//查询订单
    	$order=\App\OrderModel::where('id',$request->order_id)->where('user_id',$user_id)->first();
    	if(empty($order)){ //订单不存在
    		return $this->setStatusCode(6100)->respondWithError($this->message);
    	}
    	if($order->state!=0){
    		return $this->setStatusCode(6101)->respondWithError($this->message);
    	} 
    	//支付方式
    	$filling_type=config('clinic-config.filling_type.'.$request->filling_type);
    	

    	 

//     	//测试支付成功
//     	$updateOrder=\App\OrderModel::where('id',$request->order_id)->update(array('payment_by'=>$request->filling_type,'state'=>1,'payment_at'=>date('Y-m-d H:i:s',time())));
//     	//商品销量+1
//     	//商品库存-1
//     	\App\GoodsModel::where('id',$order->goods_id)->increment('sales',$order->num);
//     	\App\GoodsModel::where('id',$order->goods_id)->decrement('num',$order->num);
//     	return $this->respond($this->format('',true));
//     	exit;

    	 
    	$http=$_SERVER['SERVER_PORT'] == 443?'https://':'http://';

    	//吊起支付
    	$obj=new \Acme\Repository\UnitePay($filling_type,'goods_order');
    	if ($request->input("filling_type")==3){
    		$open_id=$request->input("open_id");
    		$wechatJsParam=["open_id"=>$open_id];
    	}elseif($request->input("filling_type")==4){
    		$wechatJsParam=['return_url'=>$http.$_SERVER['HTTP_HOST'].'/website/personal_center.php?queue=4'];
    	}else{
    		$wechatJsParam=[];
    	}
    	//支付
    	$response=$obj->purchase($request->order_id,config('clinic-config.default_service_name'),$order->amount,$wechatJsParam);

    	//失败提示
    	if($response===false)  return $this->setStatusCode(9998)->respondWithError($this->message);
    
    	\Log::info('the '.$filling_type.' url is', ['res'=>$response]);
    	

    
    	//成功更新订单
    	$order=\App\OrderModel::where('id',$request->order_id)->update(array('payment_by'=>$request->filling_type));
    	 
    
    	return $this->respond($this->format($response));
    
    
    }    

    /**
     * 支付服务订单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function payServiceOrder(Request $request){

    	$validator = $this->setRules([
    			'ss' => 'required|string',
    			'order_id' => 'required|string',  //订单号
    			'filling_type' => 'required|in:1,2,3,4,5',    //支付方式 1支付宝2微信
    			])
    			->_validate($request->all());
    	if (!$validator) throw new ValidationErrorException;
    	$user_id=\App\Session::where('session',$request->ss)->first()->user_id;
    	//查询订单
    	$order=\App\OrdersModel::where('order_id',$request->order_id)->where('user_id',$user_id)->first();
    	if(empty($order)){ //订单不存在
    		return $this->setStatusCode(6100)->respondWithError($this->message);
    	}
    	if($order->pay_state!=0){
    		return $this->setStatusCode(6101)->respondWithError($this->message);
    	}
    	
//     	//测试支付成功    	 
//     	$updateOrder=\App\OrdersModel::where('order_id',$request->order_id)->update(array('payment_by'=>$request->filling_type,'pay_state'=>1,'payment_at'=>date('Y-m-d H:i:s',time())));    	 
//     	return $this->respond($this->format('',true));
//     	exit;
    	
    	$http=$_SERVER['SERVER_PORT'] == 443?'https://':'http://';
    	
    	//支付方式
    	$filling_type=config('clinic-config.filling_type.'.$request->filling_type);
    	//吊起支付
    	$obj=new \Acme\Repository\UnitePay($filling_type,'service_order');
    	if ($request->input("filling_type")==3){
    		$open_id=$request->input("open_id");
    		$wechatJsParam=["open_id"=>$open_id];
    	}elseif($request->input("filling_type")==4){
    		$wechatJsParam=['return_url'=>$http.$_SERVER['HTTP_HOST'].'/website/personal_center.php?queue=2'];
    	}else{
    		$wechatJsParam=[];
    	}
    	//支付
    	$response=$obj->purchase($request->order_id,config('clinic-config.default_service_name'),$order->amount,$wechatJsParam);
    	
    	//失败提示
    	if($response===false)  return $this->setStatusCode(9998)->respondWithError($this->message);
    	
    	\Log::info('the '.$filling_type.' url is', ['res'=>$response]);
    	
    	//成功更新订单
    	$order=\App\OrdersModel::where('order_id',$request->order_id)->update(array('payment_by'=>$request->filling_type));
    	
    	
    	return $this->respond($this->format($response));
    	
    	
    }
    //获取订单状态
    public function getOrderState(Request $request){
    	//tijiao
    	$type=substr($request->order_id,0,1);
		if($type==2){//服务订单
			$order_info=\App\OrdersModel::where('order_id',$request->order_id)->first();
			$state=$order_info->pay_state<1?0:1;
		}elseif ($type==1){//商品订单
			$order_info=\App\OrderModel::where('id',$request->order_id)->first();
			$state=$order_info->state<1?0:1;
		}
    	
		return $this->respond($this->format($state));
		
    }
}
