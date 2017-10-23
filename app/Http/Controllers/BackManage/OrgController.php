<?php

namespace App\Http\Controllers\BackManage;

use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HospitalClassModel;




class OrgController  extends Controller
{

 public  function orgClassList (Request $request){
 	$arr=array(
 		array('id'=>1,'name'=>'中国大陆'),
 		array('id'=>2,'name'=>'大中华'),
 		array('id'=>3,'name'=>'日本'),
 		array('id'=>4,'name'=>'亚洲'),
 		array('id'=>5,'name'=>'欧洲'),
 		array('id'=>6,'name'=>'美洲'),
 		array('id'=>7,'name'=>'澳洲'),
 		array('id'=>8,'name'=>'其他'), 		
 	);
	return view('org.classlist',['data'=>$arr]);
 }
 public  function orgSecondClassList (Request $request){

 	$data=\App\OrgClassManageModel::where('type',$request->id)->orderBy('sort','asc')->get();
 	return view('org.secondclasslist',['data'=>$data,'cid'=>$request->id]);
 }
 
 public  function classEdit (Request $request){

 	$data=\App\OrgClassManageModel::where('id',$request->id)->first();
 	return view('org.classedit',['data'=>$data,'type'=>$data->type]);
 }
 
 public  function classSave (Request $request){

 	$params=array(
 		'name'=>$request->name,
 		'sort'=>$request->sort, 
 		'type'=>$request->type			
 	);
 	$a=\App\OrgClassManageModel::where('id',$request->id)->update($params);
 	
 	Session()->flash('message','保存成功');
 	 	
 	return redirect('orgsecondclasslist/'.$request->type);
 	 }
 
 public  function classAdd (Request $request){

 	
 	return view('org.classedit',['type'=>$request->id]);
 }
 
 public  function classCreate (Request $request){
 	$params=array(
 		'name'=>$request->name,
 		'sort'=>$request->sort, 
 		'type'=>$request->type			
 	);
	$a=\App\OrgClassManageModel::insert($params);
 	return redirect('orgsecondclasslist/'.$request->type);
 }
 //删除
 public  function classDelete (Request $request){
 	
 	$data=\App\OrgClassManageModel::where('id',$request->id)->first();
 	
 	\App\OrgClassManageModel::where('id',$request->id)->delete();
 	return redirect('orgsecondclasslist/'.$data->type);
 }

 //医疗机构
 public  function orgList (Request $request){

 	$province=DB::table('com_sic_region_info')->where('level',1)->get();
 	
 	$org_data=\App\HospitalModel::where('class_id',$request->id);
 	if($request->name !=''){
 		$org_data->where('name','like',"%$request->name%");
 	}
 	if($request->province >0 && $request->city==0){
 		$city=\App\RegionInfo::where('level',2)->where('parentId',$request->province)->select('id')->get()->toArray();
 		foreach ($city as $val){
 			$city_arr[]=$val['id'];
 		}
 		$org_data->whereIn('city',$city);
 	}
 	if($request->city >0){
 		$org_data->where('city',$request->city);
 	} 	
 	$data=$org_data->orderBy('sort','asc')->get();
 		
 	foreach ($data as &$val){
 			
 		$city_info=\App\RegionInfo::where('id',$val->city)->first();

 		if ($city_info){
 			$province_info=\App\RegionInfo::where('id',$city_info->parentId)->first(); 		
 			$val['city']=$province_info->name.$city_info->name; 			
 		}
 	}
 	if($request->search==1){
 		$p=DB::table('com_sic_region_info')->where('id',$request->city)->first();
 		if($p){
 			$data->province=$p->parentId;
 			$data->city_name=$p->name;
 			$city=DB::table('com_sic_region_info')->where('level',2)->where('parentId',$p->parentId)->get();
 		}else{
 			$data->province=$request->province;
 			$data->city_name='';
 			$city=DB::table('com_sic_region_info')->where('level',2)->where('parentId',$request->province)->get();		
 		} 		 		
 		return view('org.hospitallist',['province'=>$province,'city'=>$city,'data'=>$data,'cid'=>$request->id]);
 	}else{ 		
 		return view('org.hospitallist',['province'=>$province,'data'=>$data,'cid'=>$request->id]);
 	}
 	

 	
 	
 	
 	
 	
 	
 	
 	
 
 }
 public  function orgAdd (Request $request){
 	$province=DB::table('com_sic_region_info')->where('level',1)->get();
 	
 	$class=HospitalClassModel::get();

 	return view('org.hospitaledit',['class'=>$class,'province'=>$province,'cid'=>$request->id]);
 }
 
 public  function orgCreate (Request $request){
 	//dd($request->hospital_class);
 	if(is_array($request->hospital_class)){
 		$class=trim(implode(',',$request->hospital_class),',');
        //dd($class);
 	}else{
 		$class='';
 	}
 	$params=array(
 			'name'=>$request->name,
 			'sort'=>$request->sort,
 			'content'=>$request->content,
 			'address'=>$request->address,
 			'phone'=>$request->phone,
 			'grade'=>$request->grade,
 			'class_id'=>$request->cid,
 			'city'=>$request->city,
 			'class'=>$class,
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
 			
 		$file->move(base_path('storage').'/upload/hospital/',$new_name);
 			
 		$params['logo']='/storage/upload/hospital/'.$new_name;
 	}
 	$a=\App\HospitalModel::insert($params);
 	return redirect('orglist/'.$request->cid);
 }
 public  function orgEdit (Request $request){
 	$data=\App\HospitalModel::where('id',$request->id)->first();
 	$province=DB::table('com_sic_region_info')->where('level',1)->get();
 	$class=HospitalClassModel::get()->toArray();
 	
 	$had_class=explode(',', $data->class);
 	foreach ($class as $key=>$val){
 		$new_class[$key]=$val;
 		$new_class[$key]['checked']=in_array($val['id'], $had_class)?1:0;
 	}
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
 	

 	return view('org.hospitaledit',['class'=>$new_class,'province'=>$province,'city'=>$city,'data'=>$data,'cid'=>$data->class_id]);
 }
 public  function orgSave (Request $request){
 	if(is_array($request->hospital_class)){
 		$class=trim(implode(',',$request->hospital_class),',');
 	}else{
 		$class='';
 	}

 	$params=array(
 			'name'=>$request->name,
 			'sort'=>$request->sort,
 			'content'=>$request->content,
 			'address'=>$request->address,
 			'phone'=>$request->phone,
 			'grade'=>$request->grade,
 			'class_id'=>$request->cid,	
 			'city'=>$request->city,
 			'class'=>$class,
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
 			
 		$file->move(base_path('storage').'/upload/hospital/',$new_name);
 			
 		$params['logo']='/storage/upload/hospital/'.$new_name;
 	}
 	$a=\App\HospitalModel::where('id',$request->id)->update($params);
 	return redirect('orglist/'.$request->cid);
 }
 //
 public  function orgDelete (Request $request){
 
 	$data=\App\HospitalModel::where('id',$request->id)->first(); 	
 	\App\HospitalModel::where('id',$request->id)->delete();
 	return redirect('orglist/'.$data->class_id);
 } 

}