<?php

namespace App\Http\Controllers\Baseuser;

use App\AddedServiceModel;
use App\AddedServiceOptionModel;
use App\HospitalClassModel;
use App\HospitalModel;
use App\Member;
use App\OrderModel;
use App\OrdersModel;
use App\Session;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ServiceOrderController extends Controller
{
    //服务列表
    public function Order_list(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'string',
            'type'=>'required'
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        /*********************************************************/
        /*1，通过当前类型，获取到类型下的所有服务id，写入数组；
          2，再通过服务id的数组，去查询服务可选项，在查询出的可选项中获取到服务id；
          3，这样的做法是为了，屏蔽没有可选项的服务*/
        /*********************************************************/
        $service = AddedServiceModel::select('id')->where('type',$request->type)->get()->toArray();
       for ($k=0;$k<count($service);$k++){
           $id[] = $service[$k]['id'];
       }
       $service_id=array();
        if (count($service) != 0){//当类别下有服务项时
            $serviceoption = AddedServiceOptionModel::whereIn('added_id',$id)->get()->toArray();
            for ($t=0;$t<count($serviceoption);$t++){
                if ($serviceoption[$t]['id']){
                    $service_id[] =  $serviceoption[$t]['added_id'];
                }
            }
        }else{//当类别下没有服务项时,返回空数组
            $params['list']=array( );
        }

        if ($request->type == 3 ){
            $added_id = $service['0'];//如果类型为产业医生
        }else{
            $added_id = array_unique($service_id);
        }

        $id = array(//挂号与住院为固定id，单独写入数组
            '0'=>1,
            '1'=>2
        );
        if ($request->type == 7){//当类型为7时，将两个数组合并
            $added_id = array_merge($id,$added_id);
        }

        $res = AddedServiceModel::whereIn('id',$added_id)
            ->orderBy('sort','asc')
            ->orderBy('created_at','desc')->get();
        $url='http://'.$_SERVER['HTTP_HOST'].'/storage/upload/';

        $request->type == '3'?$type='2':$type='1';
        if ($type == '2'){//当前类型为产业医生时，$type=2为展示类型
            $params['list'][]=array(
                'content'=>formatContent($res[0]['original']['content']),
                'open_type'=>$res[0]['original']['open_type'],
            );
            $params['type']=$type;
        }else{//其余的为列表类型
           for ($i=0;$i<count($res);$i++){
               $params['list'][]=array(
                   'content'=>formatContent($res[$i]['original']['content']),
                   'created_at'=>$res[$i]['original']['created_at'],
                   'id'=>$res[$i]['original']['id'],
                   'title'=>$res[$i]['original']['title'],
                   'open_type'=>$res[$i]['original']['open_type'],
               );
           }
            $params['type']=$type;
        }
        if($res){
            return $this->respond($this->format($params));
        }else{
            return $this->setStatusCode(9998)->respondWithError($this->message);
        }
    }

    //精准服务选择医院类型
    public function Hospital_class(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'string',
            'type'=>'required' //服务id 2：预约挂号 3：住院治疗
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $res = HospitalClassModel::where('type',$request->type)
            ->orderBy('id','ase')
            ->get();
        if($res){
            return $this->respond($this->format($res));
        }else{
            return $this->setStatusCode(9998)->respondWithError($this->message);
        }
    }

    //服务可选项列表
    public function Option_list(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'string',
            'sid'=>'required'
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id
        if (!empty($request->ss)){
            $session = Session::where('session',$request->ss)->first();
            if ($session){
                $mygrade = Member::where('user_id',$user_id)->first();
                $grade = $mygrade['grade']; //用户等级
            }else{
                $grade = 1;
            }
        }else{
            $grade = 1;
        }
        /*$mygrade = Member::where('user_id',$user_id)->first();
        $grade = $mygrade['grade']<=1?1:$mygrade['grade']; //用户等级*/

        $res_info = AddedServiceOptionModel::where('added_id',$request->sid)->get();//取出所有可选项
        $mycontent = AddedServiceModel::where('id',$request->sid)->first();//服务介绍
        $myprice =array(
            '1'=> 'price',
            '2'=> 'price_grade1',
            '3'=> 'price_grade2',
            '4'=> 'price_grade3',
            '5'=> 'price_grade4',
        );
        $res=array();
        $price = $myprice[$grade];//用户价格
        for ($i=0;$i<count($res_info);$i++){
            $res[] = array(
              'id'=>$res_info[$i]['id'],
              'added_id'=>$res_info[$i]['added_id'],
              'title'=>$res_info[$i]['title'],
              'created_at'=>$res_info[$i]['created_at'],
              'price'=>$res_info[$i][$price],
            );
        }
        //$res = array_merge($res,array(array('content'=> $mycontent->content)));//将服务简介写入数组
        $url='http://'.$_SERVER['HTTP_HOST'].'/storage/upload/';
        
        $new_data['oplist']=$res;
        $new_data['content']=formatContent($mycontent->content);
        
        
        if($new_data){
            return $this->respond($this->format($new_data));
        }else{
            return $this->setStatusCode(9998)->respondWithError($this->message);
        }
    }

    //提交服务需求
    public function Sub_order(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'required|string',
            'name'=>'required',
            'open_type'=>'required',//1,普通订单2,挂号和住院订单
            'mobile'=>'required',
            'option_id'=>'string',
            'service_content'=>'string',
            'time'=>'string',//open_type=2时
            'department_id'=>'string',//open_type=2时
            'hospital_id'=>'string',//open_type=2时
            'hospital_class'=>'string',//open_type=2时

        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $user_id = $this->getUserIdBySession($request->ss); // 获取用户id
        if ($request->open_type == 2){//精准订单
            $order_id = '2'.generatorOrderId(); //生成订单id
            $res_hospital_class = HospitalClassModel::where('id',$request->hospital_class)->first();
            $addedservice = AddedServiceModel::where('id',$res_hospital_class['type']-1)->first();
            $service_type = $addedservice['type'];//服务类型id
            $service_class = $res_hospital_class['id'];//精准订单没有可选项，这里写入服务id
            $params=array(
                'name'=>$request->name,
                'order_id'=>$order_id,
                'order_type'=>'1',
                'user_id'=>$user_id,
                'service_option'=>$service_class,
                'service_type'=>$service_type,
                'mobile'=>$request->mobile,
                'intend_time'=>$request->time,
                'department_id'=>$request->department_id,
                'hospital_id'=>$request->hospital_id,
                'service_content'=>$request->service_content,
                'created_at'=>date('Y-m-d H:i:s',time()),
            );
            $res = OrdersModel::insert($params);
            if($res){
	            	$updateOrder=\App\OrdersModel::where('order_id',$order_id)->update(array('pay_state'=>1,'payment_at'=>date('Y-m-d H:i:s',time())));            	 
                	return $this->respond($this->format(array('order_id'=>$order_id,'pay'=>0)));
            }else{
                $data['msg'] = '提交失败';
                return $this->respond($this->format($data));
            }
        }elseif($request->open_type == 1){ //普通订单
            $mygrade = Member::where('user_id',$user_id)->first();
            $grade = $mygrade['grade']; //用户等级
            $order_id = '2'.generatorOrderId(); //生成订单id
            $added_id = AddedServiceOptionModel::where('id',$request->option_id)->first();
            if ($added_id){//判断服务id是否存在
                $service_type = AddedServiceModel::where('id',$added_id['added_id'])->first();
                $myprice =array(
                    '1'=> 'price',
                    '2'=> 'price_grade1',
                    '3'=> 'price_grade2',
                    '4'=> 'price_grade3',
                    '5'=> 'price_grade4',
                );
                $price = $myprice[$grade];//用户价格
                $params=array(
                    'user_id'=>$user_id,
                    'order_id'=>$order_id,
                    'name'=>$request->name,
                    'mobile'=>$request->mobile,
                    'service_type'=>$service_type['type'],
                    'service_option'=>$request->option_id,
                    'amount'=>$added_id[$price],
                    //'intend_time'=>$request->time,
                    'service_content'=>$request->service_content,
                    'created_at'=>date('Y-m-d H:i:s',time()),
                );
                $res = OrdersModel::insert($params);
                if($res){
                	if($added_id[$price]>0){
                		$pay=1;
                	}else{
                		$pay=0;
                		$updateOrder=\App\OrdersModel::where('order_id',$order_id)->update(array('pay_state'=>1,'payment_at'=>date('Y-m-d H:i:s',time())));                		
                	}
                	return $this->respond($this->format(array('order_id'=>$order_id,'pay'=>$pay)));
                }else{
                    $data['msg'] = '提交失败';
                    return $this->respond($this->format($data));
                }
            }else {
                $data['msg'] = '商品id有误，商品不存在';
                return $this->respond($data);
            }
        }

    }
    //订单列表
    public function Orders_list(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'required|string',
            'home' => 'string',
            'page' => 'string',
        ])
            ->_validate($request->all());/**/
        if (!$validator) throw new ValidationErrorException;
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id
        $type =array(
            '1'=>'海外医疗',
            '2'=>'健康管理',
            '3'=>'产业医生',
            '4'=>'增值服务',
            '5'=>'个性化订制',
            '6'=>'保险经纪',
            '7'=>'医疗服务',
        );
        if ($request->home){ //首页
            $start = $request->page <= 1 ? 0 : (($request->page) - 1) * 10;//分页
            $orders_list = OrdersModel::select('order_id','created_at','service_type','state','amount','order_type','pay_state','service_option')->where('user_id',$user_id)->where('pay_state',1)->orderBy('created_at','desc')->get();
            $orders['list'] = OrdersModel::select('order_id','created_at','service_type','state','amount','order_type','pay_state','service_option')->where('user_id',$user_id)->where('pay_state',1)->orderBy('created_at','desc')->skip($start)->take(10)->get();
            $orders['count'] = count($orders_list);
            for ($i=0;$i<count($orders['list']);$i++){
                if ($orders['list'][$i]['order_type'] == 1){
                    $res_serviceid = AddedServiceModel::where('id',$orders['list'][$i]['service_option'])->first();
                    $service_name =$res_serviceid['title'];
                    $orders['list'][$i]['service_name'] = $service_name;
                }elseif ($orders['list'][$i]['order_type'] == 0){
                    $service_name = AddedServiceOptionModel::where('id',$orders['list'][$i]['service_option'])->first();
                    $service_name_ = AddedServiceModel::where('id',$service_name['added_id'])->first();
                    $orders['list'][$i]['service_name'] = $service_name_['title'];
                }
                $type_id = $orders['list'][$i]['service_type'];
                $service_type = $type[$type_id];
                $orders['list'][$i]['service_type'] = $service_type;//服务类型
                if ($orders['list'][$i]['amount'] !== '0'){  //订单状态 0未付款1未处理2已处理
                    if ($orders['list'][$i]['state'] == '0' && $orders['list'][$i]['pay_state'] == '0' ){
                        $orders['list'][$i]['order_state'] = '0';
                    }else if ($orders['list'][$i]['state'] == '0' && $orders['list'][$i]['pay_state'] == '1' ){
                        $orders['list'][$i]['order_state'] = '1';
                    }else if ($orders['list'][$i]['state'] =='1' && $orders['list'][$i]['pay_state'] == '1' ){
                        $orders['list'][$i]['order_state'] = '2';
                    }
                }else{
                    if ($orders['list'][$i]['state'] == '0'){
                        $orders['list'][$i]['order_state'] = '未处理';
                    }else{
                        $orders['list'][$i]['order_state'] = '已处理';
                    }
                }
                $orders['list'][$i]['service_name']=$service_type;
            }
        }else{
            $start = $request->page <= 1 ? 0 : (($request->page) - 1) * 10;//分页
            $orders = OrdersModel::select('order_id','created_at','service_type','state','amount','order_type','pay_state','service_option')->where('user_id',$user_id)->where('pay_state',1)->orderBy('created_at','desc')->skip($start)->take(10)->get();
            for ($i=0;$i<count($orders);$i++){
                if ($orders[$i]['order_type'] == 1){
                    $res_serviceid = AddedServiceModel::where('id',$orders[$i]['service_option'])->first();
                    $service_name =$res_serviceid['title'];
                    $orders[$i]['service_name'] = $service_name;
                }elseif ($orders[$i]['order_type'] == 0){
                    $service_name = AddedServiceOptionModel::where('id',$orders[$i]['service_option'])->first();
                    $service_name_ = AddedServiceModel::where('id',$service_name['added_id'])->first();
                    $orders[$i]['service_name'] = $service_name_['title'];
                }
                $type_id = $orders[$i]['service_type'];
                $service_type = $type[$type_id];
                $orders[$i]['service_type'] = $service_type;//服务类型
                if ($orders[$i]['amount'] !== '0'){  //订单状态 0未付款1未处理2已处理
                    if ($orders[$i]['state'] == '0' && $orders[$i]['pay_state'] == '0' ){
                        $orders[$i]['order_state'] = '0';
                    }else if ($orders[$i]['state'] == '0' && $orders[$i]['pay_state'] == '1' ){
                        $orders[$i]['order_state'] = '1';
                    }else if ($orders[$i]['state'] =='1' && $orders[$i]['pay_state'] == '1' ){
                        $orders[$i]['order_state'] = '2';
                    }
                }else{
                    if ($orders[$i]['state'] == '0'){
                        $orders[$i]['order_state'] = '未处理';
                    }else{
                        $orders[$i]['order_state'] = '已处理';
                    }
                }
                $orders[$i]['service_name']=$service_type;
            }
        }


        if($orders){
            return $this->respond($this->format($orders));
        }else{
            return $this->setStatusCode(9998)->respondWithError($this->message);
        }
    }

    //服务订单详情
    public function Order_info(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'required|string',
            'order_id'=>'required',
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $res = OrdersModel::where('order_id',$request->order_id)->first();
        $type =array(
            '1'=>'海外医疗',
            '2'=>'健康管理',
            '3'=>'产业医生',
            '4'=>'增值服务',
            '5'=>'个性化订制',
            '6'=>'保险经纪',
            '7'=>'医疗服务',
        );
        

        if($res->order_type==1){
        	$res_hospital_class = HospitalClassModel::where('id',$res['service_option'])->first();
        	$option_name = $res_hospital_class->name;//精准订单没有可选项，这里写入服务id
        	$service_type=$res_hospital_class->type==2?'预约挂号':'住院治疗';
        }else{

        	$option = AddedServiceOptionModel::where('id',$res['service_option'])->first();        	
        	$second_option = AddedServiceModel::where('id',$option['added_id'])->first();
        	$option_name=$option['title'];
        	$service_type=$second_option->title;
        }


        $res_user = User::where('id',$res['manage_id'])->first();
        if ($res['manage_id'] == 0){
            $manage_name = "";
        }else{
            $manage_name = $res_user['name'];
        }
        $params=array(
            'service_type'=>$service_type,//服务类型
            'created_at'=>$res['created_at'],//申请时间
            'intend_time'=>$res['intend_time'],//服务时间
            'service_content'=>$res['service_content'],//服务描述
            'service_option'=>$option_name,//服务可选项
            'manage_id'=>$res['manage_id'],//反馈人员
            'manage_name'=>$manage_name,//反馈人姓名
            'manage_content'=>$res['manage_content'],//反馈内容
            'manage_time'=>$res['manage_time'],//反馈时间

        );
        if($res){
            return $this->respond($this->format($params));
        }else{
            return $this->setStatusCode(9998)->respondWithError($this->message);
        }
    }
}
