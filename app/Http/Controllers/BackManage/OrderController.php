<?php

namespace App\Http\Controllers\BackManage;

use App\GoodsModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\GoodsImageModel;
use DB;

class OrderController extends Controller
{

	
	public  function OrderList (Request $request){
		
// // 		$money=5000;
		
	
// // 		$a=config('clinic-config.user_lvs');
		
		
		
// // 		$user_lv=[];
// // 		foreach ($a as $val){
// // 			if($money>=$val['min'] && $money<=$val['max']){
// // 				$user_lv=$val;
// // 			}
// // 		}
		
// // 		//dd($user_lv);
		
// 		\DB::enableQueryLog();
		
		
// 		$path=public_path();
		
// 		//读取上次执行时间
// 		if(file_exists($path.'/month_rebate_execute.txt')){
			 
// 			\Log::info('进入月返现程序');
// 			$log_file=unserialize(file_get_contents($path.'/month_rebate_execute.txt'));
// 			$log_date=date('Y-m',strtotime($log_file['time']));
// 			$today_data=date('Y-m');
// 			\Log::info('月返现读取文件内容，'.serialize($log_file));
// 			//检查重复执行
// // 			if($log_date==$today_data){
// // 				\Log::info('月返现同一天内重复执行，已退出');
// // 				exit();
// // 			}
// 		}else{
// 			$log_date=date('Y-m',strtotime('-1 days'));			
// 		}
		
	
// 		$data=[];
		
		
// 		dump($log_date);

		
		
			
			
			
// 				//时间节点
// 				$start_time=date('Y-m-d',strtotime("$log_date -1month")).' 00:00:00';
// 				$end_time=date('Y-m-d',strtotime("$start_time +1 month -1 day")).' 23:59:59';
// 				dump($start_time);
// 				dump($end_time);
				
// 				//返现计算
// 				$personal=\App\BaseOrderModel::where('pay_time','>=',$start_time)->where('pay_time','<',$end_time)
// 				->leftjoin('ys_sub_order','ys_sub_order.base_id','=','ys_base_order.id')
// 				->where('ys_base_order.state',1)
// 				->where('ys_sub_order.receive_state',1)
// 				->where('ys_sub_order.all_rebate','>',0)
// 				->groupBy('ys_base_order.user_id')
// 				->selectRaw('ys_base_order.user_id,sum(ys_sub_order.price) as amount')
// 				->get();
				
// 				dump($personal);
				
// 				$user_insert=true;
// 				$user_update=true;
// 				foreach ($personal as $val){
					
// 					//dump($val);
// 					//b层级
// 					$b=\App\MemberModel::where('invite_id',$val->user_id)
// 					->where('invite_id','!=','')
// 					->select('user_id')					
// 					->get();
// 					$b_str='';
// 					if(!empty($b)){
						
					
// 					foreach ($b as $v_b){
					
// 						$b_str.=','.$v_b->user_id;						
// 						dump($b_str);
// 						$c=\App\MemberModel::where('invite_id',$v_b->user_id)
// 						->where('invite_id','!=','')
// 						->select('user_id')
// 						->get();
						
// 						$c_str='';
// 						$d_str='';
						
// 						if(!empty($c)){
							
						
// 						foreach ($c as $v_c){
// 								dump($v_c);
// 							$c_str.=','.$v_c->user_id;
// 							$d=\App\MemberModel::where('invite_id',$v_c->user_id)
// 							->where('invite_id','!=','')
// 							->select('user_id')
// 							->selectRaw("GROUP_CONCAT(concat(user_id)) as d_str")
// 							->get();
// 							//dd($d);
// 							dump($d);
// 							if(!empty($d)){
// 								//$d_str.=$d->d_str;
// 							}
							
						
// 						}
// 						dd($d_str);
// 						}
						
// 					}
// 					}
// 					dd($c_str);
// 					//dd($d_str);
					
					
// // 					$personal=\App\BaseOrderModel::where('pay_time','>=',$start_time)->where('pay_time','<',$end_time)
// // 					->leftjoin('ys_sub_order','ys_sub_order.base_id','=','ys_base_order.id')
// // 					->where('ys_base_order.state',1)
// // 					->where('ys_sub_order.receive_state',1)
// // 					->where('ys_sub_order.all_rebate','>',0)
// // 					->where('ys_base_order.user_id')
// // 					->selectRaw('ys_base_order.user_id,sum(ys_sub_order.price) as amount')
// // 					->get();
					
					
					
					
// // 					$user_money=round($val->all_rebate/365,2);
// // 					$params=[
// // 						'user_id'=>$val->user_id,
// // 						'amount'=>$user_money,
// // 						'pay_describe'=>'购物日返',
// // 						'created_at'=>date('Y-m-d H:i:s',time()),
// // 						'type'=>1,
// // 					];
// // 					$user_insert=\App\BillModel::insert($params);
// // 					$user_update=\App\MemberModel::where('user_id',$val->user_id)->increment('balance',$user_money);
// 				}					
// 				if ($user_insert==false || $user_update==false) {
// 					\DB::rollBack();
// 					\Log::info('用户返现失败,log_date是'.$log_date);
// 				}else {
// 					\DB::commit();
// 					\Log::info('用户返现成功');
// 				}					
	
// 				dd(2222);
// 		//执行时间
// 		$data['time']=date('Y-m-d H:i:s',time());
// 		$data['time_section']="月返利程序运行时间区间，开始时间$log_date ,结束时间".date('Y-m-d',$new_data);

// 		file_put_contents($path.'/month_rebate_execute.txt', serialize($data));
		
		
		
		
		
		
		
		
		
		
// 		dd(1);
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		

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
		if ($request->orderid != ''){
			$par->where('ys_base_order.id','like',"%$request->orderid%");
			$search['orderid']=$request->id;
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
			->selectRaw("GROUP_CONCAT(concat(ys_order_goods.name,'(',ys_order_goods.num,'件)')) as goods_name")
			->get();
		
		$data['order_id']=$request->id;
		$data['goods_name']=$goods_name[0]->goods_name;
		$data['receive_address']=$data['receive_name'].'，'.$data['receive_mobile'].'，'.$data['receive_address'];
	
	
		//供应商
		$supplier_name=\App\SubOrderModel::where('base_id',$request->id)
		->leftjoin('ys_supplier','ys_supplier.id','=','ys_sub_order.supplier_id')
		->selectRaw("GROUP_CONCAT(ys_supplier.name) as supplier_name")
		->get();
		$data['supplier_name']=$supplier_name[0]->supplier_name;
		
		return view('orderdetial',['data'=>$data]);
	}
	
	
	public  function ChangeOrder (Request $request){
		
		$data=\App\BaseOrderModel::where('id',$request->id)
					->leftjoin('ys_member','ys_member.user_id','=','ys_base_order.user_id')
					->select('ys_member.name','ys_member.mobile','ys_base_order.create_time')
					->first();
		$data['order_id']=$request->id;
		return view('changeorder',['data'=>$data]);
	}	

	//更改订单归属人提交
	public  function changeOrderSave (Request $request){
	
	 	$user_info=DB::table('ys_member')->where('ys_member.mobile',$request->phone)
	 	->first();
		if(!$user_info){
			session()->flash('message','人员不存在');
			return back();
		}
		
		$res=\App\BaseOrderModel::where('id',$request->id)->update(['user_id'=>$user_info->user_id]);
		

		return redirect('manage/changeorder/'.$request->id);
	}
	
	//删除订单
	public  function DeleteOrder (Request $request){

		\App\OrderGoodsModel::leftjoin('ys_sub_order','ys_sub_order.id','=','ys_order_goods.sub_id')
				->leftjoin('ys_base_order','ys_base_order.id','=','ys_sub_order.base_id')
				->where('ys_base_order.id',$request->id)
				->delete();	
			
		\App\SubOrderModel::where('base_id',$request->id)->delete();
		
		\App\BaseOrderModel::where('id',$request->id)->delete();

	
		return redirect('manage/orderlist');
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
		if ($request->orderid != ''){
			$par->where('ys_base_order.id','like',"%$request->orderid%");
			$search['orderid']=$request->id;
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
					if($k=='amount' || $k=='all_profit'){
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
			
			fputcsv($fp, array(iconv('utf-8', 'gbk','总金额'.$total_amount)));
			
			fputcsv($fp,$null);
			fputcsv($fp,$null);
			foreach ($count_goods as $c_k=>$c_goods){
				fputcsv($fp, array(iconv('utf-8', 'gbk//IGNORE', $c_k.'('.$c_goods.'件)')));
			}
			
			
			
	}
	
	
}
