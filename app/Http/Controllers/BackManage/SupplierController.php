<?php

namespace App\Http\Controllers\BackManage;

use App\AdminRoleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;


class SupplierController  extends Controller
{


 public  function supplierList (Request $request){
 	
 	$par=\App\SupplierModel::select();

 	$search=array();
 	if ($request->name != ''){
 		$par->where('name','like',"%$request->name%");
 		$search['name']=$request->name;
 	}
 	if ($request->mobile != ''){
 		$par->where('mobile','like',"%$request->mobile%");
 		$search['mobile']=$request->mobile;
 	}

 	$data=$par->orderBy('id','desc')->paginate(10);


     $tmp = [];
     if(!empty($data)){
         foreach($data as $k=>$v){
             array_push($tmp,$v->class_id);
         }
         $tmp = array_values(array_unique($tmp));

         $class_name = \DB::table('ys_store_class')->whereIn('id',$tmp)->get();


         foreach($data as $k=>$v){
             $data[$k]->class_name = '';
             foreach($class_name as $kk=>$vv){
                 if($v->class_id == $vv->id){
                     $data[$k]->class_name = $vv->name;
                 }
             }
         }
     }

 	$state_arr=[
 		0=>'禁用',
 		1=>'正常',
 	];
 	foreach ($data as $val){
 		$val['state']=$state_arr[$val['state']];
 	}


	return view('supplierlist',['data'=>$data,'search'=>$search]);
 }
 //
 public  function supplierEdit (Request $request){
 
 	$data=\App\SupplierModel::where('id',$request->id)->first();

     $class_info = \DB::table('ys_store_class')->select('id','name','first_id','sort')->orderBy('sort','asc')->get();

     if(!empty($class_info)){

         $tmp = [];
         foreach($class_info as $k=>$v){
             if($v->first_id == 0){
                 array_push($tmp,$v->id);
             }

         }


         $arr = array_fill_keys($tmp, []); //根据一级分类的id为键，值为空数组进行填充数组

         foreach($arr as $k=>$v){
             foreach($class_info as $kk=>$vv){
                 if($k == $vv->first_id){
                     array_push($arr[$k],$k.'+'.$vv->id.'+'.$vv->name);
                 }
             }
         }

         $arr = array_values($arr);

     }else{

         $arr = [];
     }


     //查询一下，目前到底属于哪个一级分类和二级分类
     if(!empty($data->class_id)){

         $second_info = \DB::table('ys_store_class')->where('id',$data->class_id)->first();
         if(!empty($second_info)){
             $first_info = \DB::table('ys_store_class')->where('id',$second_info->first_id)->first();
         }else{
             $first_info = "";
         }


     }else{
         $second_info = "";
         $first_info = "";
     }


	return view('supplieredit',['data'=>$data,'class_info'=>$class_info,'arr'=>urlencode(json_encode($arr)),'second_info'=>$second_info,'first_info'=>$first_info ]);
 }


 public  function supplierSave (Request $request){


     $input = Input::except('_token');
     $rules = [
         'name'=> 'required',
         'mobile'=> 'required',
     ];
     $massage = [
         'name.required' =>'名称不能为空',
         'mobile.required' =>'手机号不能为空',
     ];
     $validator = \Validator::make($input,$rules,$massage);
     if($validator->passes()){
     	if($request->class_id <= 0){
     		return back() -> with('errors','门店分类未选择');
     	}


         if ($request->hasFile('image')){//图片上传
             $file_name=uploadPic($request->file('image')[0]);
             $params=array(
                 'name'=>$request->name,
                 'mobile'=>$request->mobile,
                 'state'=>$request->state,
                 'bank_name'=>$request->bank_name,
                 'bank_address'=>$request->bank_address,
                 'bank_num'=>$request->bank_num,
                 'real_name'=>$request->real_name,
                 'logo'=>$file_name,
                 'class_id'=>$request->class_id,
                 'free_shipping'=>$request->free_shipping
             );
         }else{
             $params=array(
                 'name'=>$request->name,
                 'mobile'=>$request->mobile,
                 'state'=>$request->state,
                 'bank_name'=>$request->bank_name,
                 'bank_address'=>$request->bank_address,
                 'bank_num'=>$request->bank_num,
                 'real_name'=>$request->real_name,
                 'class_id'=>$request->class_id,
                 'free_shipping'=>$request->free_shipping
             );
         }


        if(!empty($request->password)){
            $params['password']=md5($request->password);
        }
         $res = \App\SupplierModel::where('id',$request->id)->update($params);
         if($res === false){
             return back() -> with('errors','数据更新失败');
         }else{
             Session()->flash('message','保存成功');
             return redirect('supplierlist');
         }
     }else{
         return back() -> withErrors($validator);
     }

 }
 
 public  function supplierAdd (Request $request){


     $class_info = \DB::table('ys_store_class')->select('id','name','first_id','sort')->orderBy('sort','asc')->get();

     if(!empty($class_info)){

         $tmp = [];
         foreach($class_info as $k=>$v){
             if($v->first_id == 0){
                 array_push($tmp,$v->id);
             }
         }

         $arr = array_fill_keys($tmp, []); //根据一级分类的id为键，值为空数组进行填充数组

          foreach($arr as $k=>$v){
                foreach($class_info as $kk=>$vv){
                    if($k == $vv->first_id){
                         array_push($arr[$k],$k.'+'.$vv->id.'+'.$vv->name);
                    }
                }
          }

           $arr = array_values($arr);

     }else{

         $arr = [];
     }
 	 return view('supplieredit',['class_info'=>$class_info,'arr'=>urlencode(json_encode($arr))]);
 }

 public  function supplierCreate (Request $request){

     $input = Input::except('_token');
     $rules = [
         'name'=> 'required',
         'password'=> 'required',
         'mobile'=> 'required',
     ];
     $massage = [
         'name.required' =>'名称不能为空',
         'mobile.required' =>'手机号不能为空',
         'password.required' =>'密码不能为空',
     ];
     $validator = \Validator::make($input,$rules,$massage);
     

     
     

     if($validator->passes()){     	
     	if($request->class_id <= 0){
     		return back() -> with('errors','门店分类未选择');
     	}


         if ($request->hasFile('image')){//图片上传
             $file_name=uploadPic($request->file('image')[0]);
         }else{
             $file_name="";
         }


        $params=array(
                'name'=>$request->name,
                'mobile'=>$request->mobile,
                'password'=>md5($request->password),
        		'state'=>$request->state,
        		'bank_name'=>$request->bank_name,
        		'bank_address'=>$request->bank_address,
        		'bank_num'=>$request->bank_num,
        		'real_name'=>$request->real_name,
                'logo'=>$file_name,
                'class_id'=>$request->class_id,
                'free_shipping'=>$request->free_shipping
        );
         //dd($params);
         $res = \App\SupplierModel::create($params);

         if($res){
             return redirect('supplierlist');
         }else{
             return back() -> with('errors','添加供应商错误');
         }
     }else{
         return back() -> withErrors($validator);
     }


 }
 //删除供应商
 public function supplierDelete(Request $request)
 {
 	\App\SupplierModel::where('id',$request->id)->delete();
 	return redirect('supplierlist');
 }
 
//申请提现列表
 public  function SupplierCashList (Request $request){
 


 	$par=\App\SupplierCashApplyModel::leftjoin('ys_supplier','ys_supplier.id','=','ys_supplier_cash_apply.supplier_id')
 					->select('ys_supplier_cash_apply.id','ys_supplier.name','ys_supplier_cash_apply.amount','ys_supplier_cash_apply.created_at','ys_supplier_cash_apply.state','ys_supplier_cash_apply.finish_at');
 		

  	$search=array();
 	if ($request->start != ''){
 		$par->where('created_at','>=',$request->start.' 00:00:00');
 		$search['start']=$request->start;
 	}
 	if ($request->end != ''){
 		$par->where('created_at','<',$request->end.' 59:59:59');
 		$search['end']=$request->end;
 	}
 	if (isset($request->state) && $request->state != -1){
 		$par->where('ys_supplier_cash_apply.state','=',$request->state);
 		$search['state']=$request->state;
 	}
 	if ($request->supplier != ''){
 		$par->where('supplier_id',$request->supplier);
 		$search['supplier']=$request->supplier;
 	} 	
 	
 	$data=$par->orderBy('created_at','desc')->paginate(10);
 	
 	$state_arr=[
  		0=>'申请中',
  		1=>'已完成',
 	];
 	foreach ($data as &$val){
 		$val->state=$state_arr[$val->state];
 	}
 	
 	$suppliers=\App\SupplierModel::where('state',1)->get(); 	
 	return view('managesupplierbillslist',['data'=>$data,'search'=>$search,'suppliers'=>$suppliers]);
 }
 
 
 
 
 

 public function SupplierCashExcel(Request $request){
 
  	$par=\App\SupplierCashApplyModel::leftjoin('ys_supplier','ys_supplier.id','=','ys_supplier_cash_apply.supplier_id')
 					->select('ys_supplier.name','ys_supplier_cash_apply.amount','ys_supplier_cash_apply.bank_name','ys_supplier_cash_apply.bank_address','ys_supplier_cash_apply.bank_num','ys_supplier_cash_apply.real_name','ys_supplier_cash_apply.state','ys_supplier_cash_apply.created_at','ys_supplier_cash_apply.finish_at');
 		
  	 
  	$search=array();
 	if ($request->start != ''){
 		$par->where('created_at','>=',$request->start.' 00:00:00');
 		$search['start']=$request->start;
 	}
 	if ($request->end != ''){
 		$par->where('created_at','<',$request->end.' 59:59:59');
 		$search['end']=$request->end;
 	}
 	if (isset($request->state) && $request->state != -1){
 		$par->where('ys_supplier_cash_apply.state','=',$request->state);
 		$search['state']=$request->state;
 	}
 	if ($request->supplier != ''){
 		$par->where('supplier_id',$request->supplier);
 		$search['supplier']=$request->supplier;
 	} 	
 	
 	$data=$par->orderBy('created_at','desc')->get();
 	
 	$state_arr=[
  		0=>'申请中',
  		1=>'已完成',
 	];
 	foreach ($data as &$val){
 		$val->state=$state_arr[$val->state];
 	}
 	
 
 	$arr_data=$data->toArray();
 	if (empty($arr_data)){
 		return back();
 	}
 	//，
 	foreach($arr_data as $k=>$v){
 		$new_arr[$k]=$v;
 	}
 	// 输出Excel文件头，可把user.csv换成你要的文件名
 	header('Content-Type: application/vnd.ms-excel');
 	header('Content-Disposition: attachment;filename="供应商提现流水.csv"');
 	header('Cache-Control: max-age=0');
 
 	// 打开PHP文件句柄，php://output 表示直接输出到浏览器
 	$fp = fopen('php://output', 'a');
 
 	// 输出Excel列名信息
 	$head = array('供应商名','金额','开户行名','开户行地址','卡号','持卡人','状态','申请时间','完成时间');
 	foreach ($head as $i => $v) {
 		// CSV的Excel支持GBK编码，一定要转换，否则乱码
 		$head[$i] = iconv('utf-8', 'gbk',$v);
 	}
 	// 将数据通过fputcsv写到文件句柄
 	fputcsv($fp, $head);
 	$total_amount=0;
 	foreach ($new_arr as $key => $val) {
 		$total_amount+=$val['amount'];
 		foreach($val as $k=>$v){
 			if($k=='amount'){
					$new[$k] = iconv('utf-8', 'gbk//IGNORE',$v);
			}else{
					$new[$k] = iconv('utf-8', 'gbk//IGNORE', strval($v)."\t");
			}
 		}
 		fputcsv($fp, $new);
 	}
 	$null=array('','','','','','','','');
 	//统计信息
 	fputcsv($fp,$null);
 	fputcsv($fp,$null);
 	fputcsv($fp, array(iconv('utf-8', 'gbk', '总金额'.$total_amount)));
 }
 
 
 
 
 public  function supplierCashEdit (Request $request){
 
 	
 	
 	$data=\App\SupplierCashApplyModel::where('ys_supplier_cash_apply.id',$request->id)
 		->leftjoin('ys_supplier','ys_supplier.id','=','ys_supplier_cash_apply.supplier_id')
	 	->select('ys_supplier.name','ys_supplier_cash_apply.*')
 		->first();
 	return view('supplierbilledit',['data'=>$data]);
 }
 
 public  function supplierCashSave (Request $request){
 	$data=\App\SupplierCashApplyModel::where('id',$request->id)->first();

 	if($request->state==1){
 		$res=\App\SupplierCashApplyModel::where('id',$request->id)->update(['state'=>1,'finish_at'=>date('Y-m-d H:i:s')]);
 		$res=\App\SupplierModel::where('id',$data->supplier_id)->decrement('balance',$data->amount);
 		
 		$params=[
	 		'supplier_id'=>$data->supplier_id,
	 		'amount'=>-trim($data->amount),
	 		'created_at'=>date('Y-m-d H:i:s',time()),
	 		'pay_describe'=>'提现扣除',
	 		'type'=>2
 		];
 		$res=\App\SupplierBillsModel::insert($params);
 		
 		if($res === false){
 			return back() -> with('errors','数据更新失败');
 		}else{
 			Session()->flash('message','保存成功');
 			return redirect('manage/suppliercashlist');
 		} 		
 	}else{
 		return back() -> with('errors','请选择状态');
 	}
 
 }
 
 public  function joinSupplierList (Request $request){
 
 	$par=\App\JoinSupplierModel::select();

 	$data=$par->orderBy('id','desc')->paginate(10);
   	$search=array();
 	if ($request->start != ''){
 		$par->where('created_at','>=',$request->start.' 00:00:00');
 		$search['start']=$request->start;
 	}
 	if ($request->end != ''){
 		$par->where('created_at','<',$request->end.' 59:59:59');
 		$search['end']=$request->end;
 	}
 	if (isset($request->state) && $request->state != -1){
 		$par->where('state','=',$request->state);
 		$search['state']=$request->state;
 	}	
 	
 	$data=$par->orderBy('created_at','desc')->paginate(10);
 	
 	$state_arr=[
  		0=>'未处理',
  		1=>'已处理',
 	];
 	foreach ($data as &$val){
 		$val->state=$state_arr[$val->state];
 	}
 
 	return view('joinsupplierlist',['data'=>$data,'search'=>$search]);
 }
 
 
 public function joinsupplierexcel(Request $request){
 
  	$par=\App\JoinSupplierModel::select('name','mobile','company_name','goods_name','goods_descript','img_1','img_2','img_3','state');

 	$data=$par->orderBy('id','desc')->paginate(10);
   	$search=array();
 	if ($request->start != ''){
 		$par->where('created_at','>=',$request->start.' 00:00:00');
 		$search['start']=$request->start;
 	}
 	if ($request->end != ''){
 		$par->where('created_at','<',$request->end.' 59:59:59');
 		$search['end']=$request->end;
 	}
 	if (isset($request->state) && $request->state != -1){
 		$par->where('state','=',$request->state);
 		$search['state']=$request->state;
 	}	
 	
 	$data=$par->orderBy('created_at','desc')->get();
 	
 	$state_arr=[
  		0=>'未处理',
  		1=>'已处理',
 	];
 	$http = getenv('HTTP_REQUEST_URL');
 	foreach ($data as &$val){
 		$val->state=$state_arr[$val->state];
 		$val->img_1=empty($val->img_1)?'':$http.$val->img_1;
 		$val->img_2=empty($val->img_2)?'':$http.$val->img_2;
 		$val->img_3=empty($val->img_3)?'':$http.$val->img_3;
 	}

 	$arr_data=$data->toArray();
 	if (empty($arr_data)){
 		return back();
 	}
 	//，
 	foreach($arr_data as $k=>$v){
 		$new_arr[$k]=$v;
 	}
 	// 输出Excel文件头，可把user.csv换成你要的文件名
 	header('Content-Type: application/vnd.ms-excel');
 	header('Content-Disposition: attachment;filename="供应商申请加盟.csv"');
 	header('Cache-Control: max-age=0');
 
 	// 打开PHP文件句柄，php://output 表示直接输出到浏览器
 	$fp = fopen('php://output', 'a');
 
 	// 输出Excel列名信息
 	$head = array('联系人','联系电话','公司名','商品名','商品描述','图1','图2','图3','状态');
 	foreach ($head as $i => $v) {
 		// CSV的Excel支持GBK编码，一定要转换，否则乱码
 		$head[$i] = iconv('utf-8', 'gbk',$v);
 	}
 	// 将数据通过fputcsv写到文件句柄
 	fputcsv($fp, $head);
 	foreach ($new_arr as $key => $val) {
 		foreach($val as $k=>$v){
 			$new[$k] = iconv('utf-8', 'gbk', strval($v)."\t");
 		}
 		fputcsv($fp, $new);
 	}

 }
 
 public  function joinSupplierDetial (Request $request){
 	$http = getenv('HTTP_REQUEST_URL');
 	$data=\App\JoinSupplierModel::where('id',$request->id)->first();
 	$data->img_1=empty($data->img_1)?'':$http.$data->img_1;
 	$data->img_2=empty($data->img_2)?'':$http.$data->img_2;
 	$data->img_3=empty($data->img_3)?'':$http.$data->img_3;

 	return view('joinsupplierdetial',['data'=>$data]);
 }
 public  function joinSupplierSave (Request $request){

 	if($request->state==1){
 		$res=\App\JoinSupplierModel::where('id',$request->id)->update(['state'=>1]);
 		if($res === false){
 			return back() -> with('errors','数据更新失败');
 		}else{
 			Session()->flash('message','保存成功');
 			return redirect('manage/joinsupplier');
 		} 		
 	}else{
 		return back() -> with('errors','请选择状态');
 	}
 } 


   //根据门店id，获取门店的分类列表
    public function shopClassMan($id){

         $info = \DB::table('ys_store_goods_class as a')
                   ->leftjoin('ys_supplier as b','a.store_id','=','b.id')
                   ->select('a.id','a.name','a.store_id','a.sort','b.name as spec_name')
                   ->where('a.store_id',$id)
                   ->orderBy('a.sort','asc')
                   ->paginate(10);

        $num  = \DB::table('ys_store_goods_class')->where('store_id',$id)->lists('id');

        return view('shopclasslist',['data'=>$info,'num'=>$num,'store_id'=>$id]);

    }

    //添加门店分类
    public function shopClassManAdd($id){


        return view('shopclassAdd',['store_id'=>$id]);

    }


    //添加数据保存
    public function shopClassManSave(Request $request)
    {

         if(!empty($request->shopClassName))
         {


             $res = \DB::table('ys_store_goods_class')->insert([

                      'name'=>$request->shopClassName,
                      'store_id'=>$request->store_id,
                      'sort'=>$request->sort
             ]);

             if($res){
                 return redirect('supplierlist');
             }else{
                 return back() -> with('errors','数据填充失败');
             }
         }else{

             return back() -> with('errors','分类名称必填');

         }

    }


    //编辑
    public function shopClassManEdit($id){


        $shop_class = \DB::table('ys_store_goods_class')->where('id',$id)->first();

        return view('shopclassEdit',['data'=>$shop_class]);


    }


    //保存编辑
    public function sshopClassManSave(Request $request)
    {


        if(!empty($request->shopClassName)){


            $is_have = \DB::table('ys_store_goods_class')->where('id',$request->id)->where('name',$request->shopClassName)->where('sort',$request->sort)->first();

            if(!empty($is_have)){

                return back() -> with('errors','没有要更新的数据');
            }


            $res  = \DB::table('ys_store_goods_class')->where('id',$request->id)->update([

                'name'=>$request->shopClassName,
                'sort'=>$request->sort
            ]);

            if($res){
                return redirect('supplierlist');
            }else{
                return back() -> with('errors','数据更改失败');
            }

        }else{

            return back() -> with('errors','门店分类名称必填');
        }


    }

    //删除门店分类
    public function shopClassManDel($id){

        \DB::table('ys_store_goods_class')->where('id',$id)->delete();
        return redirect('supplierlist');

    }




}