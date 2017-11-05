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

}