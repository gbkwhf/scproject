<?php

namespace App\Http\Controllers\BackManage;

use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;





class AjaxController  extends Controller
{


 public  function cityList (Request $request){ 	
 	$city=DB::table('com_sic_region_info')->where('parentId',$request->id)->where('level',2)->get();
	echo json_encode($city);
 }
 public  function getUserInfo (Request $request){
 	
 	$user_info=DB::table('ys_member')->where('ys_member.mobile',$request->phone)
 					->leftJoin('ys_employee','ys_member.user_id','=','ys_employee.user_id')
 					->select('ys_member.name','ys_employee.*')
 					->first(); 	
 	if(!$user_info){
 		return 3;//人员不存在
 	} 	
 	if($user_info->agency_id>0){ 		
 		if($user_info->agency_id==$request->agency_id){
 			return 1;//已在本店
 		}
 		return 2;//已在其他店
 	} 	 

 	
 	echo json_encode($user_info);
 }
 //获取商品分类
 public  function getGoodsClass (Request $request){
 	$class=DB::table('ys_goods_class')->where('first_id',$request->id)->get();
 	echo json_encode($class);
 }

}