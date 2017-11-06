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
 						->select('ys_sub_order.id as order_id','price','pay_type','pay_time','receive_mobile','express_num');
 					 					 	
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
 		$par->where('ys_base_order.state','=',$request->state);
 		$search['state']=$request->state;
 	}
 	
 	

 	$data=$par->paginate(10);
 	$pay_arr=[
 		1=>'微信支付',
 		2=>'线下支付',
 		3=>'微信公众号',
 	];
 	foreach ($data as $val){
 		$goods_name=\App\OrderGoodsModel::where('sub_id',$val->order_id)
 						->leftjoin('ys_goods','ys_order_goods.goods_id','=','ys_goods.id')
 						->selectRaw("GROUP_CONCAT(concat(ys_goods.name,'(',ys_order_goods.num,'件)')) as goods_name")
 						->get(); 		
 		$val->goods_name=str_limit($goods_name[0]->goods_name,10,'...');
 		$val->pay_type=$pay_arr[$val->pay_type];
 		$val->state=empty($val->express_num)?'未发货':'已发货';
 		
 	}
	return view('supplierorderlist',['data'=>$data,'search'=>$search]);
 }
 //
 public  function orderDetial (Request $request){
 
 	$data=\App\SubOrderModel::where('ys_sub_order.id',$request->id)
 							->where('ys_base_order.state','=',1)
 							->join('ys_base_order','ys_sub_order.base_id','=','ys_base_order.id')
 							->select('ys_sub_order.id as order_id','price','pay_type','pay_time','receive_mobile','receive_address','receive_name','express_num','express_name')						
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
 
 

}