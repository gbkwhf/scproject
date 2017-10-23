<?php

namespace App\Http\Controllers\BackManage;

use App\AddedServiceModel;
use App\AddedServiceOptionModel;
use App\HospitalClassModel;
use App\HospitalModel;
use App\Member;
use App\MessageModel;
use App\OrderModel;
use App\OrdersModel;
use App\RecollectionCodeModel;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
class OrdersController extends Controller
{
    //需求订单列表
    public function ordersList(Request $request)
    {

        $res = OrdersModel::select('user_id','pay_state','state','order_id','created_at','service_type','service_option','order_type','name','mobile');
        if ($request->name){
            $res->where('name','like','%'.$request->name.'%');
        }
        if($request->id){
            $res->where('order_id','like','%'.$request->id.'%');
        }
        if ($request->state_type != ""){//订单状态
            if ($request->state_type == 0){
                $res->where('order_type',0)->where('pay_state',0);
            }elseif ($request->state_type == 1){
                $res->where('state',0);
            }elseif ($request->state_type == 2){
                $res->where('state',1);
            }
        }
        if ($request->type_order != ""){//订单类型
            if ($request->type_order == 0){
                $res->where('order_type',0);
            }else{
                $res->where('order_type',1);
            }
        }
        if ($request->grade !=""){ //通过会员等级搜索订单
            $orders = OrdersModel::get();
            for($k=0;$k<count($orders);$k++){ //取出所有订单的会员等级重组数组，订单id为键，等级id为值
                $user_id = Member::where('user_id',$orders[$k]['user_id'])->first();
                $grade[$orders[$k]['order_id']]  = $user_id['grade'];
            }
            $a= array_keys($grade,$request->grade); //取出值等于获取到的等级id，并将这些值的键重组数组
            $res->whereIn('order_id',$a);
        }
        $a =$res->orderBy('created_at','desc')->paginate(10);
        $type =array(
            '1'=>'海外医疗',
            '2'=>'健康管理',
            '3'=>'产业医生',
            '4'=>'增值服务',
            '5'=>'个性化订制',
            '6'=>'保险经纪',
            '7'=>'医疗服务',
        );
        $grades= array(
            '1'=>'普通会员',
            '2'=>'红卡会员',
            '3'=>'金卡会员',
            '4'=>'白金卡会员',
            '5'=>'黑卡会员',
        );
        for ($i=0;$i<count($a);$i++){
            $res_member = Member::where('user_id',$a[$i]['user_id'])->first();
            $grade_id = $res_member['grade'];
            $a[$i]['grade'] = $grades[$grade_id];//用户等级

            $a[$i]['service_type'] =$type[$a[$i]['service_type']];
            if ($a[$i]['order_type']==1){//精准服务类型
                //$AddedService = AddedServiceModel::where('id',$a[$i]['service_option'])->first();
                $hospitalclass = HospitalClassModel::where('id',$a[$i]['service_option'])->first();
                $a[$i]['service_option'] = $hospitalclass['name'];
            }else{//普通服务类型
                $res_addedserviceoption = AddedServiceOptionModel::where('id',$a[$i]['service_option'])->first();
                $service_id = $res_addedserviceoption['added_id'];
                $AddedService = AddedServiceModel::where('id',$service_id)->first();
                $a[$i]['service_option'] = $AddedService['title'];
            }
            if ($a[$i]['order_type'] == 1){
                $a[$i]['order_type'] = '精准订单';
                if ($a[$i]['state'] == 0 ){
                    $a[$i]['type'] = 0; //未反馈
                }elseif ($a[$i]['state'] == 1){
                    $a[$i]['type'] = 1; //已反馈
                }
            }else{
                $a[$i]['order_type'] = '普通订单';
                if ($a[$i]['state'] == 0 && $a[$i]['pay_state'] == 0){
                    $a[$i]['type'] = 2; //未付款
                }elseif ($a[$i]['state'] == 0 && $a[$i]['pay_state'] == 1){
                    $a[$i]['type'] = 0; //未反馈
                }elseif ($a[$i]['state'] == 1 && $a[$i]['pay_state'] == 1){
                    $a[$i]['type'] = 1; //已反馈
                }
            }
        }
        return view('orders/orderslist',['data'=>$a]);
    }
    //订单详情
    public function ordersInfo($id)
    {
        $type =array(
            '1'=>'海外医疗',
            '2'=>'健康管理',
            '3'=>'产业医生',
            '4'=>'增值服务',
            '5'=>'个性化订制',
            '6'=>'保险经纪',
            '7'=>'医疗服务',
        );
        $res = OrdersModel::where('order_id',$id)->first();
        $recollection_code = RecollectionCodeModel::where('id',$res['department_id'])->first();
        $department_name = $recollection_code['name'];
        $res['department_id'] = $department_name;
        $res['service_type'] = $type[$res['service_type']];
        $hospital = HospitalModel::where('id',$res['hospital_id'])->first();
        $res['hospital_id'] = $hospital['name'];
        if ($res['amount'] == 0){
            if ($res['state'] == 0 ){
                $res['type'] = '未处理';
            }else{
                $res['type'] = '已处理';
            }
        }else{
            if ($res['state'] == 0 || $res['pay_state'] == 0){
                $res['type'] = '未付款';
            }elseif ($res['state'] == 1 || $res['pay_state'] == 0){
                $res['type'] = '未处理';
            }elseif ($res['state'] == 1 || $res['pay_state'] == 1){
                $res['type'] = '已处理';
            }
        }
        if ($res['order_type']==1){
            //$addedaervice = AddedServiceModel::where('id',$res['service_option'])->first();
            $hospitalclass = HospitalClassModel::where('id',$res['service_option'])->first();
            $res['service_option'] = $hospitalclass['name'];
        }else{
            $addedserviceoption = AddedServiceOptionModel::where('id',$res['service_option'])->first();
            $addedaervice = AddedServiceModel::where('id',$addedserviceoption['added_id'])->first();
            $res['service_option'] = $addedaervice['title'];
            $res['option'] = $addedserviceoption['title'];
        }
        if ($res['order_type']==1){
            $res['order_type']='精准订单';
        }else{
            $res['order_type']='普通订单';
        }
        if ($res['manage_id']==0){
            $res['manage_id']="";
        }else{
            $manage_id = User::where('id',$res['manage_id'])->first();
            $res['manage_id'] = $manage_id['name'];
        }
        if ($res['manage_time']==0){$res['manage_time']="";}
        return view('orders/ordersinfo',['data'=>$res]);

    }

    //反馈
    public function Feedback($id)
    {
        $type =array(
            '1'=>'海外医疗',
            '2'=>'健康管理',
            '3'=>'产业医生',
            '4'=>'增值服务',
            '5'=>'个性化订制',
            '6'=>'保险经纪',
            '7'=>'医疗服务',
        );
       $res = OrdersModel::where('order_id',$id)->first();
        $res['service_type'] = $type[$res['service_type']];
        if ($res['order_type']==1){
            $hospitalclass = HospitalClassModel::where('id',$res['service_option'])->first();
            $res['service_option'] = $hospitalclass['name'];
        }else{
            $addedserviceoption = AddedServiceOptionModel::where('id',$res['service_option'])->first();
            $addedaervice = AddedServiceModel::where('id',$addedserviceoption['added_id'])->first();
            $res['service_option'] = $addedaervice['title'];
            $res['option'] = $addedserviceoption['title'];
        }

        if ($res['order_type'] == 1){
            $res['order_type'] ='精准订单';
        }else{
            $res['order_type'] ='普通订单';
        }
        $recollectioncode = RecollectionCodeModel::select('name')->where('id',$res['department_id'])->first();
        $res['department_id'] = $recollectioncode['name'];

        $hospital = HospitalModel::where('id',$res['hospital_id'])->first();
        $res['hospital_id'] = $hospital['name'];
        return view('orders/ordersfeedback',['data'=>$res]);
    }
    //保存反馈
    public function Feedback_save(Request $request)
    {
        $input = Input::except('_token');
        $rules = [
            'manage_content'=> 'required',
        ];
        $massage = [
            'manage_content.required' =>'反馈内容不能为空',
        ];
        $validator = \Validator::make($input,$rules,$massage);

        if($validator->passes()) {
            $res_user = User::where('name',$request->name)->first();
            $params=array(
               'manage_id' =>$res_user['id'],
               'manage_content' =>$request->manage_content,
               'manage_time' =>date('Y-m-d H:i:s',time()),
                'state'=>'1'
            );
            $res = OrdersModel::where('order_id',$request->id)->update($params);
            $orders = OrdersModel::where('order_id',$request->id)->first();
            $user_id = $orders['user_id'];
            $date = array(
                'user_id'=>$user_id,
                'created_at'=>date('Y-m-d H:i:s',time()),
                'content'=>'您的服务订单已反馈',/**/
            );
            MessageModel::insert($date);
            if($res === false){
                return back() -> with('errors','数据填充失败');

            }else{
                return redirect('orders/orderslist');
            }
        }else{
            return back() -> withErrors($validator);
        }
    }
}
