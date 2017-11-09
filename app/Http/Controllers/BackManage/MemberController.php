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
 

 	
 	return view('sendmemberbalace');
 }
 
 public  function SendMemberBalanceSave (Request $request){
 
 	
 	$money=trim($request->balance);
 	//增加余额，写流水
 	\DB::beginTransaction(); //(开启事务)
 	
 	$data=\App\MemberModel::where('ys_member.state',1)
 				->leftjoin('ys_base_order','ys_base_order.user_id','=','ys_member.user_id')
 				->where('ys_base_order.state',1)
 				->where('ys_base_order.rebate_num','>',0)
 				->groupBy('ys_member.user_id')
 				->get();
 	$params=[];
 	$user_ids=[];
	foreach ($data as $val){
		$params[]=[
 				'user_id'=>$val->user_id,
 				'amount'=>$money,
 				'pay_describe'=>'系统返现',
 				'created_at'=>date('Y-m-d H:i:s',time()),
 				'type'=>3,
			];
		$user_ids[]=$val->user_id;
	}	 	
	$user_insert=true;
	$user_update=true;
	$user_insert=\App\BillModel::insert($params);
	$user_update=\App\MemberModel::whereIn('user_id',$user_ids)->increment('balance',$money);
	

	if ($user_insert==false || $user_update==false) {
		\DB::rollBack();
		return back() -> with('errors','返现失败');
	}else {
		\DB::commit();
		return redirect('manage/sendmemberbalance');
	}
 }
}