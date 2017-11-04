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
    			'base_order_id' => 'required|string',  //主订单id
    			'filling_type' => 'required|in:1,3',    //支付方式 1微信 2线下支付 3微信js
                'open_id' => 'string'//公众号id
    			])
    			->_validate($request->all());
    	if (!$validator) return $this->setStatusCode(9999)->respondWithError($this->message);

        if($request->filling_type == 3){ //当支付方式为：微信js时候，open_id必填
            if(empty($request->open_id)){
                return $this->setStatusCode(9999)->respondWithError($this->message);
            }
        }

    	$user_id= $this->getUserIdBySession($request->ss); //获取用户id;
    	//查询订单
    	$order=\DB::table('ys_base_order')->where('id',$request->base_order_id)->where('user_id',$user_id)->first();
    	if(empty($order)){ //订单不存在
    		return $this->setStatusCode(6100)->respondWithError($this->message);
    	}
    	if($order->state == 1){ //订单已支付
    		return $this->setStatusCode(6101)->respondWithError($this->message);
    	} 
    	//支付方式
    	$filling_type=config('clinic-config.filling_type.'.$request->filling_type);
    	

    	$http=$_SERVER['SERVER_PORT'] == 443?'https://':'http://';

    	//吊起支付
    	$obj=new \Acme\Repository\UnitePay($filling_type,'goods_order');
    	if ($request->input("filling_type")==3){ //微信js（公众号支付）
    		$open_id=$request->input("open_id");
    		$wechatJsParam=["open_id"=>$open_id];
    	}elseif($request->input("filling_type")==4){ //网站支付宝支付
    		$wechatJsParam=['return_url'=>$http.$_SERVER['HTTP_HOST'].'/website/personal_center.php?queue=4'];
    	}else{
    		$wechatJsParam=[];
    	}
    	//支付
    	$response=$obj->purchase($request->base_order_id,config('clinic-config.default_service_name'),$order->require_amount,$wechatJsParam);
    	//失败提示
    	if($response===false)  return $this->setStatusCode(9998)->respondWithError($this->message);
    
    	\Log::info('the '.$filling_type.' url is', ['res'=>$response]);

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
