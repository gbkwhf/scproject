<?php

namespace App\Http\Controllers\BackManage;

use App\Member;
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
     $member_count=\App\Member::orderBy('created_at','desc')->get();
 	 $member=\App\Member::orderBy('created_at','desc');
      if ($request->grade != ''){/**/
          $member->where('grade',$request->grade);
      }
      if ($request->start != ''&& $request->end == "" ){
          $start = $request->start.' 00:00:00';
          $member->where('created_at','>=',$start);
      }
      if ( $request->end != '' && $request->start == ''){
          $end = $request->end.' 59:59:59';
          $member->where('created_at','<=',$end);
      }
      if ($request->start != "" && $request->end != ""){
          $start = $request->start.' 00:00:00';
          $end = $request->end.' 59:59:59';
          $member->where('created_at','>=',$start)
              ->where('created_at','<=',$end);
      }
      if ($request->mobile != ""){
          $member->where('mobile','like','%'.$request->mobile.'%');
      }
      if ($request->vip_code != ""){
      	$member->where('vip_code','like','%'.$request->vip_code.'%');
      }      
     $data = $member ->paginate(10);
     $count = count($data);
 	$gradeArr=array(
 			'1'=>'普通会员',
 			'2'=>'红卡会员',
 			'3'=>'金卡会员',
 			'4'=>'白金卡会员',
 			'5'=>'黑卡会员'
 	);
 	$sexArr=array(
 			'1'=>'男',
 			'2'=>'女',
 			'0'=>'未选择',
 	); 	
 	foreach ($data as &$val){
 		$val['grade']=$gradeArr[$val['grade']];
        if ($val['sex'] == ''){
            $val['sex'] = 0;
        }
 		$val['sex']=$sexArr[$val['sex']];
 	}
	return view('memberlist',['data'=>$data,'count'=>$count]);
 }
 
 public  function memberEdit (Request $request){
 	
	$data=\App\Member::where('user_id',$request->id)->first();
 	
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
 			'vip_code'=>$request->vip_code,
 			'grade'=>$request->grade,
 			'state'=>$request->state,
 	);
 	$a=\App\Member::where('user_id',$request->user_id)->update($params);
 	return redirect('memberlist');
 }
 //会员删除
 public  function memberDelete (Request $request){


 	\App\Member::where('user_id',$request->id)->delete();
 	
 	return redirect('memberlist');
 }

 //注册会员
public function memberAdd(Request $request)
{
    return view('memberadd');
}

    public function memberAddSave(Request $request)
    {
        $mobile = Member::where('mobile',$request->mobile)->first();
        if ($mobile){
            Session()->flash('message','手机号码已注册');
            //return redirect('memberlist')->with(['message'=>'手机号码已注册！']);
            return back();
        }
        
            $input = Input::except('_token');
            $rules = [
                'username'=> 'required',
                'mobile'=> 'required',
                'sex'=> 'required',
                'password'=> 'required',
            ];
            $massage = [
                'username.required' =>'用户名不能为空',
                'mobile.required' =>'电话不能为空',
                'sex.required' =>'性别不能为空',
                'password.required' =>'密码不能为空',
            ];

            $validator = \Validator::make($input,$rules,$massage);
            if ($validator->passes()){
                $params=array(
                    'name'=>$request->username,
                    'mobile'=>$request->mobile,
                    'sex'=>$request->sex,
                    'created_at' => date('Y-m-d H:i:s'),
                    'password'=>md5($request->password),
                );

                $res = Member::insertGetId($params);
                if ($res){
                    $version = array(
                        'user_id' => $res,
                        'base_ver' => 1,
                        'last_update_date' => date('Y-m-d H:i:s', time()),
                    );
                    UserVersionInfoModel::insert($version);
                }
                Session()->flash('message','注册成功');
                return redirect('memberlist');
            }else{
                return back() -> withErrors($validator);
            }
        


    }

}