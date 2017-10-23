<?php

namespace App\Http\Controllers\BackManage;

use App\MessageModel;
use App\Question;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;




class UserQuestionController  extends Controller
{
 public  function questionList (Request $request){

 	$question=\App\Question::orderBy('id','desc')
 				->leftJoin('stj_member','stj_member.user_id','=','stj_question.user_id')
 				->select('stj_question.*','stj_member.grade');
     if ($request->type != ''){/**/
         $question->where('stj_question.type',$request->type);
     }
     if ($request->state != ''){
         $question->where('stj_question.state',$request->state);
     }
     if ($request->created_at_start != ''){
         $created_at_start = $request->created_at_start.' 00:00:00';
         $question->where('stj_question.created_at','>=',$created_at_start);
     }
     if ($request->created_at_end != ''){
         $created_at_end = $request->created_at_end.' 59:59:59';
         $question->where('stj_question.created_at','<=',$created_at_end);
     }
     if ($request->manage_time_start != ''){
         $manage_time_start = $request->manage_time_start.' 00:00:00';
         $question->where('stj_question.manage_time','>=',$manage_time_start);
     }
     if ($request->manage_time_end != ''){
         $manage_time_end = $request->manage_time_end.' 59:59:59';
         $question->where('stj_question.manage_time','<=',$manage_time_end);
     }
    if ($request->mobile != ''){
        $question->where('stj_question.mobile','like','%'.$request->mobile.'%');
    }
    if ($request->grade != ''){
        $question->where('stj_member.grade',$request->grade);
    }
 	$data = $question->paginate(10);
 	$typeArr=array(
 		'1'=>'付款相关',
 		'2'=>'会员相关',
 	);
 	$gradeArr=array(
 		'1'=>'普通会员',
 		'2'=>'红卡会员',
 		'3'=>'金卡会员',
 		'4'=>'白金卡会员',
 		'5'=>'黑卡会员'
 	);
 	foreach ($data as &$val){
 		$val['type']=$typeArr[$val->type];
 		
 		$val['grade']=isset($val->grade)?$gradeArr[$val->grade]:'非会员';
 		
 	}
	return view('questionlist',['data'=>$data]);
 }
 //
 public  function questionEdit (Request $request){
 	

 	$typeArr=array(
 		'1'=>'付款相关',
 		'2'=>'会员相关',
 	);
 	$gradeArr=array(
 		'1'=>'普通会员',
 		'2'=>'红卡会员',
 		'3'=>'金卡会员',
 		'4'=>'白金卡会员',
 		'5'=>'黑卡会员'
 	);
 	$data=\App\Question::where('id',$request->id)
 			->leftJoin('stj_member','stj_member.user_id','=','stj_question.user_id')
 			->select('stj_question.*','stj_member.grade')
 			->first();
 	$data->type=$typeArr[$data->type];
 	$data->grade=isset($data['grade'])?$gradeArr[$data['grade']]:'非会员';

 	if($data->state==1){//已反馈的反馈人从数据库读取，未反馈的反馈人为当前后台登录的管理员
 		$manage_info=\App\User::where('id',$data->manage_id)->first();
 		$data->manage_id=$manage_info->name;
 	}
 	
 	
 
	return view('questionedit',['data'=>$data]);
 } 
 public  function questionSave (Request $request){

     if ($request->content != ''){
         $request->state = 1;
     }
 	$params=array(
 		'state'=>$request->state,
 		'manage_id'=>Auth::user()->id,
 		'manage_content'=>$request->content,
 		'manage_time'=>date('Y-m-d H:i:s',time()),/**/
 	);

 	$data=\App\Question::where('id',$request->id)->update($params);
     $question = Question::where('id',$request->id)->first();
     $user_id = $question['user_id'];
     $date = array(
         'user_id'=>$user_id,
         'created_at'=>date('Y-m-d H:i:s',time()),
         'content'=>'您的咨询已经反馈',
     );
     MessageModel::insert($date);
 	return redirect('questionlist');
 }

}