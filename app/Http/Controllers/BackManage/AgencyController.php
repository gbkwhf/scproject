<?php

namespace App\Http\Controllers\BackManage;

use App\AdminRoleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;


class AgencyController  extends Controller
{


 public  function agencyList (Request $request){
 	
 	$par=\App\AgencyModel::select();
 	$search=array();
 	if ($request->name != ''){
 		$par->where('name','like',"%$request->name%");
 		$search['name']=$request->name;
 	}
 	if ($request->mobile != ''){
 		$par->where('mobile','like',"%$request->mobile%");
 		$search['mobile']=$request->mobile;
 	}

 	$data=$par->orderBy('id','desc')->paginate(10);
 	$state_arr=[
 		0=>'禁用',
 		1=>'正常',
 	];
 	foreach ($data as $val){
 		$val['state']=$state_arr[$val['state']];
 	}

	return view('agencylist',['data'=>$data,'search'=>$search]);
 }
 //
 public  function agencyEdit (Request $request){
 
 	$data=\App\AgencyModel::where('id',$request->id)->first();
	return view('agencyedit',['data'=>$data]);
 } 
 public  function agencySave (Request $request){

     $input = Input::except('_token');
     $rules = [
         'name'=> 'required',
         'mobile'=> 'required',
     ];
     $massage = [
         'name.required' =>'名称不能为空',
         'mobile.required' =>'手机号不能为空',
     ];
     $validator = \Validator::make($input,$rules,$massage);
     if($validator->passes()){
        $params=array(
                'name'=>$request->name,
                'mobile'=>$request->mobile,
                'state'=>$request->state,
        		'account'=>$request->account,
        );
        if(!empty($request->password)){
            $params['password']=md5($request->password);
        }
         $res = \App\AgencyModel::where('id',$request->id)->update($params);
         if($res === false){
             return back() -> with('errors','数据更新失败');
         }else{
             Session()->flash('message','保存成功');
             return redirect('agencylist');
         }
     }else{
         return back() -> withErrors($validator);
     }

 }
 
 public  function agencyAdd (Request $request){

 	return view('agencyedit');
 }

 public  function agencyCreate (Request $request){
     $input = Input::except('_token');
     $rules = [
         'name'=> 'required',
         'password'=> 'required',
         'mobile'=> 'required',
     ];
     $massage = [
         'name.required' =>'名称不能为空',
         'mobile.required' =>'手机号不能为空',
         'password.required' =>'密码不能为空',
     ];
     $validator = \Validator::make($input,$rules,$massage);
     if($validator->passes()){
        $params=array(
                'name'=>$request->name,
                'mobile'=>$request->mobile,
                'password'=>md5($request->password),
        		'account'=>$request->account,
        		'state'=>$request->state,
        );
         //dd($params);
         $res = \App\AgencyModel::create($params);

         if($res){
             return redirect('agencylist');
         }else{
             return back() -> with('errors','添加经销商错误');
         }
     }else{
         return back() -> withErrors($validator);
     }
 }
 //删除经销商
 public function agencyDelete(Request $request)
 {
 	\App\AgencyModel::where('id',$request->id)->delete();
 	return redirect('agencylist');
 }
 //会员体现流水
 public  function MemberCashList (Request $request){
 
 	
 	//时间，用户名，经销商
 	$bills=\App\OperateBillsModel::where('ys_operate_bills.type',1)
 				->where('ys_operate_bills.state',1)
 				->leftjoin('ys_member','ys_member.user_id','=','ys_operate_bills.user_id')
 				->leftjoin('ys_employee','ys_employee.user_id','=','ys_operate_bills.employee_id') 
 				->selectRaw('ys_member.name,ys_member.mobile,ys_operate_bills.amount,ys_operate_bills.created_at,ys_operate_bills.employee_id,ys_employee.agency_id');		
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
 	if ($request->agency != ''){
 		$bills->where('ys_employee.agency_id','=',$request->agency);
 		$search['agency']=$request->agency;
 	} 	
 	$data = $bills->orderBy('ys_operate_bills.created_at','desc')->paginate(10);


 	foreach ($data as &$val){
 		$employee=\App\AgencyModel::where('id',$val->agency_id)->first();
 		$val->agency_id=$employee->name;
 		
 	}
 	//所有经销商
 	$agency_list=\App\AgencyModel::get();
 	return view('operatebills',['data'=>$data,'search'=>$search,'agency_list'=>$agency_list]);
 }
 
 
 
 public function MemberCashExcel(Request $request){
 	
 	//时间，用户名，经销商
 	$bills=\App\OperateBillsModel::where('ys_operate_bills.type',1)
 	->where('ys_operate_bills.state',1)
 	->leftjoin('ys_member','ys_member.user_id','=','ys_operate_bills.user_id')
 	->leftjoin('ys_employee','ys_employee.user_id','=','ys_operate_bills.employee_id')
 	->selectRaw('ys_member.name,ys_member.mobile,ys_operate_bills.amount,ys_operate_bills.created_at,ys_employee.agency_id');
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
 	if ($request->agency != ''){
 		$bills->where('ys_employee.agency_id','=',$request->agency);
 		$search['agency']=$request->agency;
 	}
 	$data = $bills->orderBy('ys_operate_bills.created_at','desc')->get();
 	
 	
 	foreach ($data as &$val){
 		$employee=\App\AgencyModel::where('id',$val->agency_id)->first();
 		$val->agency_id=$employee->name; 			
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
 	$head = array('会员名','注册手机','金额','提现时间','经销商');
 	foreach ($head as $i => $v) {
 		// CSV的Excel支持GBK编码，一定要转换，否则乱码
 		$head[$i] = iconv('utf-8', 'gbk',$v);
 	}
 	// 将数据通过fputcsv写到文件句柄
 	fputcsv($fp, $head);
 	$total_amount=0;
 	foreach ($new_arr as $key => $val) {	
 		$total_amount+=$val['amount'];
 		foreach($val as $k=>$v){
 			// 				if($k=='user_name'){
 			// 					$v=preg_replace("/[^\x{4e00}-\x{9fa5}a-zA-Z0-9]/iu",'',$v);
 			// 				}
 			$new[$k] = iconv('utf-8', 'gbk', strval($v)."\t"); 			
 		}
 		fputcsv($fp, $new);
 	}
 	$null=array('','','','','','','','');
 	//统计信息
 	fputcsv($fp,$null);
 	fputcsv($fp,$null);
 	fputcsv($fp, array(iconv('utf-8', 'gbk', '总金额'.$total_amount)));
 }
 
}