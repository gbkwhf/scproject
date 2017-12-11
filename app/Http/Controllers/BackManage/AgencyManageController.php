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
 					->select('ys_base_order.id','ys_member.name as employee_name','amount','pay_time','ys_base_order.user_id','receive_mobile as user_mobile'); 		
 			
 		
 		
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
 	
 		$total_amount=$par->orderBy('ys_base_order.pay_time','desc')->sum('amount');

 		$data=$par->orderBy('ys_base_order.pay_time','desc')->paginate(10);
 		

 		foreach ($data as $val){
			$goods_name=\App\SubOrderModel::where('base_id',$val->id)
 							->leftjoin('ys_order_goods','ys_order_goods.sub_id','=','ys_sub_order.id')
 							->leftjoin('ys_goods','ys_order_goods.goods_id','=','ys_goods.id')
 							->selectRaw("GROUP_CONCAT(concat(ys_goods.name,'(',ys_order_goods.num,'件)')) as goods_name")
 							->get();
 			$val->goods_name=str_limit($goods_name[0]->goods_name,30,'...'); 			

 			$u_info=\App\MemberModel::where('user_id',$val->user_id)->first();
 			$val->user_id=$u_info->name;
 			$val->user_mobile=$u_info->mobile;	
 		}
 		
 	}else{
 		$data=[];
 	}
	return view('agencyorderlist',['data'=>$data,'search'=>$search,'employees'=>$emp,'total_amount'=>$total_amount]);
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
 
 
 //会员体现流水
 public  function MemberCashList (Request $request){
 
 	$agency_id=\Session::get('role_userid');
 	//时间，用户名，经销商
 	$bills=\App\OperateBillsModel::where('ys_operate_bills.type',1)
 	->where('ys_employee.agency_id',$agency_id)
 	->leftjoin('ys_member','ys_member.user_id','=','ys_operate_bills.user_id')
 	->leftjoin('ys_employee','ys_employee.user_id','=','ys_operate_bills.employee_id')
 	->selectRaw('ys_member.name,ys_member.mobile,ys_operate_bills.amount,ys_operate_bills.created_at,ys_operate_bills.state,ys_operate_bills.employee_id');
 	$search=[];
 	if ($request->start != ''){
 		$bills->where('ys_operate_bills.created_at','>=',$request->start.' 00:00:00');
 		$search['start']=$request->start;
 	}
 	if ($request->end != ''){
 		$bills->where('ys_operate_bills.created_at','<',$request->end.' 59:59:59');
 		$search['end']=$request->end;
 	}
 	if ($request->mobile != ""){
 		$bills->where('ys_member.mobile','like','%'.$request->mobile.'%');
 		$search['mobile']=$request->mobile;
 	}
 	if ($request->name != ""){
 		$bills->where('ys_member.name','like','%'.$request->name.'%');
 		$search['name']=$request->name;
 	}
 	if (isset($request->state) && $request->state != -1){
 		$bills->where('ys_operate_bills.state','=',$request->state);
 		$search['state']=$request->state;
 	} 	
 	$data = $bills->orderBy('ys_operate_bills.created_at','desc')->paginate(10);
 
 	$state_arr=[
	 	0=>'申请中',
	 	1=>'已完成',
 	];
 	foreach ($data as &$val){
 		$val->state=$state_arr[$val->state]; 			
 		$employee=\App\MemberModel::where('user_id',$val->employee_id)->first();
 		$val->employee_id=$employee->name;
 			
 	}
 	//所有经销商
 	$agency_list=\App\AgencyModel::get();
 	return view('agencyoperatebills',['data'=>$data,'search'=>$search,'agency_list'=>$agency_list]);
 }
 
 
 
 public function MemberCashExcel(Request $request){
 
 	$agency_id=\Session::get('role_userid');
 	//时间，用户名，经销商
 	$bills=\App\OperateBillsModel::where('ys_operate_bills.type',1)
 	->where('ys_employee.agency_id',$agency_id)
 	->leftjoin('ys_member','ys_member.user_id','=','ys_operate_bills.user_id')
 	->leftjoin('ys_employee','ys_employee.user_id','=','ys_operate_bills.employee_id')
 	->selectRaw('ys_member.name,ys_member.mobile,ys_operate_bills.amount,ys_operate_bills.created_at,ys_operate_bills.state,ys_operate_bills.employee_id');
 	$search=[];
 	if ($request->start != ''){
 		$bills->where('ys_operate_bills.created_at','>=',$request->start.' 00:00:00');
 		$search['start']=$request->start;
 	}
 	if ($request->end != ''){
 		$bills->where('ys_operate_bills.created_at','<',$request->end.' 59:59:59');
 		$search['end']=$request->end;
 	}
 	if ($request->mobile != ""){
 		$bills->where('ys_member.mobile','like','%'.$request->mobile.'%');
 		$search['mobile']=$request->mobile;
 	}
 	if ($request->name != ""){
 		$bills->where('ys_member.name','like','%'.$request->name.'%');
 		$search['name']=$request->name;
 	}
 	if (isset($request->state) && $request->state != -1){
 		$bills->where('ys_operate_bills.state','=',$request->state);
 		$search['state']=$request->state;
 	} 	
 	$data = $bills->orderBy('ys_operate_bills.created_at','desc')->get();
 
 	$state_arr=[
	 	0=>'申请中',
	 	1=>'已完成',
 	];
 	foreach ($data as &$val){
 		$val->state=$state_arr[$val->state]; 			
 		$employee=\App\MemberModel::where('user_id',$val->employee_id)->first();
 		$val->employee_id=$employee->name;
 	}
 
 	$arr_data=$data->toArray();
 	if (empty($arr_data)){
 		return back();
 	}
 	//，
 	foreach($arr_data as $k=>$v){
 		$new_arr[$k]=$v;
 	}
 	// 输出Excel文件头，可把user.csv换成你要的文件名
 	header('Content-Type: application/vnd.ms-excel');
 	header('Content-Disposition: attachment;filename="用户提现流水.csv"');
 	header('Cache-Control: max-age=0');
 
 	// 打开PHP文件句柄，php://output 表示直接输出到浏览器
 	$fp = fopen('php://output', 'a');
 
 	// 输出Excel列名信息
 	$head = array('会员名','注册手机','金额','提现时间','状态','经销商');
 	foreach ($head as $i => $v) {
 		// CSV的Excel支持GBK编码，一定要转换，否则乱码
 		$head[$i] = iconv('utf-8', 'gbk', $v);
 	}
 	// 将数据通过fputcsv写到文件句柄
 	fputcsv($fp, $head);
 	$total_amount=0;
 	foreach ($new_arr as $key => $val) {
 		$total_amount+=$val['amount'];
 		foreach($val as $k=>$v){
 		 	if($k=='amount'){
					$new[$k] = iconv('utf-8', 'gbk//IGNORE',$v);
			}else{
					$new[$k] = iconv('utf-8', 'gbk//IGNORE', strval($v)."\t");
			}
 		}
 		fputcsv($fp, $new);
 	}
 	$null=array('','','','','','','','');
 	//统计信息
 	fputcsv($fp,$null);
 	fputcsv($fp,$null);
 	fputcsv($fp, array(iconv('utf-8', 'gbk', '总金额'.$total_amount)));
 }
 
//订单列表excel
 public function orderListExcel(Request $request){
 
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
 					->select('ys_base_order.id','ys_member.name as employee_name','ys_base_order.id as goods_name','amount','pay_time','ys_base_order.user_id','receive_mobile as user_mobile')
 					->selectRaw("CONCAT(receive_name,',',receive_mobile,',',receive_address) as user_address"); 		
 			
 		
 		
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
 	
 		$total_amount=$par->orderBy('ys_base_order.pay_time','desc')->sum('amount');
 			
 		
 		$data=$par->orderBy('ys_base_order.pay_time','desc')->get();
 		

 		
 		foreach ($data as $val){
			$goods_name=\App\SubOrderModel::where('base_id',$val->id)
 							->leftjoin('ys_order_goods','ys_order_goods.sub_id','=','ys_sub_order.id')
 							->leftjoin('ys_goods','ys_order_goods.goods_id','=','ys_goods.id')
 							->selectRaw("GROUP_CONCAT(concat(ys_goods.name,'(',ys_order_goods.num,'件)')) as goods_name")
 							->get();
 			$val->goods_name=str_limit($goods_name[0]->goods_name,30,'...');

 			$u_info=\App\MemberModel::where('user_id',$val->user_id)->first();
 			$val->user_id=$u_info->name;
 			$val->user_mobile=$u_info->mobile; 			 			
 		}
 		
 	}else{
 		$data=[];
 	}
 	
 	

 	
 	
 
 	$arr_data=$data->toArray();
 	if (empty($arr_data)){
 		return back();
 	}
 	//，
 	foreach($arr_data as $k=>$v){
 		$new_arr[$k]=$v;
 	}
 	// 输出Excel文件头，可把user.csv换成你要的文件名
 	header('Content-Type: application/vnd.ms-excel');
 	header('Content-Disposition: attachment;filename="用户提现流水.csv"');
 	header('Cache-Control: max-age=0');
 
 	// 打开PHP文件句柄，php://output 表示直接输出到浏览器
 	$fp = fopen('php://output', 'a');
 
 	//员工名	商品名	订单金额	付款时间	用户名	用户手机	操作
 	// 输出Excel列名信息
 	$head = array('订单号','员工名','商品名','金额','付款时间','用户名','用户手机','收货地址');
 	foreach ($head as $i => $v) {
 		// CSV的Excel支持GBK编码，一定要转换，否则乱码
 		$head[$i] = iconv('utf-8', 'gbk', $v);
 	}
 	// 将数据通过fputcsv写到文件句柄
 	fputcsv($fp, $head);
 	$total_amount=0;
 	foreach ($new_arr as $key => $val) {
 		$total_amount+=$val['amount'];
 		foreach($val as $k=>$v){
 		 	if($k=='amount'){
					$new[$k] = iconv('utf-8', 'gbk//IGNORE',$v);
			}else{
					$new[$k] = iconv('utf-8', 'gbk//IGNORE', strval($v)."\t");
			}
 		}
 		fputcsv($fp, $new);
 	}
 	
 	
 	$null=array('','','','','','','','');
 	//统计信息
 	fputcsv($fp,$null);
 	fputcsv($fp,$null);
 		
 	fputcsv($fp, array(iconv('utf-8', 'gbk','总金额'.$total_amount)));
 	
 } 
 
 
 

}