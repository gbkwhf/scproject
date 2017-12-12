<?php

namespace App\Http\Controllers\BackManage;

use App\AdminRoleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;


class SupplierManageController  extends Controller
{


 public  function orderList (Request $request){
 	
 	$supplier_id=\Session::get('role_userid');

 	
 	$par=\App\SubOrderModel::where('supplier_id','=',$supplier_id)->where('ys_base_order.state','=',1)
 						->join('ys_base_order','ys_sub_order.base_id','=','ys_base_order.id')
 						->select('ys_sub_order.id as order_id','price','pay_type','pay_time','receive_mobile','express_num','ys_base_order.user_remark','ys_base_order.manage_remark');
 					 					 	
 	//付款时间，订单号，用户手机，订单状态，
 	$search=array();
 	if ($request->start != ''){
 		$par->where('ys_base_order.pay_time','>=',$request->start.' 00:00:00');
 		$search['start']=$request->start;
 	}
 	if ($request->end != ''){
 		$par->where('ys_base_order.pay_time','<',$request->end.' 59:59:59');
 		$search['end']=$request->end;
 	} 	
 	if ($request->mobile != ''){
 		$par->where('ys_base_order.receive_mobile','like',"%$request->mobile%");
 		$search['mobile']=$request->mobile;
 	}
 	if ($request->order_id != ''){
 		$par->where('ys_sub_order.id','like',"%$request->order_id%");
 		$search['order_id']=$request->order_id;
 	} 	
 	if ($request->state!==null && $request->state != '-1'){
 		if($request->state==1){//已发货
 			$par->whereNotNull('ys_sub_order.express_num');
 		}else {
 			$par->whereNull('ys_sub_order.express_num');
 		}
 		
 		$search['state']=$request->state;
 	}
 	
 	

 	$data=$par->orderBy('ys_base_order.pay_time','desc')->paginate(10);
 	$pay_arr=[
 		1=>'微信支付',
 		2=>'线下支付',
 		3=>'微信公众号',
 	];
 	foreach ($data as $val){
 		$goods_name='';
 		$goods_list=\App\OrderGoodsModel::where('sub_id',$val->order_id)
 						->leftjoin('ys_goods','ys_order_goods.goods_id','=','ys_goods.id')
 						->selectRaw("ys_goods.name,ys_goods.supplier_price,ys_order_goods.num") 						
 						->get(); 	
 		$val->pay_type=$pay_arr[$val->pay_type];
 		$val->state=empty($val->express_num)?'未发货':'已发货';
 		foreach ($goods_list as $v){
 		 		$val->supplier_amount+=$v->supplier_price*$v->num;
 		 		$goods_name.=$v->name.'('.$v->num.'件),';
 		 } 		
 		$val->goods_name=str_limit(trim($goods_name,','),15,'...');
 	}
	return view('supplierorderlist',['data'=>$data,'search'=>$search]);
 }
 //
 public  function orderDetial (Request $request){
 
 	$data=\App\SubOrderModel::where('ys_sub_order.id',$request->id)
 							->where('ys_base_order.state','=',1)
 							->join('ys_base_order','ys_sub_order.base_id','=','ys_base_order.id')
 							->select('ys_sub_order.id as order_id','price','pay_type','pay_time','receive_mobile','receive_address','receive_name','express_num','express_name','ys_base_order.user_remark','ys_base_order.manage_remark')						
 							->first(); 	
 	$goods_name=\App\OrderGoodsModel::where('sub_id',$request->id)
 							->leftjoin('ys_goods','ys_order_goods.goods_id','=','ys_goods.id')
 							->selectRaw("GROUP_CONCAT(concat(ys_goods.name,'(',ys_order_goods.num,'件)')) as goods_name")
 							->get(); 	 
 	$data['goods_name']=$goods_name[0]->goods_name;
 	$data['receive_address']=$data['receive_name'].'，'.$data['receive_mobile'].'，'.$data['receive_address'];
 	

	return view('supplierorderdetial',['data'=>$data]);
 } 
 public  function orderSend (Request $request){

     $input = Input::except('_token');
     $rules = [
         'express_name'=> 'required',
         'express_num'=> 'required',
     ];
     $massage = [
         'express_name.required' =>'快递公司不能为空',
         'express_num.required' =>'快递单号不能为空',
     ];
     $validator = \Validator::make($input,$rules,$massage);
     if($validator->passes()){
        $params=array(
                'express_name'=>trim($request->express_name),
                'express_num'=>trim($request->express_num),
        );
         $res = \App\SubOrderModel::where('id',$request->id)->update($params);
         
         $info = \App\SubOrderModel::where('ys_sub_order.id',$request->id)
         		->leftjoin('ys_order_goods','ys_order_goods.sub_id','=','ys_sub_order.id')
         		->leftjoin('ys_goods','ys_goods.id','=','ys_order_goods.goods_id')
         		->select('ys_sub_order.supplier_id','ys_goods.supplier_price','ys_order_goods.num')         		
         		->get();
         $supplier_amount=0;
		foreach ($info as $val){
			$supplier_amount+=$val->supplier_price*$val->num;
		}
         $params=[
        	'supplier_id'=>$info[0]->supplier_id,	
        	'amount'=>+$supplier_amount,
        	'created_at'=>date('Y-m-d H:i:s',time()),
        	'pay_describe'=>'销售收入',
        	'type'=>1
         ];
         $res=\App\SupplierBillsModel::insert($params);
         $res = \App\SupplierModel::where('id',$info[0]->supplier_id)->increment('balance',$supplier_amount);          
         if($res === false){
             return back() -> with('errors','数据更新失败');
         }else{
             Session()->flash('message','保存成功');
             return redirect('supplier/orderlist');
         }
     }else{
         return back() -> withErrors($validator);
     }

 }
 
 public function getOrderExcel(Request $request){
 	
 	
 	
 	$supplier_id=\Session::get('role_userid');
 	
 	
 	$par=\App\SubOrderModel::where('supplier_id','=',$supplier_id)->where('ys_base_order.state','=',1)
 	->join('ys_base_order','ys_sub_order.base_id','=','ys_base_order.id')
 	->select('ys_sub_order.id as order_id','ys_sub_order.id as goods_name','ys_sub_order.id as supplier_amount','pay_time','receive_name','receive_mobile','receive_address','express_name','express_num','ys_base_order.user_remark','ys_base_order.manage_remark');
 	
 	//'订单号','商品名','付款时间','收货人','收货人手机','收货地址','快递名称','快递单号'
 	$search=array();
 	if ($request->start != ''){
 		$par->where('ys_base_order.pay_time','>=',$request->start.' 00:00:00');
 		$search['start']=$request->start;
 	}
 	if ($request->end != ''){
 		$par->where('ys_base_order.pay_time','<',$request->end.' 59:59:59');
 		$search['end']=$request->end;
 	}
 	if ($request->mobile != ''){
 		$par->where('ys_base_order.receive_mobile','like',"%$request->mobile%");
 		$search['mobile']=$request->mobile;
 	}
 	if ($request->order_id != ''){
 		$par->where('ys_sub_order.id','like',"%$request->order_id%");
 		$search['order_id']=$request->order_id;
 	}
  	if ($request->state!==null && $request->state != '-1'){
 		if($request->state==1){//已发货
 			$par->whereNotNull('ys_sub_order.express_num');
 		}else {
 			$par->whereNull('ys_sub_order.express_num');
 		}
 		
 		$search['state']=$request->state;
 	}
 	
 	
 	
 	$data=$par->orderBy('ys_base_order.pay_time','desc')->get();
 	$count_goods=[];
 	foreach ($data as $val){
 		
 		
 		
 		//商品总数统计
 		$total_goods=\App\OrderGoodsModel::where('sub_id',$val->order_id)
 		->leftjoin('ys_goods','ys_order_goods.goods_id','=','ys_goods.id')
 		->selectRaw("ys_goods.name,sum(ys_order_goods.num) as goods_num")
 		->groupBy('name')
 		->get();
 		foreach ($total_goods as $v){
 			if(empty($count_goods["$v->name"])){
 				$count_goods[strval("$v->name")]=(int)$v->goods_num;
 			}else{
 				$count_goods[strval("$v->name")]+=(int)$v->goods_num;
 			}
 		}
 		$goods_name='';
 		$supplier_amount=0;
 		$goods_list=\App\OrderGoodsModel::where('sub_id',$val->order_id)
 		->leftjoin('ys_goods','ys_order_goods.goods_id','=','ys_goods.id')
 		->selectRaw("ys_goods.name,ys_goods.supplier_price,ys_order_goods.num")
 		->get();
 		foreach ($goods_list as $g){
 			$supplier_amount+=$g->supplier_price*$g->num;
 			$goods_name.=$g->name.'('.$g->num.'件),';
 		}
 		$val->goods_name=trim($goods_name,',');
 		$val->supplier_amount=$supplier_amount;

 	}

 	$arr_data=$data->toArray();
 	if (empty($arr_data)){
 		return back();
 	}
 	
 	
 	foreach($arr_data as $k=>$v){
 		$new_arr[$k]=$v;
 	}
 	// 输出Excel文件头，可把user.csv换成你要的文件名
 	header('Content-Type: application/vnd.ms-excel');
 	header('Content-Disposition: attachment;filename="订单列表.csv"');
 	header('Cache-Control: max-age=0');
 
 	// 打开PHP文件句柄，php://output 表示直接输出到浏览器
 	$fp = fopen('php://output', 'a');
 
 	// 输出Excel列名信息
 	$head = array('订单号','商品名','金额','付款时间','收货人','收货人手机','收货地址','快递名称','快递单号','用户备注','客服备注');
 	foreach ($head as $i => $v) {
 		// CSV的Excel支持GBK编码，一定要转换，否则乱码
 		$head[$i] = iconv('utf-8', 'gbk', $v);
 	}
 	// 将数据通过fputcsv写到文件句柄
 	fputcsv($fp, $head);
 	foreach ($new_arr as $key => $val) {
 		//var_dump($val);
 		foreach($val as $k=>$v){
 			if($k=='supplier_amount'){
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
 	foreach ($count_goods as $c_k=>$c_goods){
 		fputcsv($fp, array(iconv('utf-8', 'gbk', $c_k.'('.$c_goods.'件)')));
 	}
 }
 
 //体现记录
 public  function billsList (Request $request){
 
 	$supplier_id=\Session::get('role_userid');

 	$par=\App\SupplierCashApplyModel::where('supplier_id',$supplier_id);
 		

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
  		0=>'申请中',
  		1=>'已完成',
 	];
 	foreach ($data as &$val){
 		$val->state=$state_arr[$val->state];
 	}
 	

 	
 	return view('supplierbillslist',['data'=>$data,'search'=>$search]);
 }
 
 
 public  function supplierCashAdd (Request $request){
 
 	$supplier_id=\Session::get('role_userid'); 	
 	$data=\App\SupplierModel::where('id',$supplier_id)->first();
 	
 	$res=0;
 	$res = \App\SupplierCashApplyModel::where('supplier_id',$supplier_id)->where('state',0)->sum('amount');
	$data->apply_amount=$res;
	$data->balance=$data->balance-$res;
 	return view('supplierbilladd',['data'=>$data]);
 }
 
 public  function supplierCash (Request $request){
 	$supplier_id=\Session::get('role_userid');
 	$data=\App\SupplierModel::where('id',$supplier_id)->first();
 	
	if($request->amount<=0){
		return back() -> with('errors','提现金额必须大于0');		
	}

 		$res=0;
 		$res = \App\SupplierCashApplyModel::where('supplier_id',$supplier_id)->where('state',0)->sum('amount');
 		$data->apply_amount=$res;
 		$data->balance=$data->balance-$res;
 		
 		if($request->amount>$data->balance){//申请金额大于可提现金额
 			return back() -> with('errors','大于可提现金额'.$data->balance); 			
 		}
 		 		
 		$params=array(
 				'supplier_id'=>$supplier_id,
 				'amount'=>trim($request->amount),
 				'created_at'=>date('Y-m-d H:i:m',time()),
 				'state'=>0,
 				'pay_describe'=>'供应商申请提现',
 				'bank_name'=>$data->bank_name,
 				'bank_address'=>$data->bank_address,
 				'bank_num'=>$data->bank_num,
 				'real_name'=>$data->real_name,
 		);
 		$res = \App\SupplierCashApplyModel::insert($params);

 		if($res === false){
 			return back() -> with('errors','数据更新失败');
 		}else{
 			Session()->flash('message','保存成功');
 			return redirect('supplier/supplierbillslist');
 		}
 
 }
}