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
    	
    	$path=public_path();
    	
    	//读取上次执行时间
    	if(file_exists($path.'/day_rebate_execute.txt')){
    	
    		\Log::info('进入日返现程序');
    		$log_file=unserialize(file_get_contents($path.'/day_rebate_execute.txt'));
    		$log_date=date('Y-m-d',strtotime($log_file['time']));
    		$today_data=date('Y-m-d');
    		\Log::info('日返现读取文件内容，'.serialize($log_file));
    		//检查重复执行
    		if($log_date==$today_data){
    			\Log::info('日返现同一天内重复执行，已退出');
    			exit();
    		}
    	}else{
    		$log_date=date('Y-m-d',strtotime('-1 days'));
    		$log_file['total']['total_profit']=0;
    	}
    	
    	
    	$data=[];
    	
    	$fo=(strtotime('yesterday')-(strtotime($log_date)-86400))/86400;
    	
    	for ($i=1;$i<=$fo;$i++){
    		$new_data=strtotime($log_date)-86400+86400*$i;
    		//时间节点
    		$start_time=date('Y-m-d H:i:s',strtotime(date('Y-m-d',strtotime('-364 days',$new_data))));
    		$end_time=date('Y-m-d',strtotime(date('Y-m-d',$new_data))).' 23:59:59';
    		//返现计算
    		$personal=\App\BaseOrderModel::where('pay_time','>=',$start_time)->where('pay_time','<',$end_time)
    		->leftjoin('ys_sub_order','ys_sub_order.base_id','=','ys_base_order.id')
    		->where('ys_base_order.state',1)
    		->where('ys_sub_order.receive_state',1)
    		->where('ys_sub_order.all_rebate','>',0)
    		->groupBy('ys_base_order.user_id')
    		->selectRaw('ys_base_order.user_id,sum(ys_sub_order.all_rebate) as all_rebate')
    		->get();
    		$user_insert=true;
    		$user_update=true;
    		foreach ($personal as $val){
    			$user_money=round($val->all_rebate/365,2);
    			$params=[
    			'user_id'=>$val->user_id,
    			'amount'=>$user_money,
    			'pay_describe'=>'购物日返',
    			'created_at'=>date('Y-m-d H:i:s',time()),
    			'type'=>1,
    			];
    			$user_insert=\App\BillModel::insert($params);
    			$user_update=\App\MemberModel::where('user_id',$val->user_id)->increment('balance',$user_money);
    		}
    		if ($user_insert==false || $user_update==false) {
    			\DB::rollBack();
    			\Log::info('用户返现失败,log_date是'.$log_date);
    		}else {
    			\DB::commit();
    			\Log::info('用户返现成功');
    		}
    	}
    	//执行时间
    	$data['time']=date('Y-m-d H:i:s',time());
    	$data['time_section']="日返利程序运行时间区间，开始时间$log_date ,结束时间".date('Y-m-d',$new_data);
    	
    	file_put_contents($path.'/day_rebate_execute.txt', serialize($data));
    	

       })->dailyAt('00:30');
       //->dailyAt('00:30');->everyMinute();
    }
}
