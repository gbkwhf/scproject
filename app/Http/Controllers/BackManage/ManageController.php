<?php

namespace App\Http\Controllers\BackManage;

use App\AdminRoleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;


class ManageController  extends Controller
{


 public  function manageList (Request $request){
 	
 	
 	$data=\App\User::orderBy('id','desc')->paginate(10);
    for ($i=0;$i<count($data);$i++){
        $adminrole = AdminRoleModel::where('id',$data[$i]['role'])->first();
        $data[$i]['role'] = $adminrole['role_name'];
    }
	return view('manageuser',['data'=>$data]);
 }
 //
 public  function manageEdit (Request $request){
 
 	$data=\App\User::where('id',$request->id)->first();
     $adminrole = AdminRoleModel::get()->toArray();
	return view('manageuseredit',['data'=>$data,'adminrole'=>$adminrole]);
 } 
 public  function manageSave (Request $request){

     $input = Input::except('_token');
     $rules = [
         'name'=> 'required',
         'email'=> 'required',
         'password'=> 'required',
         'role'=> 'required',

     ];
     $massage = [
         'name.required' =>'账号不能为空',
         'email.required' =>'邮箱不能为空',
         'password.required' =>'密码不能为空',
         'role.required' =>'权限不能为空',

     ];
     $validator = \Validator::make($input,$rules,$massage);
     if($validator->passes()){
        $params=array(
                'name'=>$request->name,
                'email'=>$request->email,
                'role'=>$request->role,
        );
        if(isset($request->password)){
            $params['password']=Bcrypt($request->password);
        }
         $res = \App\User::where('id',$request->id)->update($params);
         if($res === false){
             return back() -> with('errors','数据更新失败');
         }else{
             Session()->flash('message','保存成功');
             return redirect('managelist');
         }
     }else{
         return back() -> withErrors($validator);
     }

 }
 
 public  function manageAdd (Request $request){

     $adminrole = AdminRoleModel::get()->toArray();
 	return view('manageuseredit',['adminrole'=>$adminrole]);
 }

 public  function manageCreate (Request $request){
     $input = Input::except('_token');
     $rules = [
         'name'=> 'required',
         'email'=> 'required',
         'password'=> 'required',
         'role'=> 'required',

     ];
     $massage = [
         'name.required' =>'账号不能为空',
         'email.required' =>'邮箱不能为空',
         'password.required' =>'密码不能为空',
         'role.required' =>'权限不能为空',

     ];
     $validator = \Validator::make($input,$rules,$massage);
     if($validator->passes()){
        $params=array(
                'name'=>$request->name,
                'email'=>$request->email,
                'role'=>$request->role,
                'password'=>Bcrypt($request->password),
        );
         //dd($params);
         $res = \App\User::create($params);

         if($res){
             return redirect('managelist');
         }else{
             return back() -> with('errors','数据更新失败');
         }
     }else{
         return back() -> withErrors($validator);
     }
 }

}