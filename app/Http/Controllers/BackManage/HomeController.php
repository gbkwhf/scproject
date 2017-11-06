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

        $time = date('Y-m-d 00:00:00',time());//今天零点
        $yesterday = date('Y-m-d 00:00:00',strtotime("$time-1 day"));//昨天零点
        $monday = date('Y-m-d 00:00:00',(time()-((date('w')==0?7:date('w'))-1)*24*3600));//周一零点
        $month = date('Y-m-d 00:00:00',strtotime(date('Y-m', time()).'-01 00:00:00'));//本月第一天零点
        //所有会员sql
        $member = \App\MemberModel::count();//所有会员总数
        $member_day = \App\MemberModel::where('created_at','>=',$time)->count();//今天所有会员
        $member_yesterday = \App\MemberModel::where('created_at','>=',$yesterday)->where('created_at','<',$time)->count();//昨天所有会员
        $member_monday = \App\MemberModel::where('created_at','>=',$monday)->count();//本周所有会员
        $member_month = \App\MemberModel::where('created_at','>=',$month)->count();//本月所有会员

        //订单
        $order = \App\BaseOrderModel::where('state',1)->count();//所有商品订单
        $order_day = \App\BaseOrderModel::where('state',1)->where('pay_time','>=',$time)->count();//今天商品订单
        $order_yesterday = \App\BaseOrderModel::where('state',1)->where('pay_time','>=',$yesterday)->where('pay_time','<',$time)->count();//昨天商品订单
        $order_monday = \App\BaseOrderModel::where('state',1)->where('pay_time','>=',$monday)->count();//本周商品订单
        $order_month = \App\BaseOrderModel::where('state',1)->where('pay_time','>=',$month)->count();//本月商品订单



        $data =array(
           'member'=> $member,
           'member_day'=> $member_day,
           'member_yesterday'=> $member_yesterday,
           'member_monday'=> $member_monday,
           'member_month'=> $member_month,
           'order'=> $order,
           'order_day'=> $order_day,
           'order_yesterday'=> $order_yesterday,
           'order_monday'=> $order_monday,
           'order_month'=> $order_month,
        );

        return view('home',['data'=>$data]);
    }
    //经销商首页
    public function agencyIndex(){
    	$agency_id=\Session::get('role_userid');
    	$data=\App\AgencyModel::where('id',$agency_id)->first();    	 
    	
    	
    	
    	
    	$time = date('Y-m-d 00:00:00',time());//今天零点
    	$yesterday = date('Y-m-d 00:00:00',strtotime("$time-1 day"));//昨天零点
    	$monday = date('Y-m-d 00:00:00',(time()-((date('w')==0?7:date('w'))-1)*24*3600));//周一零点
    	$month = date('Y-m-d 00:00:00',strtotime(date('Y-m', time()).'-01 00:00:00'));//本月第一天零点
    	 
    	//订单

    	$order=\App\BaseOrderModel::where('ys_employee.agency_id','=',$agency_id)
    				->leftjoin('ys_employee','ys_employee.user_id','=','ys_base_order.employee_id')
    				->where('ys_base_order.state','=',1)
    				->count();
    	$order_day=\App\BaseOrderModel::where('ys_employee.agency_id','=',$agency_id)
			    	->leftjoin('ys_employee','ys_employee.user_id','=','ys_base_order.employee_id')
			    	->where('pay_time','>=',$time)
			    	->where('ys_base_order.state','=',1)
			    	->count();
    	$order_yesterday=\App\BaseOrderModel::where('ys_employee.agency_id','=',$agency_id)
			    	->leftjoin('ys_employee','ys_employee.user_id','=','ys_base_order.employee_id')
			    	->where('pay_time','>=',$yesterday)->where('pay_time','<',$time)
			    	->where('ys_base_order.state','=',1)
			    	->count();
    	$order_monday=\App\BaseOrderModel::where('ys_employee.agency_id','=',$agency_id)
			    	->leftjoin('ys_employee','ys_employee.user_id','=','ys_base_order.employee_id')
			    	->where('pay_time','>=',$monday)
			    	->where('ys_base_order.state','=',1)
			    	->count();
    	$order_month=\App\BaseOrderModel::where('ys_employee.agency_id','=',$agency_id)
			    	->leftjoin('ys_employee','ys_employee.user_id','=','ys_base_order.employee_id')
			    	->where('ys_base_order.state','=',1)
			    	->where('pay_time','>=',$month)
			    	->count();    	    	    	
    	

    	$order_num =array(
    			'order'=> $order,
    			'order_day'=> $order_day,
    			'order_yesterday'=> $order_yesterday,
    			'order_monday'=> $order_monday,
    			'order_month'=> $order_month,
    	);
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	return view('agencyindex',['data'=>$data,'order_num'=>$order_num]);
    }
    //供应商首页
    public function supplierIndex(){    
    	$supplier_id=\Session::get('role_userid');
    	$data=\App\SupplierModel::where('id',$supplier_id)->first();
    	
    	
    	
    	
    	$time = date('Y-m-d 00:00:00',time());//今天零点
    	$yesterday = date('Y-m-d 00:00:00',strtotime("$time-1 day"));//昨天零点
    	$monday = date('Y-m-d 00:00:00',(time()-((date('w')==0?7:date('w'))-1)*24*3600));//周一零点
    	$month = date('Y-m-d 00:00:00',strtotime(date('Y-m', time()).'-01 00:00:00'));//本月第一天零点
    	
    	//订单
    	
    	
    	
    	$order=\App\SubOrderModel::where('supplier_id','=',$supplier_id)->where('ys_base_order.state','=',1)
    				->join('ys_base_order','ys_sub_order.base_id','=','ys_base_order.id')
    				->count();
    	$order_day=\App\SubOrderModel::where('supplier_id','=',$supplier_id)->where('ys_base_order.state','=',1)
    				->where('pay_time','>=',$time)
			    	->join('ys_base_order','ys_sub_order.base_id','=','ys_base_order.id')
			    	->count();
    	$order_yesterday=\App\SubOrderModel::where('supplier_id','=',$supplier_id)->where('ys_base_order.state','=',1)
			    	->where('pay_time','>=',$yesterday)->where('pay_time','<',$time)
			    	->join('ys_base_order','ys_sub_order.base_id','=','ys_base_order.id')
			    	->count();
    	$order_monday=\App\SubOrderModel::where('supplier_id','=',$supplier_id)->where('ys_base_order.state','=',1)
			    	->where('pay_time','>=',$monday)
			    	->join('ys_base_order','ys_sub_order.base_id','=','ys_base_order.id')
			    	->count();
    	$order_month=\App\SubOrderModel::where('supplier_id','=',$supplier_id)->where('ys_base_order.state','=',1)
			    	->where('pay_time','>=',$month)
			    	->join('ys_base_order','ys_sub_order.base_id','=','ys_base_order.id')
			    	->count();    	    	
    	
    	$order_num =array(
    			'order'=> $order,
    			'order_day'=> $order_day,
    			'order_yesterday'=> $order_yesterday,
    			'order_monday'=> $order_monday,
    			'order_month'=> $order_month,
    	);
    	
    	
    	
    	
    	
    	return view('supplierindex',['data'=>$data,'order_num'=>$order_num]);
    }
}
