<?php

namespace App\Http\Controllers\BackManage;

use App\AdminRoleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;


class AgencyManageController  extends Controller
{


 public  function orderList (Request $request){
 	
 	$agency_id=\Session::get('role_userid');


 	$emp=\App\EmployeeModel::withTrashed()->where('agency_id',$agency_id)->leftjoin('ys_member','ys_member.user_id','=','ys_employee.user_id')->get();
 	
 	
 	$employees=\App\EmployeeModel::withTrashed()->where('agency_id',$agency_id)->selectRaw('GROUP_CONCAT(user_id) as employees')->get();
 	
 	if($employees && $employees[0]->employees){
 		
 		

 		if ($request->employee_id != ''){
 			$employee_arr[]=$request->employee_id;
 			$search['employee_id']=$request->employee_id;
 		}else{
 			$employee_arr=explode(',',$employees[0]->employees);
 		}
 		
 		$par=\App\BaseOrderModel::whereIn('employee_id',$employee_arr)
 					->leftjoin('ys_member','ys_member.user_id','=','ys_base_order.employee_id') 					
 					->where('ys_base_order.state',1)
 					->select('ys_base_order.id','ys_member.name as employee_name','amount','pay_time','receive_mobile'); 		
 			
 		
 		
 		//付款时间，订单号，用户手机，订单状态，员工名称
 		$search=array();

 		if ($request->start != ''){
 			$par->where('ys_base_order.pay_time','>=',$request->start.' 00:00:00');
 			$search['start']=$request->start;
 		}
 		if ($request->end != ''){
 			$par->where('ys_base_order.pay_time','<',$request->end.' 59:59:59');
 			$search['end']=$request->end;
 		}
 		if ($request->mobile != ''){
 			$par->where('ys_base_order.receive_mobile','like',"%$request->mobile%");
 			$search['mobile']=$request->mobile;
 		}
 	
 		$data=$par->paginate(10);
 		

 		foreach ($data as $val){
			$goods_name=\App\SubOrderModel::where('base_id',$val->id)
 							->leftjoin('ys_order_goods','ys_order_goods.sub_id','=','ys_sub_order.id')
 							->leftjoin('ys_goods','ys_order_goods.goods_id','=','ys_goods.id')
 							->selectRaw("GROUP_CONCAT(concat(ys_goods.name,'(',ys_order_goods.num,'件)')) as goods_name")
 							->get();
 			$val->goods_name=str_limit($goods_name[0]->goods_name,30,'...'); 				
 		}
 		
 	}else{
 		$data=[];
 	}
	return view('agencyorderlist',['data'=>$data,'search'=>$search,'employees'=>$emp]);
 }
 //
 public  function orderDetial (Request $request){
  	
 	$data=\App\BaseOrderModel::where('id',$request->id)->first();

 	$goods_name=\App\BaseOrderModel::where('ys_base_order.id',$request->id)
 	->leftjoin('ys_sub_order','ys_sub_order.base_id','=','ys_base_order.id')
 	->leftjoin('ys_order_goods','ys_sub_order.id','=','ys_order_goods.sub_id')
 	->leftjoin('ys_goods','ys_order_goods.goods_id','=','ys_goods.id')
 	->selectRaw("GROUP_CONCAT(concat(ys_goods.name,'(',ys_order_goods.num,'件)')) as goods_name")
 	->get();
 	

 	$data['goods_name']=$goods_name[0]->goods_name;
 	$data['receive_address']=$data['receive_name'].'，'.$data['receive_mobile'].'，'.$data['receive_address'];
 
 
 	return view('agencyorderdetial',['data'=>$data]);
 }
 
 //设置员工
 public  function setEmployee (Request $request){
 
 	$agency_id=\Session::get('role_userid');
 	
 
 	//员工列表
 	$data=\App\EmployeeModel::where('agency_id',$agency_id)
 	->join('ys_member','ys_employee.user_id','=','ys_member.user_id')
 	->select('ys_employee.id','ys_employee.user_id','ys_member.name','ys_member.mobile')
 	->get();
 	return view('setemployee',['data'=>$data,'agency_id'=>$agency_id]);
 }
 //设置员工提交
 public  function setEmployeeSave (Request $request){
 
 	$agency_id=\Session::get('role_userid');
 	$user_info=\App\MemberModel::where('ys_member.mobile',$request->phone)
 	->leftJoin('ys_employee','ys_member.user_id','=','ys_employee.user_id')
 	->select('ys_member.user_id','ys_employee.agency_id','ys_employee.deleted_at')
 	->first();
 	if(!$user_info){
 	 	session()->flash('message','人员不存在');
 		return back();
 	}
 	if($user_info->agency_id>0 && empty($user_info->deleted_at)){
 		if($user_info->agency_id==$request->agency_id && empty($user_info->deleted_at)){
	 		session()->flash('message','已是其他店员工');
	 		return back();
 		}
 		session()->flash('message','已是本店员工');
 		return back();
 	}
 	$params=array(
 			'user_id'=>	$user_info->user_id,
 			'agency_id'=>$agency_id,
 			'deleted_at'=>null,
 	);
 	$result=\App\EmployeeModel::withTrashed()->updateOrcreate(['user_id'=>$user_info->user_id],$params);
 	return redirect('agency/setemployee');
 }
 //删除员工
 public  function DeleteEmployee (Request $request){

 	$result=\App\EmployeeModel::where('id',$request->id)->delete(); 	
 	return redirect('agency/setemployee');
 
 }
 

}