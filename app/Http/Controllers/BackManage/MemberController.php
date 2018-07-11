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
use App\Http\Controllers\Weixin;



class MemberController  extends Controller
{


 public  function memberList (Request $request){


		 //dd(Auth::user());
 	 $member=\App\MemberModel::leftjoin('ys_employee','ys_employee.user_id','=','ys_member.invite_id')
 	 			->leftjoin('ys_invite_member','ys_invite_member.user_id','=','ys_member.user_id')
		 		->selectRaw('ys_member.*')
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
		$val['invite_role']=$val['invite_role']==1?'有':'无';
 		
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
	 //dd($data);
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
			'invite_role'=>$request->invite_role,
			'user_lv'=>$request->user_lv,
 	);
 	$a=\App\MemberModel::where('user_id',$request->user_id)->update($params);
	 if($request->state==2){
		 \App\Session::where('user_id',$request->user_id)->delete();
	 }
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
 	if ($request->agency != ''){
 		$member->where('ys_employee.agency_id','=',$request->agency);
 		$search['agency']=$request->agency;
 	} 	
 	$data = $member->select('ys_member.invite_id','ys_member.name','ys_member.mobile','amount','pay_describe','type','ys_bills.created_at')
 			->leftjoin('ys_employee','ys_employee.user_id','=','ys_member.invite_id')
 	 		->paginate(10);
 	

 	
 	
 	$total=$member->sum('ys_bills.amount');
 	
 	//1会员返利，2邀请返利，3系统返现
 	$typeArr=array(
 			'1'=>'购物返现',
 			'2'=>'邀请返现',
 			'3'=>'系统返现',
 	);
 	foreach ($data as &$val){
 		$val['type']=$typeArr[$val['type']];
 		
 		
 		
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
 	return view('membercashbacklist',['data'=>$data,'search'=>$search,'total'=>$total,'agency_list'=>$agency_list]);
 }
 
 
 
 public function CashBackListExcel(Request $request){
 
 
 
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
 	if ($request->agency != ''){
 		$member->where('ys_employee.agency_id','=',$request->agency);
 		$search['agency']=$request->agency;
 	}
 	//会员名	手机号	金额	描述	类型	门店  员工 	时间
 	$data = $member->select('ys_member.name','ys_member.mobile','amount','pay_describe','type','ys_member.invite_id as store_name','ys_member.invite_id as employee_name','ys_bills.created_at')
 	->leftjoin('ys_employee','ys_employee.user_id','=','ys_member.invite_id')
 	->get();
 	
 	
 	
 	
 	$total=$member->sum('ys_bills.amount');
 	
 	//1购物日返，2邀请返利，3系统返现 ，4购物月返
 	$typeArr=array(
 			'1'=>'购物日返',
 			'2'=>'邀请返现',
 			'3'=>'系统返现',
 			'4'=>'购物月返',
 			'5'=>'',
 	);
 	foreach ($data as &$val){
 		$val['type']=$typeArr[$val['type']];
 			
 			
 			
 		//邀请人
 		$invite_info=\App\MemberModel::where('ys_member.user_id',$val->store_name)
 		->leftjoin('ys_employee','ys_employee.user_id','=','ys_member.user_id')
 		->leftjoin('ys_agency','ys_agency.id','=','ys_employee.agency_id')
 		->select('ys_member.name','ys_agency.name as agency_name')
 		->first();
 		$agenyc_name=empty($invite_info->agency_name)?'':"$invite_info->agency_name";
 	
 		//$val['invite_id']=empty($val->invite_id)?'':$invite_info->name.$agenyc_name;
 		
 		$val['store_name']=empty($val->store_name)?'':$agenyc_name;
 		$val['employee_name']=empty($val->store_name)?'':$invite_info->name;
 		
 			
 	}
 	
 
 //dd($data);
 
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
 	header('Content-Disposition: attachment;filename="用户返现列表.csv"');
 	header('Cache-Control: max-age=0');
 
 	// 打开PHP文件句柄，php://output 表示直接输出到浏览器
 	$fp = fopen('php://output', 'a');
 
 	// 输出Excel列名信息 会员名	手机号	金额	描述	类型	门店  员工 	时间
 	$head = array('会员名','注册手机','金额','描述','类型','门店','员工','时间');
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

	public  function answerQuestion (Request $request){

		$par=\App\AfterExchangeModel::selectRaw('ys_after_exchange.*,ys_after_exchange.merchant_reply as state,ys_member.name')
							->leftjoin('ys_member','ys_member.user_id','=','ys_after_exchange.user_id');



		$search=array();
		if ($request->start != ''){
			$par->where('ys_after_exchange.created_at','>=',$request->start.' 00:00:00');
			$search['start']=$request->start;
		}
		if ($request->end != ''){
			$par->where('ys_after_exchange.created_at','<',$request->end.' 59:59:59');
			$search['end']=$request->end;
		}
		if (isset($request->state) && $request->state == 0){
			$par->where('merchant_reply','=','');
			$search['state']=$request->state;
		}elseif (isset($request->state) && $request->state == 1){
			$par->where('merchant_reply','!=',' ');
			$search['state']=$request->state;
		}

		$data=$par->orderBy('id','desc')->paginate(10);




		foreach ($data as &$val){
			$val->state=empty($val->state)?'未回复':'已回复';
			$val->user_problem=str_limit($val->user_problem,20);

		}


		return view('answerquestionlist',['data'=>$data,'search'=>$search]);
	}



	public  function answerQuestionDetial (Request $request){




		$data=\App\AfterExchangeModel::where('id',$request->id)
				->leftjoin('ys_member','ys_member.user_id','=','ys_after_exchange.user_id')
				->selectRaw('ys_after_exchange.*,ys_member.name')
				->first();


		return view('answerquestiondetial',['data'=>$data]);
	}
	public  function answerQuestionSave (Request $request){


			$res=\App\AfterExchangeModel::where('id',$request->id)->update(['merchant_reply'=>$request->reply]);
			if($res === false){
				return back() -> with('errors','数据更新失败');
			}else{
				Session()->flash('message','保存成功');
				return redirect('manage/answerquestion');
			}

	}


	public  function depositOrder (Request $request){



	$par=\App\ApplyInviteRoleModel::where('ys_apply_inviterole.state',1)->selectRaw('ys_apply_inviterole.*,ys_member.name as user_name,ys_member.mobile as user_mobile')
		->leftjoin('ys_member','ys_member.user_id','=','ys_apply_inviterole.user_id');



	$search=array();
	if ($request->start != ''){
		$par->where('ys_apply_inviterole.created_at','>=',$request->start.' 00:00:00');
		$search['start']=$request->start;
	}
	if ($request->end != ''){
		$par->where('ys_apply_inviterole.created_at','<',$request->end.' 59:59:59');
		$search['end']=$request->end;
	}
	if (isset($request->state)){
		$par->where('ys_apply_inviterole.confirm_state',$request->state);
		$search['state']=$request->state;
	}

	$data=$par->orderBy('ys_apply_inviterole.created_at','desc')->paginate(10);




	foreach ($data as &$val){
		$val->confirm_state=empty($val->confirm_state)?'未确认':'已确认';

	}


	return view('depositorderlist',['data'=>$data,'search'=>$search]);
}



	public  function depositOrderDetial (Request $request){




		$data=\App\ApplyInviteRoleModel::where('ys_apply_inviterole.order_id',$request->id)
			->leftjoin('ys_member','ys_member.user_id','=','ys_apply_inviterole.user_id')
			->selectRaw('ys_apply_inviterole.*,ys_member.name as user_name')
			->first();


		return view('depositorderdetial',['data'=>$data]);
	}
	public  function depositOrderSave (Request $request){

		$o_info=\App\ApplyInviteRoleModel::where('order_id',$request->id)->first();
		$res=\App\ApplyInviteRoleModel::where('order_id',$request->id)->update(['confirm_state'=>$request->confirm_state]);

		\App\MemberModel::where('user_id',$o_info->user_id)->update(['invite_role'=>$request->confirm_state]);

		if($res === false){
			return back() -> with('errors','数据更新失败');
		}else{
			Session()->flash('message','保存成功');
			return redirect('manage/depositorder');
		}

	}

	public  function returnApply (Request $request){



		$par=\App\ApplyReturnModel::select('ys_apply_return.*','ys_member.*','ys_member.created_at as user_created_at','ys_apply_return.created_at as apply_created_at')->leftjoin('ys_member','ys_member.user_id','=','ys_apply_return.user_id');



		$search=array();
		if ($request->start != ''){
			$par->where('ys_apply_return.created_at','>=',$request->start.' 00:00:00');
			$search['start']=$request->start;
		}
		if ($request->end != ''){
			$par->where('ys_apply_return.created_at','<',$request->end.' 59:59:59');
			$search['end']=$request->end;
		}
		if (isset($request->state) && $request->state!=-1){
			$par->where('ys_apply_return.confirm_state',$request->state);
			$search['state']=$request->state;
		}

		$data=$par->orderBy('ys_apply_return.created_at','desc')->paginate(10);



		foreach ($data as &$val){
			$val->confirm_state=empty($val->confirm_state)?'未确认':'已确认';

		}

		//dd($data);

		return view('returnapplylist',['data'=>$data,'search'=>$search]);
	}



	public  function returnApplyDetial (Request $request){




		$data=\App\ApplyReturnModel::where('ys_apply_return.id',$request->id)
			->leftjoin('ys_member','ys_member.user_id','=','ys_apply_return.user_id')
			->first();


		return view('returnapplydetial',['data'=>$data]);
	}
	public  function returnApplySave (Request $request){

		$o_info=\App\ApplyReturnModel::where('id',$request->id)->first();



		if($request->confirm_state==1){
			$res=\App\ApplyReturnModel::where('id',$request->id)->update(['confirm_state'=>1]);
			\App\MemberModel::where('user_id',$o_info->user_id)->update(['invite_role'=>0,'state'=>2,'balance'=>0,'deposit'=>0]);

			\App\Session::where('user_id',$o_info->user_id)->delete();
		}


		if($res === false){
			return back() -> with('errors','数据更新失败');
		}else{
			Session()->flash('message','保存成功');
			return redirect('manage/returnapply');
		}

	}


	public  function applyToweixin (Request $request){



		$par=\App\ApplyMoneyToWeixinModel::select('ys_apply_money_toweixin.*','ys_member.name','ys_member.mobile','ys_apply_money_toweixin.created_at as created_at')->leftjoin('ys_member','ys_member.user_id','=','ys_apply_money_toweixin.user_id');




		$search=array();
		if ($request->start != ''){
			$par->where('ys_apply_money_toweixin.created_at','>=',$request->start.' 00:00:00');
			$search['start']=$request->start;
		}
		if ($request->end != ''){
			$par->where('ys_apply_money_toweixin.created_at','<',$request->end.' 59:59:59');
			$search['end']=$request->end;
		}
		if (isset($request->state) && $request->state!=-1){
			$par->where('ys_apply_money_toweixin.state',$request->state);
			$search['state']=$request->state;
		}

		$data=$par->orderBy('ys_apply_money_toweixin.created_at','desc')->paginate(10);


		//state0申请中，1已通过，2，拒绝
		$stateArr=[
			0=>'申请中',
			1=>'已通过',
			2=>'已拒绝',
		];
		foreach ($data as &$val){
			$val->state=$stateArr[$val->state];

		}


		return view('applytoweixinlist',['data'=>$data,'search'=>$search]);
	}



	public  function applyToweixinDetial (Request $request){




		$data=\App\ApplyMoneyToWeixinModel::where('ys_apply_money_toweixin.id',$request->id)
			->select('ys_apply_money_toweixin.*','ys_member.name','ys_member.mobile')
			->leftjoin('ys_member','ys_member.user_id','=','ys_apply_money_toweixin.user_id')
			->first();


		return view('applytoweixindetial',['data'=>$data]);
	}
	public  function applyToweixinSave (Request $request){


		\DB::beginTransaction(); //(开启事务)
		$wei_cost=config('clinic-config.wei_cost');

		$res=true;
		$o_info=\App\ApplyMoneyToWeixinModel::where('id',$request->id)->first();


		if($o_info->state==0){

			if($request->state==1){

				//打款到微信
				$mchid = '1501770681';          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
				$appid = 'wxe1adb0d83562cd82';  //微信支付申请对应的公众号的APPID
				$appKey = '';   //微信支付申请对应的公众号的APP Key
				$apiKey = 'e4bff1oc172ae9c1a9f7a319fcaff42e';   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
				//①、获取当前访问页面的用户openid（如果给指定用户转账，则直接填写指定用户的openid)
				$wxPay = new \App\Http\Controllers\Weixin\WxpayService($mchid,$appid,$appKey,$apiKey);
				$openId =$o_info->open_id;

				//②、付款
				$outTradeNo = $o_info->order_id;     //订单号
				$payAmount = $o_info->amount;             //转账金额，单位:元。转账最小金额为1元
				$trueName = '';         //收款人真实姓名
				$result = $wxPay->createJsBizPackage($openId,$payAmount,$outTradeNo,$trueName);
				\Log::info('log toweixin'.$result);
				if($result){//成功
					//echo 'success';

					//修改申请状态
					$res=\App\ApplyMoneyToWeixinModel::where('id',$request->id)->update(['state'=>1]);
					$params=[
						'user_id'=>$o_info->user_id,
						'amount'=>$o_info->amount,
						'type'=>1,
						'desc'=>'提现成功'

					];
					$params_wei=[
							'user_id'=>$o_info->user_id,
							'amount'=>round($o_info->amount*$wei_cost,2),
							'type'=>1,
							'desc'=>'提现手续费'
					];

					$res=\App\BalanceBillModel::create($params);
					$res=\App\BalanceBillModel::create($params_wei);

				}else{
					return back() -> with('errors','付款失败');
				}




			}elseif ($request->state==2){

			
				//修改申请状态
				$res=\App\ApplyMoneyToWeixinModel::where('id',$request->id)->update(['state'=>2]);
				//将余额退回
				$cost=$o_info->amount+round($o_info->amount*$wei_cost,2);
				\App\MemberModel::where('user_id',$o_info->user_id)->increment('balance',$cost);
				//插入余额明细
				$params=[
						'user_id'=>$o_info->user_id,
						'amount'=>$o_info->amount,
						'type'=>1,
						'desc'=>'提现失败'
				];
				$params_wei=[
					'user_id'=>$o_info->user_id,
					'amount'=>round($o_info->amount*$wei_cost,2),
					'type'=>1,
					'desc'=>'手续费退回'
				];
				$res=\App\BalanceBillModel::create($params);
				$res=\App\BalanceBillModel::create($params_wei);
			}

		}else{
			return back() -> with('errors','不能重复审批');
		}

		if ($res === false) {
			\DB::rollBack();
			return back() -> with('errors','数据更新失败');
		}else {
			\DB::commit();
			return redirect('manage/returnorder');
		}

	}


}