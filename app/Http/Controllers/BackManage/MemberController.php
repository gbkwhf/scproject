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
 	 $member=\App\MemberModel::orderBy('created_at','desc');
 	 
 	 $search=[];
 	 if ($request->start != ''){
 	 	$member->where('created_at','>=',$request->start.' 00:00:00');
 	 	$search['start']=$request->start;
 	 }
 	 if ($request->end != ''){
 	 	$member->where('created_at','<',$request->end.' 59:59:59');
 	 	$search['end']=$request->end;
 	 } 	 
      if ($request->mobile != ""){
          $member->where('mobile','like','%'.$request->mobile.'%');
          $search['mobile']=$request->mobile;
      }   
      if ($request->name != ""){
      	$member->where('name','like','%'.$request->name.'%');
          $search['name']=$request->name;
      }         
     $data = $member ->paginate(10);
 	$sexArr=array(
 			'1'=>'男',
 			'2'=>'女',
 			'0'=>'未选择',
 	); 	
 	foreach ($data as &$val){
        if ($val['sex'] == ''){
            $val['sex'] = 0;
        }
 		$val['sex']=$sexArr[$val['sex']];
 		$val['state']=$val['state']==1?'正常':'禁止登陆';
 	}
	return view('memberlist',['data'=>$data,'search'=>$search]);
 }
 
 public  function memberEdit (Request $request){
 	
 	//返现计算
 	$time=date('Y-m-d H:i:s',strtotime(date('Y-m-d',strtotime('-3months'))));
 	$total_profit=\App\BaseOrderModel::where('pay_time','>',$time)->where('state',1)->sum('all_profit');
 	$total_num=\App\BaseOrderModel::where('pay_time','>',$time)->where('state',1)->sum('rebate_num');
 	//指定用户返利
 	$personal_num=\App\BaseOrderModel::where('pay_time','>',$time)->where('state',1)->where('user_id','260001')->sum('rebate_num');
 	$percent=($total_profit/$total_num)*$personal_num;
 	
 	//时间节点
 	$yesterday=date('Y-m-d',strtotime(date('Y-m-d',strtotime('-1day'))));
 	$start_time=$yesterday.' 00:00:00';
 	$end_time=$yesterday.' 59:59:59';


 	//经销商返利//昨日引入总利润乘以固定比率，，流水
 	$agency_profit=\App\BaseOrderModel::where('state',1)
 			->leftjoin('ys_employee','ys_employee.user_id','=','ys_base_order.employee_id') 	
 			->where('pay_time','<',$end_time)
 			->where('pay_time','>=',$start_time)
 			->groupBy('ys_employee.agency_id')
 			->selectRaw('ys_employee.agency_id,sum(ys_base_order.all_profit) as agency_total')->get();
	foreach ($agency_profit as $val){
		//总利润*比例，加入余额，写入流水
	}	
 			
 	
 	
 	
 	
	//邀请返利//订单主人有没有邀请人，有邀请人就按订单利润乘以固定比率，邀请人获得返利的资格
	$user_profit=\App\BaseOrderModel::where('state',1)
			->leftjoin('ys_member','ys_member.user_id','=','ys_base_order.user_id')	
			->where('pay_time','<',$end_time)
			->where('pay_time','>=',$start_time)
			->groupBy('ys_base_order.user_id')
			->selectRaw('ys_member.invite_id,ys_base_order.user_id,sum(ys_base_order.all_profit) as user_profit')->get();	
	foreach ($user_profit as $val){
		//查询该用户邀请人有没有返现资格
		$had=\App\BaseOrderModel::where('user_id',$val->invite_id)->where('state',1)->where('rebate_num','>',0);
		if($had){//总利润*比例,计入流水和余额
			
		}
	}

 	
  	dd(1);
 	
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

}