<?php

namespace App\Http\Controllers\BackManage;

use App\Member;
use App\OrderModel;
use App\OrdersModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    public function HomeList()
    {
    	//Auth::user()->uuid=12;
    	//dd(Auth::user());
//         $time = date('Y-m-d 00:00:00',time());//今天零点
//         $yesterday = date('Y-m-d 00:00:00',strtotime("$time-1 day"));//昨天零点
//         $monday = date('Y-m-d 00:00:00',(time()-((date('w')==0?7:date('w'))-1)*24*3600));//周一零点
//         $month = date('Y-m-d 00:00:00',strtotime(date('Y-m', time()).'-01 00:00:00'));//本月第一天零点
//         //所有会员sql
//         $res_member = Member::get();//所有会员总数
//         $res_member_day = Member::where('created_at','>=',$time)->get();//今天所有会员
//         $res_member_yesterday = Member::where('created_at','>=',$yesterday)->where('created_at','<',$time)->get();//昨天所有会员
//         $res_member_monday = Member::where('created_at','>=',$monday)->get();//本周所有会员
//         $res_member_month = Member::where('created_at','>=',$month)->get();//本月所有会员
//         //收费会员sql
//         $res_charge_member = Member::where('grade','!=','1')->get();//收费会员
//         $res_charge_member_day = Member::where('grade','!=','1')
//             ->where('created_at','>=',$time)->get();//今天收费会员
//         $res_charge_member_yesterday = Member::where('grade','!=','1')
//             ->where('created_at','>=',$yesterday)
//             ->where('created_at','<',$time)->get();//昨天收费会员
//         $res_charge_member_monday = Member::where('grade','!=','1')
//             ->where('created_at','>=',$monday)->get();//本周收费会员
//         $res_charge_member_month = Member::where('grade','!=','1')
//             ->where('created_at','>=',$month)->get();//本月收费会员
//         //健康商品订单sql
//         $res_order = OrderModel::get();//所有商品订单
//         $res_order_day = OrderModel::where('created_at','>=',$time)->get();//今天商品订单
//         $res_order_yesterday = OrderModel::where('created_at','>=',$yesterday)->where('created_at','<',$time)->get();//昨天商品订单
//         $res_order_monday = OrderModel::where('created_at','>=',$monday)->get();//本周商品订单
//         $res_order_month = OrderModel::where('created_at','>=',$month)->get();//本月商品订单
//         //需求订单sql
//         $res_orders = OrdersModel::get();//所有需求订单
//         $res_orders_day = OrdersModel::where('created_at','>=',$time)->get();//今天需求订单
//         $res_orders_yesterday = OrdersModel::where('created_at','>=',$yesterday)->where('created_at','<',$time)->get();//昨天需求订单
//         $res_orders_monday = OrdersModel::where('created_at','>=',$monday)->get();//本周需求订单
//         $res_orders_month = OrdersModel::where('created_at','>=',$month)->get();//本月需求订单

//         //所有会员
//         $member =count($res_member);//所有会员
//         $member_day = count($res_member_day);//今天所会员
//         $member_yesterday = count($res_member_yesterday);//昨天所会员
//         $member_monday = count($res_member_monday);//本周所会员
//         $member_month = count($res_member_month);//本月所会员
//         //收费会员
//         $charge_member = count($res_charge_member);//收费会员
//         $charge_member_day = count($res_charge_member_day);// 今天收费会员
//         $charge_member_yesterday = count($res_charge_member_yesterday);//昨天收费会员
//         $charge_member_monday = count($res_charge_member_monday);//本周收费会员
//         $charge_member_month = count($res_charge_member_month);//本月收费会员
//         //健康商品订单
//         $order = count($res_order);//健康商品订单
//         $order_day = count($res_order_day);//今天健康商品订单
//         $order_yesterday = count($res_order_yesterday);//昨天健康商品订单
//         $order_monday = count($res_order_monday);//本周健康商品订单
//         $order_month = count($res_order_month);//本月健康商品订单
//         //需求订单
//         $orders = count($res_orders);//需求订单
//         $orders_day = count($res_orders_day);//今天需求订单
//         $orders_yesterday = count($res_orders_yesterday);//昨天需求订单
//         $orders_monday = count($res_orders_monday);//本周需求订单
//         $orders_month = count($res_orders_month);//本月需求订单
//         $data =array(
//            'member'=> $member,
//            'member_day'=> $member_day,
//            'member_yesterday'=> $member_yesterday,
//            'member_monday'=> $member_monday,
//            'member_month'=> $member_month,
//            'charge_member'=> $charge_member,
//            'charge_member_day'=> $charge_member_day,
//            'charge_member_yesterday'=> $charge_member_yesterday,
//            'charge_member_monday'=> $charge_member_monday,
//            'charge_member_month'=> $charge_member_month,
//            'order'=> $order,
//            'order_day'=> $order_day,
//            'order_yesterday'=> $order_yesterday,
//            'order_monday'=> $order_monday,
//            'order_month'=> $order_month,
//            'orders'=> $orders,
//            'orders_day'=> $orders_day,
//            'orders_yesterday'=> $orders_yesterday,
//            'orders_monday'=> $orders_monday,
//            'orders_month'=> $orders_month,
//         );

        return view('home');
    }
    //经销商首页
    public function agencyIndex(){
    	return view('agencyindex');
    }
    //供应商首页
    public function supplierIndex(){    	
    	$data=\App\SupplierModel::where('id',\Session::get('role_userid'))->first();
    	return view('supplierindex',['data'=>$data]);
    }
}
