<?php

namespace App\Http\Controllers\BackManage;

use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;




class ShopController  extends Controller
{


 public  function shopList (Request $request){
 	
 	$data=\App\Stores::leftJoin('com_sic_region_info','com_sic_region_info.id','=','ysbt_stores.city')
 				->select('ysbt_stores.*','com_sic_region_info.name as city_name')
 				->orderBy('id','desc')
 				->paginate(10);
 	
	return view('shoplist',['data'=>$data]);
 }
 
 public  function shopEdit (Request $request){
 	
 	
 	$province=DB::table('com_sic_region_info')->where('level',1)->get();
 	$jms=\App\Employees::where('is_jms',1)
 	->join('sky_user_data_master.user_base_info','user_base_info.user_id','=','ysbt_employees.user_id')
 	->select('user_base_info.user_name','user_base_info.mobile','ysbt_employees.user_id')
 	->get();

 	$data=\App\Stores::where('id',$request->id)->first();


 	$p=DB::table('com_sic_region_info')->where('id',$data->city)->first();
 	if($p){
 		$data->province=$p->parentId;
 		$data->city_name=$p->name;

 		$city=DB::table('com_sic_region_info')->where('level',2)->where('parentId',$p->parentId)->get();
 	}else{
 		$data->province=0;
 		$data->city_name=''; 	
 		$city=array();
 	}

 	
 	
 	
 	return view('shopedit',['data'=>$data,'province'=>$province,'city'=>$city,'jms'=>$jms]);
 }
 
 public  function shopSave (Request $request){

 	$params=array(
 			'name'=>$request->name,
 			'phone'=>$request->phone,
 			'address'=>$request->address,
 			'selfsupport'=>$request->selfsupport,
 			'owner_id'=>$request->owner_id,
 			'balance'=>$request->balance,
 			'city'=>$request->city,
 			'price'=>$request->price,
 			'longitude'=>$request->longitude,
 			'latitude'=>$request->latitude
 	);
 	
 	if ($request->hasFile('image')) {
 		$file=$request->file('image');
 	
 		//检查文件类型
 		$entension = $file -> getClientOriginalExtension(); //上传文件的后缀.
 		$mimeTye = $file -> getMimeType();
 		if(!in_array($mimeTye,array('image/jpeg','image/png'))){
 			return false;
 		}
 		$new_name=time().rand(100,999).'.'.$entension;
 		$file->move(base_path('storage').'/upload/store/',$new_name);
 	
 		$params['image']='/storage/upload/store/'.$new_name;
 	}
 	
 	if($request->selfsupport==1){
 		$request->owner_id=0;
 	}

 	$a=\App\Stores::where('id',$request->id)->update($params);
 	
 	Session()->flash('message','保存成功');
 	 	
 	return redirect('shoplist');
 }
 
 public  function shopAdd (Request $request){
 
 	$province=DB::table('com_sic_region_info')->where('level',1)->get();
 	$jms=\App\Employees::where('is_jms',1)
 			->join('sky_user_data_master.user_base_info','user_base_info.user_id','=','ysbt_employees.user_id')
 			->select('user_base_info.user_name','user_base_info.mobile','ysbt_employees.user_id')
 			->get();

 	return view('shopedit',['province'=>$province,'jms'=>$jms]);
 }
 
 public  function shopCreate (Request $request){

 	$new_name='';
	

 	if($request->selfsupport==1){
 		$request->owner_id=0;
 	}

 	$params=array(
 		'name'=>$request->name,
 		'phone'=>$request->phone,
 		'address'=>$request->address,
 		'selfsupport'=>$request->selfsupport,
 		'owner_id'=>$request->owner_id, 		
 		'balance'=>$request->balance,
 		'city'=>$request->city,
 		'price'=>$request->price,
 		'longitude'=>$request->longitude,
 		'latitude'=>$request->latitude,	
 		
 	);
 	
 	if ($request->hasFile('image')) {
 		$file=$request->file('image');
 	
 		//检查文件类型
 		$entension = $file -> getClientOriginalExtension(); //上传文件的后缀.
 		$mimeTye = $file -> getMimeType();
 		if(!in_array($mimeTye,array('image/jpeg','image/png'))){
 			return false;
 		}
 		$clientName = $file -> getClientOriginalName();
 		$name=explode('.', $clientName);

 		$new_name=time().rand(100,999).'.'.$entension;
 		
 		$file->move(base_path('storage').'/upload/store/',$new_name);
 		
 		$params['image']='/storage/upload/store/'.$new_name;
 	}
 	
	$a=\App\Stores::create($params);
 	return redirect('shoplist');
 }
 //门店删除
 public  function shopDelete (Request $request){

 	\App\Stores::where('id',$request->id)->delete();
 	//删除关联员工
 	\App\Employees::where('store_id',$request->id)->delete();
 	
 	return redirect('shoplist');
 }
 //批量修改价格
 public  function shopChangePrice (Request $request){
 
 	$province=DB::table('com_sic_region_info')->where('level','<',3)->orderBy('position','asc')->get();
 	
//dd($province);
 	
 	return view('shopchangeprice',['treeList'=>$province]);
 }
 //批量修改价格
 public  function shopChangePriceSave (Request $request){
 	
 	$citys=explode(',',trim($request->check_data,','));	
 	\App\Stores::whereIn('city',$citys)->update(['price'=>$request->price]);
 	
 	return redirect('shoplist');
 }
 //设置店长
 public  function setShopOwner (Request $request){
 
 	
 	
	$info=\App\Employees::where('store_id',$request->id)->where('is_master',1)
					->leftJoin('sky_user_data_master.user_base_info','user_base_info.user_id','=','ysbt_employees.user_id')
					->select('user_base_info.user_name','user_base_info.mobile')
					->first();
	$shop_info=\App\Stores::where('id',$request->id)->first();
 	return view('setshopowner',['shop_info'=>$shop_info,'info'=>$info]);
 }
 //设置店长提交
 public  function setShopOwnerSave (Request $request){
 	
 	$user_info=DB::table('sky_user_data_master.user_base_info')
 	->where('user_base_info.mobile',$request->phone)
 	->leftJoin('ysbt_employees','user_base_info.user_id','=','ysbt_employees.user_id')
 	->select('user_base_info.user_id as uid','ysbt_employees.*')
 	->first();
 	if(!$user_info){
 		session()->flash('message','人员不存在');
 		return back();
 	}
 	if($user_info->store_id>0 && $user_info->store_id!=$request->shop_id){
 		session()->flash('message','已在其他店');
 		return back();
 	}
 	if($user_info->is_master==1 && $user_info->store_id==$request->shop_id){
 		session()->flash('message','已是本店店长');
 		return back();
 	}
 	
 	//取消原来店长
 	$result=\App\Employees::where('store_id',$request->shop_id)->where('is_master',1)->update(['is_master'=>0]);
 	
 	session()->flash('message','123');

 	$params=array(
 		'user_id'=>	$user_info->uid,
 		'is_master'=>1,
 		'store_id'=>$request->shop_id, 					
 	);
 	$result=\App\Employees::updateOrcreate(['user_id'=>$user_info->user_id],$params);
 	return redirect('shoplist');
 }
 
 

}