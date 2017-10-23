<?php

namespace App\Http\Controllers\Baseuser;
use Acme\Exceptions\ValidationErrorException;
use App\GoodsModel;

use App\Member;
use App\OrderModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
   //提交订单 aaa
    public function Order_sub(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'required|string',
            'goods_id' => 'required',
            'num' => 'required',
            'receive_name' => 'required',
            'receive_phone' => 'required',
            'receive_address' => 'required'
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id
        $user_info=\App\Member::where('user_id',$user_id)->first();
        $goods = GoodsModel::where('id',$request->goods_id)->where('state',1)->first();
        if ($goods){  //如果商品id存在
            if ($request->num > 0){
                if ($goods['num']>=$request->num){ //如果库存大于购买数量
                    $myprice =array(
                        '1'=> 'price',
                        '2'=> 'price_grade1',
                        '3'=> 'price_grade2',
                        '4'=> 'price_grade3',
                        '5'=> 'price_grade4',
                    );
                    $price = $goods->$myprice[$user_info->grade];//用户价格
                    $order_id = '1'.generatorOrderId(); //生成订单id
                    $params = array(
                        'id'=>$order_id,
                        'goods_id' => $request->goods_id,
                        'num' => $request->num,
                        'receive_name' => $request->receive_name,
                        'receive_phone' => $request->receive_phone,
                        'receive_address' => $request->receive_address,
                        'created_at' => date('Y-m-d H:i:s',time()),
                        'user_id' => $user_id,
                        'goods_price' => $price,
                        'amount'=>	$price*$request->num,
                    );
                    $res = OrderModel::insert($params);
                    if($res){
                        if($params['amount']>0){
                            $pay=1;
                        }else{
                            $pay=0;
                            \App\GoodsModel::where('id',$request->goods_id)->increment('sales',$request->num);
                            \App\GoodsModel::where('id',$request->goods_id)->decrement('num',$request->num);
                            $updateOrder=\App\OrderModel::where('id',$order_id)->update(array('state'=>1,'payment_at'=>date('Y-m-d H:i:s',time())));
                        }
                        return $this->respond($this->format(array('order_id'=>$order_id,'pay'=>$pay)));
                    }else{
                        $data['msg'] = '购买失败';
                        return $this->respond($this->format($data));
                        //return $this->setStatusCode(9998)->respondWithError($this->message);
                    }
                }else{
                    $data['msg'] = '购买过多，库存不足';
                    return $this->respond($data);
                }
            }else{
                $data['msg'] = '购买数量不能为0';
                return $this->respond($data);
            }
        }else{
            $data['msg'] = '商品不存在';
            return $this->respond($data);
        }

    }

    //订单列表
    public function order_list(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'required|string',
            'page' => 'string',
            'home' => 'string',
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id
        $start=$request->page<=1?0:(($request->page)-1)*10; //分页
        $express_name =array(
            '1'=>'顺丰速递',
            '2'=>'韵达快递',
            '3'=>'中通快递',
            '4'=>'申通速递',
            '5'=>'天天快递',
            '6'=>'宅急送',
        );
        $myprice = array(   //用商品价格重组数组
            '1' =>'price',
            '2' =>'price_grade1',
            '3' =>'price_grade2',
            '4' =>'price_grade3',
            '5' =>'price_grade4',
        );
        $member = Member::where('user_id',$user_id)->first();
        $grade = $member['grade'];
        $price = $myprice[$grade];
        if ($request->home){  //首页
            $start=$request->page<=1?0:(($request->page)-1)*10; //分页
            $res_order = OrderModel::select('id','goods_id','num','user_id','receive_name','receive_phone','receive_address','state','created_at','tracking_number','express','payment_at')
                ->where('user_id',$user_id)->where('state','>',0)->orderBy('created_at','desc')
                ->get();
            $res_info['list'] = OrderModel::join('stj_goods','stj_order.goods_id','=','stj_goods.id')
                ->select('stj_order.*','stj_goods.image','stj_goods.name','stj_goods.content','stj_goods.'.$price)
                ->where('stj_order.user_id',$user_id)->where('stj_order.state','>',0)->orderBy('stj_order.created_at','desc')
                ->skip($start)->take(10)->get();//订单列表/**/
            $res_info['count'] = count($res_order);

            for ($k=0;$k<count($res_info['list']);$k++){
                $goods_id = $res_info['list'][$k]['original']['goods_id'];      //产品id
                $goods_info = GoodsModel::where('id',$goods_id)->first();  //根据订单中的id，循环取出对应的产品信息

                $res_info['list'][$k]['price'] = $res_info['list'][$k]['goods_price'];
                if ($res_info['list'][$k]['original']['tracking_number'] == ""){
                    $tracking_number = "";
                }else{
                    $tracking_number = $res_info['list'][$k]['original']['tracking_number'];
                }
                $res_info['list'][$k]['tracking_number'] = $tracking_number;
                if ($res_info['list'][$k]['original']['express'] == ""){
                    $express = '';
                }else{
                    $express = $express_name[$res_info['list'][$k]['original']['express']];
                }
                $res_info['list'][$k]['express'] = $express;
                $res_info['list'][$k]['payment_at'] = $res_info['list'][$k]['original']['payment_at'];
            }
        }else{
            $res_info = OrderModel::join('stj_goods','stj_order.goods_id','=','stj_goods.id')
                ->select('stj_order.*','stj_goods.image','stj_goods.name','stj_goods.content','stj_goods.'.$price)
                ->where('stj_order.user_id',$user_id)->where('stj_order.state','>',0)->orderBy('stj_order.created_at','desc')
                ->skip($start)->take(10)->get();//订单列表/**/
            for ($k=0;$k<count($res_info);$k++){
                $goods_id = $res_info[$k]['goods_id'];      //产品id
                $goods_info = GoodsModel::where('id',$goods_id)->first();  //根据订单中的id，循环取出对应的产品信息
                $res_info[$k]['price'] = $res_info[$k]['goods_price'];
                if ($res_info[$k]['original']['tracking_number'] == ""){
                    $tracking_number = "";
                }else{
                    $tracking_number = $res_info[$k]['original']['tracking_number'];
                }
                $res_info[$k]['tracking_number'] = $tracking_number;
                if ($res_info[$k]['original']['express'] == ""){
                    $express = '';
                }else{
                    $express = $express_name[$res_info[$k]['original']['express']];
                }
                $res_info[$k]['express'] = $express;
                $res_info[$k]['payment_at'] = $res_info[$k]['original']['payment_at'];
            }
        }

        return $this->respond($this->format($res_info));
    }
}
