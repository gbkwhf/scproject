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
 	
 	$user_info=DB::table('sky_user_data_master.user_base_info')
 					->where('user_base_info.mobile',$request->phone)
 					->leftJoin('ysbt_employees','user_base_info.user_id','=','ysbt_employees.user_id')
 					->select('user_base_info.user_name','ysbt_employees.*')
 					->first();
 	if(!$user_info){
 		return 3;//人员不存在
 	} 	
 	if($user_info->store_id>0 && $user_info->store_id!=$request->shop_id){
 		return 2;//已在其他店
 	} 	 
 	if($user_info->is_master==1 && $user_info->store_id==$request->shop_id){
		return 1;//已是本店店主
 	}
 	echo json_encode($user_info);
 }
 //获取商品分类
 public  function getGoodsClass (Request $request){
 	$class=DB::table('ys_goods_class')->where('first_id',$request->id)->get();
 	echo json_encode($class);
 }

}