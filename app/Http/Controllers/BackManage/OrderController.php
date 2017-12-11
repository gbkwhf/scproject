<?php

namespace App\Http\Controllers\BackManage;

use App\GoodsModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\GoodsImageModel;

class OrderController extends Controller
{

	
	public  function OrderList (Request $request){

		\DB::enableQueryLog();
		
		$par=\App\BaseOrderModel::where('ys_base_order.state',1)
					->leftjoin('ys_sub_order','ys_sub_order.base_id','=','ys_base_order.id')
					->leftjoin('ys_member','ys_member.user_id','=','ys_base_order.user_id')
					->leftjoin('ys_employee','ys_employee.user_id','=','ys_base_order.employee_id');
					
		
		//，金额，付款时间，，用户名，手机号，总利润，
	
		$search=array();
		
		//经销商筛选
		if ($request->agency != ''){
			if($request->agency==-1){//用户自己购买
				$par->where('employee_id','=',null);
			}else{
				$par->where('ys_employee.agency_id','=',$request->agency);
			}
			$search['agency']=$request->agency;
		}
		
		//供应商筛选
		if ($request->supplier != ''){
			$par->where('ys_sub_order.supplier_id','=',$request->supplier);
			$search['supplier']=$request->supplier;
		}		
		if ($request->start != ''){
			$par->where('ys_base_order.pay_time','>=',$request->start.' 00:00:00');
			$search['start']=$request->start;
		}
		if ($request->end != ''){
			$par->where('ys_base_order.pay_time','<',$request->end.' 59:59:59');
			$search['end']=$request->end;
		}
		if ($request->mobile != ''){
			$par->where('ys_member.mobile','like',"%$request->mobile%");
			$search['mobile']=$request->mobile;
		}
		if ($request->name != ''){
			$par->where('ys_member.name','like',"%$request->name%");
			$search['name']=$request->name;
		}		
		
		$total_par=$par;
		$data_par=$par;
		

		$total=$total_par->groupby('ys_base_order.id')->get();
		$total_amount=0;
		foreach ($total as $t){
			$total_amount+=$t->amount;
		}
		$data=$data_par->select('ys_base_order.id as order_id','ys_member.name as user_name','ys_member.mobile','amount','pay_time','ys_base_order.employee_id','ys_base_order.all_profit')
				  ->groupby('ys_base_order.id')
				  ->orderBy('pay_time','desc')
				  ->paginate(10);
		foreach ($data as $val){
			//商品名，供应商，订单来源
			$goods_name=\App\SubOrderModel::where('base_id',$val->order_id)
				->leftjoin('ys_order_goods','ys_order_goods.sub_id','=','ys_sub_order.id')
				->leftjoin('ys_goods','ys_order_goods.goods_id','=','ys_goods.id')
				->selectRaw("GROUP_CONCAT(concat(ys_goods.name,'(',ys_order_goods.num,'件)')) as goods_name")
				->get();
			$val->goods_name=str_limit($goods_name[0]->goods_name,20,'...');
			//供应商
			$supplier_name=\App\SubOrderModel::where('base_id',$val->order_id)
				->leftjoin('ys_supplier','ys_supplier.id','=','ys_sub_order.supplier_id')
				->selectRaw("GROUP_CONCAT(ys_supplier.name) as supplier_name")
				->get();
			$val->supplier_name=str_limit($supplier_name[0]->supplier_name,20,'...');
			//经销商
			if(empty($val->employee_id)){
				$val->order_source='线上订单';
			}else{
				$agency_name=\App\EmployeeModel::withTrashed()->where('user_id',$val->employee_id)
					->leftjoin('ys_agency','ys_agency.id','=','ys_employee.agency_id')				
					->first();
				$val->order_source=$agency_name->name;				
			}	
		}
		//所有经销商
		$agency_list=\App\AgencyModel::get();		
		//所有供应商
		$supplier_list=\App\SupplierModel::get();
		return view('orderlist',['data'=>$data,'search'=>$search,'agency_list'=>$agency_list,'supplier_list'=>$supplier_list,'total_amount'=>$total_amount]);
	}
	//
	public  function orderDetial (Request $request){
		 
		$data=\App\BaseOrderModel::where('id',$request->id)->first();
	
		$goods_name=\App\BaseOrderModel::where('ys_base_order.id',$request->id)
		->leftjoin('ys_sub_order','ys_sub_order.base_id','=','ys_base_order.id')
		->leftjoin('ys_order_goods','ys_sub_order.id','=','ys_order_goods.sub_id')
		->leftjoin('ys_goods','ys_order_goods.goods_id','=','ys_goods.id')
		->selectRaw("GROUP_CONCAT(concat(ys_goods.name,'(',ys_order_goods.num,'件)')) as goods_name")
		->get();
	
		$data['order_id']=$request->id;
		$data['goods_name']=$goods_name[0]->goods_name;
		$data['receive_address']=$data['receive_name'].'，'.$data['receive_mobile'].'，'.$data['receive_address'];
	
	
		return view('orderdetial',['data'=>$data]);
	}
	public  function manageRemarkSave (Request $request){
				
		$res=\App\BaseOrderModel::where('id',$request->id)->update(['manage_remark'=>$request->manage_remark]);
		if($res === false){
			return back() -> with('errors','数据更新失败');
		}else{
			return redirect('manage/orderdetial/'.$request->id);
		}		
	}	
	public function getOrderExcel(Request $request){
		
		$par=\App\BaseOrderModel::where('ys_base_order.state',1)
		->leftjoin('ys_sub_order','ys_sub_order.base_id','=','ys_base_order.id')
		->leftjoin('ys_member','ys_member.user_id','=','ys_base_order.user_id')
		->leftjoin('ys_employee','ys_employee.user_id','=','ys_base_order.employee_id')
		->select('ys_base_order.id as order_id','ys_member.name as user_name','ys_member.mobile','amount','pay_time','ys_base_order.employee_id','ys_base_order.all_profit','ys_base_order.receive_name','ys_base_order.receive_mobile','ys_base_order.receive_address','ys_base_order.user_remark','ys_base_order.manage_remark');
		
	
		
		$search=array();
		
		//经销商筛选
		if ($request->agency != ''){
			if($request->agency==-1){//用户自己购买
				$par->where('employee_id','=',null);
			}else{
				$par->where('ys_employee.agency_id','=',$request->agency);
			}
			$search['agency']=$request->agency;
		}
		
		//供应商筛选
		if ($request->supplier != ''){
			$par->where('ys_sub_order.supplier_id','=',$request->supplier);
			$search['supplier']=$request->supplier;
		}
		if ($request->start != ''){
			$par->where('ys_base_order.pay_time','>=',$request->start.' 00:00:00');
			$search['start']=$request->start;
		}
		if ($request->end != ''){
			$par->where('ys_base_order.pay_time','<',$request->end.' 59:59:59');
			$search['end']=$request->end;
		}
		if ($request->mobile != ''){
			$par->where('ys_member.mobile','like',"%$request->mobile%");
			$search['mobile']=$request->mobile;
		}
		if ($request->name != ''){
			$par->where('ys_member.name','like',"%$request->name%");
			$search['name']=$request->name;
		}
		

		
		
		$data=$par->groupby('ys_base_order.id')->orderBy('pay_time','desc')->get();
		
		$total_amount=0;
		foreach ($data as $t){
			$total_amount+=$t->amount;
		}
		


		$count_goods=[];
		foreach ($data as &$val){
			//商品总数统计
			$total_goods=\App\SubOrderModel::where('base_id',$val->order_id)
			->leftjoin('ys_order_goods','ys_order_goods.sub_id','=','ys_sub_order.id')
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
			//商品名，供应商，订单来源
			$goods_name=\App\SubOrderModel::where('base_id',$val->order_id)
			->leftjoin('ys_order_goods','ys_order_goods.sub_id','=','ys_sub_order.id')
			->leftjoin('ys_goods','ys_order_goods.goods_id','=','ys_goods.id')
			->selectRaw("GROUP_CONCAT(concat(ys_goods.name,'(',ys_order_goods.num,'件)')) as goods_name")
			->get();
			$val->goods_name=$goods_name[0]->goods_name;
			//供应商
			$supplier_name=\App\SubOrderModel::where('base_id',$val->order_id)
			->leftjoin('ys_supplier','ys_supplier.id','=','ys_sub_order.supplier_id')
			->selectRaw("GROUP_CONCAT(ys_supplier.name) as supplier_name")
			->get();
			$val->supplier_name=$supplier_name[0]->supplier_name;
			//经销商
			if(empty($val->employee_id)){
				$val->employee_id='线上订单';
			}else{
				$agency_name=\App\EmployeeModel::withTrashed()->where('user_id',$val->employee_id)
				->leftjoin('ys_agency','ys_agency.id','=','ys_employee.agency_id')
				->first();
				$val->employee_id=$agency_name->name;
			}
		}
			$arr_data=$data->toArray();
			if (empty($arr_data)){					 		
	 				return back();			
			}
			//，订单号，用户名，手机号，金额，付款时间，，订单来源，利润, 收货人，收货手机，收货地址,商品名，供应商，
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
			$head = array('订单号','用户名','手机号','金额','付款时间','订单来源','利润','收货人','收货手机','收货地址','用户备注','客服备注','商品名','供应商');
			foreach ($head as $i => $v) {
				// CSV的Excel支持GBK编码，一定要转换，否则乱码
				$head[$i] = iconv('utf-8', 'gbk', $v);
			}
			// 将数据通过fputcsv写到文件句柄
			fputcsv($fp, $head);
			foreach ($new_arr as $key => $val) {
				foreach($val as $k=>$v){
					$new[$k] = iconv('utf-8', 'gbk//IGNORE', strval($v)."\t");
				}
				fputcsv($fp, $new);
			}	

			$null=array('','','','','','','','');
			//统计信息
			fputcsv($fp,$null);
			fputcsv($fp,$null);
			
			fputcsv($fp, array(iconv('utf-8', 'gbk','总金额'.$total_amount)));
			
			fputcsv($fp,$null);
			fputcsv($fp,$null);
			foreach ($count_goods as $c_k=>$c_goods){
				fputcsv($fp, array(iconv('utf-8', 'gbk//IGNORE', $c_k.'('.$c_goods.'件)')));
			}
			
			
			
	}
	
	
}
