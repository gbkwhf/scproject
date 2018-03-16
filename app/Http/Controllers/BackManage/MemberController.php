<?php

namespace App\Http\Controllers\BackManage;

use App\MemberModel;
use App\UserVersionInfoModel;
use Dingo\Api\Contract\Http\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;


class MemberController  extends Controller
{


 public  function memberList (Request $request){

 	//dd(Auth::user());
 	 $member=\App\MemberModel::leftjoin('ys_employee','ys_employee.user_id','=','ys_member.invite_id')
 	 			->leftjoin('ys_invite_member','ys_invite_member.user_id','=','ys_member.user_id')
 	 			->orderBy('ys_member.created_at','desc');
 	 
 	 $search=[];
 	 if ($request->start != ''){
 	 	$member->where('ys_member.created_at','>=',$request->start.' 00:00:00');
 	 	$search['start']=$request->start;
 	 }
 	 if ($request->end != ''){
 	 	$member->where('ys_member.created_at','<',$request->end.' 59:59:59');
 	 	$search['end']=$request->end;
 	 } 	 
      if ($request->mobile != ""){
          $member->where('ys_member.mobile','like','%'.$request->mobile.'%');
          $search['mobile']=$request->mobile;
      }   
      if ($request->name != ""){
      	$member->where('ys_member.name','like','%'.$request->name.'%');
          $search['name']=$request->name;
      }   
      if ($request->agency != ''){
      	$member->where('ys_employee.agency_id','=',$request->agency);
      	$search['agency']=$request->agency;
      }            
     $data = $member ->paginate(10);
 	$sexArr=array(
 			'1'=>'男',
 			'2'=>'女',
 			'0'=>'未选择',
 	); 	
 	$invite_gift=config('clinic-config.invite_goods');

 	foreach ($data as &$val){
        if ($val['sex'] == ''){
            $val['sex'] = 0;
        }
 		$val['sex']=$sexArr[$val['sex']];
 		$val['state']=$val['state']==1?'正常':'禁止登陆';
 		
 		$val['gift']=empty($val['gift'])?'':$invite_gift[$val['gift']];
 
 		//邀请人
 		$invite_info=\App\MemberModel::where('ys_member.user_id',$val->invite_id)
 				->leftjoin('ys_employee','ys_employee.user_id','=','ys_member.user_id')
 				->leftjoin('ys_agency','ys_agency.id','=','ys_employee.agency_id')
 				->select('ys_member.name','ys_agency.name as agency_name')
 				->first();
 		$agenyc_name=empty($invite_info->agency_name)?'':"($invite_info->agency_name)";
 		
 		$val['invite_id']=empty($val->invite_id)?'':$invite_info->name.$agenyc_name;
 	}
 	//所有经销商
 	$agency_list=\App\AgencyModel::get();
	return view('memberlist',['data'=>$data,'search'=>$search,'agency_list'=>$agency_list]);
 }
 
 
 public function memberListExcel(Request $request){
 
 	 	//dd(Auth::user());
 	 $member=\App\MemberModel::leftjoin('ys_employee','ys_employee.user_id','=','ys_member.invite_id')
 	 			->leftjoin('ys_invite_member','ys_invite_member.user_id','=','ys_member.user_id')
 	 			->orderBy('ys_member.created_at','desc')
 	 			->select('ys_member.name as user_name','ys_member.mobile','ys_member.sex','ys_member.created_at','ys_member.invite_id as employee_name','ys_member.invite_id as agency_name','ys_invite_member.gift');
 	 
 	 $search=[];
 	 if ($request->start != ''){
 	 	$member->where('ys_member.created_at','>=',$request->start.' 00:00:00');
 	 	$search['start']=$request->start;
 	 }
 	 if ($request->end != ''){
 	 	$member->where('ys_member.created_at','<',$request->end.' 59:59:59');
 	 	$search['end']=$request->end;
 	 } 	 
      if ($request->mobile != ""){
          $member->where('ys_member.mobile','like','%'.$request->mobile.'%');
          $search['mobile']=$request->mobile;
      }   
      if ($request->name != ""){
      	$member->where('ys_member.name','like','%'.$request->name.'%');
          $search['name']=$request->name;
      }   
      if ($request->agency != ''){
      	$member->where('ys_employee.agency_id','=',$request->agency);
      	$search['agency']=$request->agency;
      }            
     $data = $member ->get();
 	$sexArr=array(
 			'1'=>'男',
 			'2'=>'女',
 			'0'=>'未选择',
 	); 	
 	$invite_gift=config('clinic-config.invite_goods');
 	foreach ($data as &$val){
        if ($val['sex'] == ''){
            $val['sex'] = 0;
        }
 		$val['sex']=$sexArr[$val['sex']];
 		//$val['state']=$val['state']==1?'正常':'禁止登陆';
 		$val['gift']=empty($val['gift'])?'':$invite_gift[$val['gift']];
 			
 
 		//邀请人
 		$invite_info=\App\MemberModel::where('ys_member.user_id',$val->employee_name)
 				->leftjoin('ys_employee','ys_employee.user_id','=','ys_member.user_id')
 				->leftjoin('ys_agency','ys_agency.id','=','ys_employee.agency_id')
 				->select('ys_member.name','ys_agency.name as agency_name')
 				->first();

 		
 		if(empty($val->employee_name)){
 			$val['employee_name']='';
 			$val['agency_name']='';
 		}else{
 			$val['employee_name']=$invite_info->name;
 			$val['agency_name']=$invite_info->agency_name;
 		}
 		
 		//$val['invite_id']=empty($val->invite_id)?'':$invite_info->name.$agenyc_name;
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
 	header('Content-Disposition: attachment;filename="用户列表.csv"');
 	header('Cache-Control: max-age=0');
 
 	// 打开PHP文件句柄，php://output 表示直接输出到浏览器
 	$fp = fopen('php://output', 'a');
 
 	// 输出Excel列名信息
 	$head = array('会员名','注册手机','性别','注册时间','邀请人','门店','领取礼品');
 	foreach ($head as $i => $v) {
 		// CSV的Excel支持GBK编码，一定要转换，否则乱码
 		$head[$i] = iconv('utf-8', 'gbk',$v);
 	}
 	// 将数据通过fputcsv写到文件句柄
 	fputcsv($fp, $head);
 	foreach ($new_arr as $key => $val) {
 		foreach($val as $k=>$v){
			$new[$k] = iconv('utf-8', 'gbk//IGNORE', strval($v)."\t");
 		}
 		fputcsv($fp, $new);
 	}

 }
 public  function memberEdit (Request $request){


 	
	$data=\App\MemberModel::where('user_id',$request->id)->first();
 	
	$sexArr=array(
			'1'=>'男',
			'2'=>'女',
			'0'=>'未选择',
	);
     if ($data['sex']==''){$data['sex']=0;}
	$data['sex']=$sexArr[$data['sex']];
 	return view('memberedit',['data'=>$data]);
 }
 
 public  function memberSave (Request $request){

 	$params=array(
 			'state'=>$request->state,
 	);
 	$a=\App\MemberModel::where('user_id',$request->user_id)->update($params);
 	return redirect('memberlist');
 }
 
 public  function SendMemberBalance (Request $request){
 	
 	
 
 	

 	$start_time=date('Y-m-d',strtotime('-180 days')).' 00:00:00';
 	$end_time=date('Y-m-d',strtotime(date('Y-m-d',strtotime('-1 days')))).' 23:59:59';
 	
 	
 	$total_num=\App\BaseOrderModel::leftjoin('ys_member','ys_member.user_id','=','ys_base_order.user_id')
 					->where('ys_member.cash_back',1)
 					->where('ys_base_order.state',1)->where('pay_time','>=',$start_time)
 					->where('ys_base_order.pay_time','<',$end_time)
 					->sum('rebate_num');

 	
 	return view('sendmemberbalace',['total'=>$total_num]);
 }
 
 public  function SendMemberBalanceSave (Request $request){
 
 	
 	$percent=trim($request->balance);
 	if($percent<=0){
 		return back() -> with('errors','金额必须大于0');
 	}
 	
 	$start_time=date('Y-m-d',strtotime('-180 days')).' 00:00:00';
 	$end_time=date('Y-m-d',strtotime(date('Y-m-d',strtotime('-1 days')))).' 23:59:59';
 	
 	\DB::beginTransaction(); //(开启事务)
 	//指定用户返利
 	$personal=\App\BaseOrderModel::where('pay_time','>=',$start_time)->where('pay_time','<',$end_time)
		 	->leftjoin('ys_member','ys_member.user_id','=','ys_base_order.user_id')
			->where('ys_member.cash_back',1)
		 	->where('ys_base_order.state',1)
		 	->where('rebate_num','>',0)
		 	->groupBy('ys_base_order.user_id')
		 	->selectRaw('ys_base_order.user_id,sum(ys_base_order.rebate_num) as rebate_num')
		 	->get();

 	
 	$user_insert=true;
 	$user_update=true;
 	foreach ($personal as $val){
 		$user_money=$val->rebate_num*$percent;
 		$params=[
	 		'user_id'=>$val->user_id,
	 		'amount'=>$user_money,
	 		'pay_describe'=>'系统返利',
	 		'created_at'=>date('Y-m-d H:i:s',time()),
	 		'type'=>3,
 		];
 		$user_insert=\App\BillModel::insert($params);
 		$user_update=\App\MemberModel::where('user_id',$val->user_id)->increment('balance',$user_money);
 	}
 	

 	if ($user_insert==false || $user_update==false) {
 		\DB::rollBack();
 		return back() -> with('errors','返现失败');
 	}else {
 		\DB::commit();
 		return redirect('manage/sendmemberbalance');
 	}
 }
 
 
 public  function CashBackList (Request $request){
 
 
 	$member=\App\BillModel::leftjoin('ys_member','ys_member.user_id','=','ys_bills.user_id')->orderBy('ys_bills.created_at','desc');
 		
 	$search=[];
 	if ($request->start != ''){
 		$member->where('ys_bills.created_at','>=',$request->start.' 00:00:00');
 		$search['start']=$request->start;
 	}
 	if ($request->end != ''){
 		$member->where('ys_bills.created_at','<',$request->end.' 59:59:59');
 		$search['end']=$request->end;
 	}
 	if ($request->mobile != ""){
 		$member->where('ys_member.mobile','like','%'.$request->mobile.'%');
 		$search['mobile']=$request->mobile;
 	}
 	if ($request->name != ""){
 		$member->where('ys_member.name','like','%'.$request->name.'%');
 		$search['name']=$request->name;
 	}
 	if ($request->type != ""){
 		$member->where('ys_bills.type','=',$request->type);
 		$search['type']=$request->type;
 	} 	
 	$data = $member->select('ys_member.name','ys_member.mobile','amount','pay_describe','type','ys_bills.created_at') ->paginate(10);
 	

 	
 	
 	$total=$member->sum('ys_bills.amount');
 	
 	//1会员返利，2邀请返利，3系统返现
 	$typeArr=array(
 			'1'=>'购物返现',
 			'2'=>'邀请返现',
 			'3'=>'系统返现',
 	);
 	foreach ($data as &$val){
 		$val['type']=$typeArr[$val['type']];
 	}
 	return view('membercashbacklist',['data'=>$data,'search'=>$search,'total'=>$total]);
 }
}