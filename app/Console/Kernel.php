<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();

    $schedule->call(function () {

    	//读取上次执行时间	
 	if(file_exists('rebate_execute.txt')){
 		$log_file=unserialize(file_get_contents('rebate_execute.txt'));
 		$log_date=date('Y-m-d',strtotime($log_file['time'])); 		
 		$today_data=date('Y-m-d'); 		
 		//检查重复执行
 		if($log_date==$today_data){
 			exit();
 		} 		
 	}else{ 		
 		$log_date=date('Y-m-d',strtotime('-1 days'));
 	}
 	$data=['time'=>date('Y-m-d H:i:s',time())];
 	
 	//返利比例
 	$rate=config('clinic-config.rate'); 	

 	//时间节点
 	$start_time=date('Y-m-d H:i:s',strtotime(date('Y-m-d',strtotime($log_date))));
 	$end_time=date('Y-m-d',strtotime(date('Y-m-d',strtotime('-1 days')))).' 23:59:59';
 	
 	//返现计算
 	$time=date('Y-m-d H:i:s',strtotime(date('Y-m-d',strtotime('-180 days',strtotime($log_date)))));
 	$yesterday_profit=\App\BaseOrderModel::where('state',1)
 			->where('pay_time','>=',$start_time)
 			->where('pay_time','<',$end_time)
 			->sum('all_profit');
 	$total_num=\App\BaseOrderModel::where('state',1)->where('pay_time','>=',$time)->where('pay_time','<',$end_time)->sum('rebate_num');
 	//指定用户返利
 	$personal=\App\BaseOrderModel::where('pay_time','>=',$time)->where('pay_time','<',$end_time)
	 	->leftjoin('ys_member','ys_member.user_id','=','ys_base_order.user_id')
	 	->where('ys_base_order.state',1)
	 	->where('rebate_num','>',0)
	 	->groupBy('ys_base_order.user_id')
	 	->selectRaw('ys_base_order.user_id,sum(ys_base_order.rebate_num) as rebate_num')
	 	->get();
 	$percent=round($yesterday_profit/$total_num,2);
 	

 	
 	//昨日系统总利润，昨日总份数，昨日每份金额，系统总利润，用户总返利，经销商总返利，
 	$data['yesterday']=[
	 	'yesterday_profit'=>$yesterday_profit,
	 	'total_num'=>$total_num,
	 	'percent'=>$percent,
 	];
 	$data['total']=[
	 	'total_profit'=>$log_file['total']['total_profit']+$yesterday_profit,
 	];
 	file_put_contents('rebate_execute.txt', serialize($data));
 	$user_insert=true;
 	$user_update=true;
 	foreach ($personal as $val){
 		$user_money=$val->rebate_num*$percent;
 		$params=[
	 		'user_id'=>$val->user_id,
	 		'amount'=>$user_money,
	 		'pay_describe'=>'会员购物返利',
	 		'created_at'=>date('Y-m-d H:i:s',time()),
	 		'type'=>1,
 		];
 		$user_insert=\App\BillModel::insert($params);
 		$user_update=\App\MemberModel::where('user_id',$val->user_id)->increment('balance',$user_money);
 	}

 	if ($user_insert==false || $user_update==false) {
 		\DB::rollBack();
 		\Log::info('用户返现失败');
 	}else {
 		\DB::commit();
 		\Log::info('用户返现成功');
 	}
 	
 	

 	//经销商返利//昨日引入总利润乘以固定比率，，流水
 	$agency_profit=\App\BaseOrderModel::where('state',1)
	 	->leftjoin('ys_employee','ys_employee.user_id','=','ys_base_order.employee_id')
	 	->where('pay_time','<',$end_time)
	 	->where('pay_time','>=',$start_time)
	 	->where('agency_id','>',0)
	 	->groupBy('ys_employee.agency_id')
	 	->selectRaw('ys_employee.agency_id,sum(ys_base_order.all_profit) as agency_total')->get();

 	$agency_insert=true;
 	$agency_update=true;
 	foreach ($agency_profit as $val){
 		//总利润*比例，加入余额，写入流水
 		$up_money=$val->agency_total*$rate['agency_rate'];
 		$params=[
	 		'agency_id'=>$val->agency_id,
	 		'amount'=>$up_money,
	 		'pay_describe'=>'替用户下单返利',
	 		'created_at'=>date('Y-m-d H:i:s',time()),
 		];
 		$agency_insert=\App\AgencyBillModel::insert($params);
 		$agency_update=\App\AgencyModel::where('id',$val->agency_id)->increment('balance',$up_money);
 	}

 	if ($agency_insert==false || $agency_update==false) {
 		\DB::rollBack();
 		\Log::info('经销商返现失败');
 	}else {
 		\DB::commit();
 		\Log::info('经销商返现成功');
 	}
 	
 	//邀请返利//订单主人有没有邀请人，有邀请人就按订单利润乘以固定比率，邀请人获得返利的资格
 	$user_profit=\App\BaseOrderModel::where('ys_base_order.state',1)
	 	->leftjoin('ys_member','ys_member.user_id','=','ys_base_order.user_id')
	 	->where('pay_time','<',$end_time)
	 	->where('pay_time','>=',$start_time)
	 	->where('invite_id','!=','')	 	
	 	->groupBy('ys_base_order.user_id')
	 	->selectRaw('ys_member.invite_id,ys_base_order.user_id,sum(ys_base_order.all_profit) as user_profit')
 		->get();
 	$invite_insert=true;
 	$invite_update=true;
 	foreach ($user_profit as $val){
 		//查询该用户邀请人有没有返现资格
 		$had=\App\BaseOrderModel::where('user_id',$val->invite_id)->where('state',1)->where('rebate_num','>',0)->first();
 		if($had){//总利润*比例,计入流水和余额
 			if($val->user_profit>0){
 				$money=$val->user_profit*$rate['invice_rate'];
 				$params=[
	 				'user_id'=>$val->invite_id,
	 				'amount'=>$money,
	 				'pay_describe'=>'邀请人下单返利',
	 				'created_at'=>date('Y-m-d H:i:s',time()),
	 				'type'=>2,
 				];
 				$invite_insert=\App\BillModel::insert($params);
 				$invite_update=\App\MemberModel::where('user_id',$val->invite_id)->increment('balance',$money); 				
 			}
 		}
 	}

 	if ($invite_insert==false || $invite_update==false) {
 		\DB::rollBack();
 		\Log::info('邀请返现失败');
 	}else {
 		\DB::commit();
 		\Log::info('邀请返现成功');
 	}
    	    	
       })->everyMinute();
    }
}
