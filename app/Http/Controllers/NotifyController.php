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
        \Log::info(var_export($result,true));

        //验证失败
        if($result===false){
            \Log::info('the '.$type.' check sign  fail ');
            return 'fail';
        }
        //返回相关关订单信息
        if($result){

            $order=\DB::table('ys_base_order')->where('id',$result['order_id'])->first();

            $order_info = \DB::table('ys_base_order as a')
                            ->leftjoin('ys_sub_order as b','a.id','=','b.base_id')
                            ->leftjoin('ys_order_goods as c','b.id','=','c.sub_id')
                            ->leftjoin('ys_goods as d','c.goods_id','=','d.id')
                            ->leftjoin('ys_goods as e','c.ext_id','=','e.id')
                            ->select('a.require_amount','c.goods_id','c.num as buy_num','e.num as rest_num','d.sales')
                            ->where('a.id',$result['order_id'])
                            ->get();

            if(empty($order_info) || empty($order)){
                \Log::info('the order not exist');
                exit;
            }
            if($result['price']<$order->require_amount){
                \Log::info('the '.$type.' maybe hack');
                exit;
            }

            $types_define=array_flip(config('clinic-config.filling_type'));
            $base = [
                'pay_time'=>date("Y-m-d H:i:s"),
                'pay_type'=>$types_define[$type],//付款方式:1微信，2线下支付 3微信js
                'state' => 1,//订单状态0未付款，1，已付款
                'amount'=>$result['price'],//实际收到的总金额
            ];

            DB::beginTransaction(); //开启事务
            //更新主订单信息
            $update1 = \DB::table('ys_base_order')->where('id',$result['order_id'])->update($base);

            //更新商品销量和库存
            $update_arr = [];
            foreach($order_info as $k=>$v){

                if($v->rest_num > $v->buy_num){ //库存充足

                    $update = \DB::table('ys_goods')->where('id',$v->goods_id)->update([
                        'num'=>$v->rest_num - $v->buy_num,
                        'sales'=>$v->sales + $v->buy_num,
                        'updated_at'=>\DB::Raw('Now()')
                    ]);

                }else{//库存不足
                    $update = 0;
                    \Log::info('low stocks，goods_id is '.$v->goods_id);
                }

                array_push($update_arr,$update);
            }

            //下面这个循环的目的是为了保证使用循环更新时的每一项都成功
            foreach($update_arr as $k=>$v){
                if(!$v){
                    \DB::rollBack();
                    \Log::info('goodsorder_fail');
                    return $result['return_msg _fail'];
                }
            }

            /**
             * 最后提交事务，并且返回主订单id
             */
            if ($update1) {
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
