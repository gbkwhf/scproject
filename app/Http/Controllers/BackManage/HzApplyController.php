<?php

namespace App\Http\Controllers\BackManage;

use App\CooperationApplyModel;
use App\Http\Controllers\Baseuser\CooperationApplyController;
use App\MessageModel;
/*use Dingo\Api\Auth\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;*/
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;



class HzApplyController  extends Controller
{


 public  function hzApplyList (Request $request){

 	$cooperationapply=\App\CooperationApplyModel::orderBy('id','desc');
     if ($request->type != ''){/**/
         $cooperationapply->where('type',$request->type);
     }
     if ($request->state != ''){
         $cooperationapply->where('state',$request->state);
     }
     if ($request->start != ''){
         $start = $request->start.' 00:00:00';
         $cooperationapply->where('created_at','>=',$start);
     }
     if ($request->end != ''){
         $end = $request->end.' 59:59:59';
         $cooperationapply->where('created_at','<=',$end);
     }
     if ($request->mobile != ''){
         $cooperationapply->where('mobile','like','%'.$request->mobile.'%');
     }
    $data = $cooperationapply->paginate(10);
 	$typeArr=array(
 		'3'=>'医疗机构',
 		'4'=>'医疗专家',
 		'5'=>'合作企业',
 	);
 	foreach ($data as &$val){ 	
 		$val['type']=$typeArr[$val->type];
 		
 	}
	return view('hzapply',['data'=>$data]);
 }
 //
 public  function hzApplyEdit (Request $request){
 	$typeArr=array(
 			'3'=>'医疗机构',
 			'4'=>'医疗专家',
 			'5'=>'合作企业',/**/
 	);
 
 	$data=\App\CooperationApplyModel::where('id',$request->id)->first();
 	
 	$data->type=$typeArr[$data->type];
 
	return view('hzapplyedit',['data'=>$data]);
 } 
 public  function hzApplySave (Request $request){

 	$a=\App\CooperationApplyModel::where('id',$request->id)->update(['state'=>$request->state]);

 	return redirect('hzapplylist');
 }
 

}