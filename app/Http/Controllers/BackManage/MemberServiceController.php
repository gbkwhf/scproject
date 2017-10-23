<?php

namespace App\Http\Controllers\BackManage;

use App\AddedServiceModel;
use App\AddedServiceOptionModel;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HospitalClassModel;
use Illuminate\Support\Facades\Input;


class MemberServiceController  extends Controller
{

 public  function serviceList (Request $request){
 	$type_arr=array(
 		1=>'海外医疗',
 		2=>'健康管理',
 		3=>'产业医生',
 		4=>'增值服务',
 		5=>'个性化订制',
 		6=>'保险经纪',
 		7=>'医疗服务', 		
 	);
 	$type=$request->id;
 	$data=\App\AddedServiceModel::where('type',$type)->get()->toArray();
     for ($i=0;$i<count($data);$i++){
         $res_0ption = AddedServiceOptionModel::where('added_id',$data[$i]['id'])->get();
         $data[$i]['option'] = count($res_0ption);
     }

	return view('memberservice.servicelist',['head_name'=>$type_arr[$type],'data'=>$data,'type'=>$type]);
 }
 
 public  function serviceEdit (Request $request){

 	$data=\App\AddedServiceModel::where('id',$request->id)->first();
 	
 	return view('memberservice.serviceedit',['data'=>$data,'type'=>$data->type]);
 }
 
 public  function serviceSave (Request $request){

 	$params=array(
 		'title'=>$request->name,
 		'sort'=>$request->sort, 
 		'open_type'=>$request->open_type,
 		'content'=>$request->content,
 		'type'=>$request->type,	
 	);
 	$a=\App\AddedServiceModel::where('id',$request->id)->update($params);
 	
 	Session()->flash('message','保存成功');
 	 	
 	return redirect('memberservice/'.$request->type);
 
 	 }
 
 public  function serviceAdd (Request $request){


 	
 	return view('memberservice.serviceedit',['type'=>$request->id]);
 }
 
 public  function serviceCreate (Request $request){
 	$params=array(
 		'title'=>$request->name,
 		'sort'=>$request->sort, 
 		'open_type'=>$request->open_type,
 		'content'=>$request->content,
 		'type'=>$request->type,	
 		'created_at'=>date('Y-m-d H:i:s',time()),
 	);
	$a=\App\AddedServiceModel::insert($params);
 	return redirect('memberservice/'.$request->type);
 	
 }
 //删除
 public  function serviceDelete (Request $request){
 	
 	$data=\App\AddedServiceModel::where('id',$request->id)->first();
 	\App\AddedServiceModel::where('id',$request->id)->delete();
 	return redirect('memberservice/'.$data->type);
 }
    //可选项列表
    public function optionlist($id)
    {
        $data = AddedServiceOptionModel::where('added_id',$id)->get();
        $res = AddedServiceModel::where('id',$id)->first();
        return view('memberservice.optionlist',['data'=>$data,'id'=>$id,'type'=>$res['type']]);
    }
    //添加可选项
    public function optionAdd($id)
    {

        return view('memberservice.optionadd',['id'=>$id]);
    }
    //添加保存可选项
    public function optionCreate(Request $request)
    {
        $input = Input::except('_token');
        $rules = [
            'title'=> 'required',
            'price'=> 'required',
            'price_grade1'=> 'required',
            'price_grade2'=> 'required',
            'price_grade3'=> 'required',
            'price_grade4'=> 'required',
        ];
        $massage = [
            'title.required' =>'可选项标题不能为空',
            'price.required' =>'普通会员价格不能为空',
            'price_grade1.required' =>'红卡会员价格不能为空',
            'price_grade2.required' =>'金卡会员价格不能为空',
            'price_grade3.required' =>'白金卡会员价格不能为空',
            'price_grade4.required' =>'黑卡会员价格不能为空',
        ];
        $validator = \Validator::make($input,$rules,$massage);
        if($validator->passes()){

            $params=array(
                'title'=>$request->title,
                'added_id'=>$request->added_id,
                'price'=>$request->price,
                'price_grade1'=>$request->price_grade1,
                'price_grade2'=>$request->price_grade2,
                'price_grade3'=>$request->price_grade3,
                'price_grade4'=>$request->price_grade4,
                'created_at'=>date('Y-m-d H:i:s',time()),
            );
            $res = AddedServiceOptionModel::insert($params);
            if($res){
                return redirect('memberservice/optionlist/'.$request->added_id);
            }else{
                return back() -> with('errors','数据填充失败');
            }
        }else{
            return back() -> withErrors($validator);
        }
    }
    //编辑可选项
    public function optionEdit($id)
    {
       $data = AddedServiceOptionModel::where('id',$id)->first();
        return view('memberservice.optionadd',[ 'data'=>$data,'id'=>$data['added_id'],'added_id'=>$data['added_id']]);
    }
    //编辑保存可选项
    public function optioneSave(Request $request)
    {
        $input = Input::except('_token');
        $rules = [
            'title'=> 'required',
            'price'=> 'required',
            'price_grade1'=> 'required',
            'price_grade2'=> 'required',
            'price_grade3'=> 'required',
            'price_grade4'=> 'required',
        ];
        $massage = [
            'title.required' =>'可选项标题不能为空',
            'price.required' =>'普通会员价格不能为空',
            'price_grade1.required' =>'红卡会员价格不能为空',
            'price_grade2.required' =>'金卡会员价格不能为空',
            'price_grade3.required' =>'白金卡会员价格不能为空',
            'price_grade4.required' =>'黑卡会员价格不能为空',
        ];
        $validator = \Validator::make($input,$rules,$massage);
        if($validator->passes()){

            $params=array(
                'title'=>$request->title,
                'added_id'=>$request->added_id,
                'price'=>$request->price,
                'price_grade1'=>$request->price_grade1,
                'price_grade2'=>$request->price_grade2,
                'price_grade3'=>$request->price_grade3,
                'price_grade4'=>$request->price_grade4,
            );
            $id = $input['id'];
            $res = AddedServiceOptionModel::where('id',$id)->update($params);
            if($res === false){
                return back() -> with('errors','数据填充失败');
            }else{
                return redirect('memberservice/optionlist/'.$request->added_id);
            }
        }else{
            return back() -> withErrors($validator);
        }
    }
    //删除可选项
    public function optioneDelete(Request $request)
    {
        $res_option = AddedServiceOptionModel::where('id',$request->id)->first();
        AddedServiceOptionModel::where('id',$request->id)->delete();
        return redirect('memberservice/optionlist/'.$res_option['added_id']);
    }
    //医院类型列表
    public function hospitalCalss($id)/**/
    {
        $data = HospitalClassModel::where('type',$id+1)->get();
        $res = AddedServiceModel::where('id',$id)->first();
        return view('memberservice.hospitalcalss',['data'=>$data,'id'=>$res['type']]);
    }
}