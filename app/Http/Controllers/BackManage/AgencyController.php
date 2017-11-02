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
        if(isset($request->password)){
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
}