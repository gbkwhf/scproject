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

//        $schedule->call(function () {
//            \App\UserCustomMadePackageInfo::
//            whereRaw("ADDDATE(DATE_FORMAT(custom_time,'%Y-%m-%d'), INTERVAL 31 DAY)=DATE_FORMAT(now(),'%Y-%m-%d')")->chunk(100, function($users) {
//                foreach ($users as $user) {
//                    dispatch(new \App\Jobs\UserCustomDeduction($user));
//                }
//            });
//        })->everyThirtyMinutes();

        //取消过了20分钟还没付款的订单
        $schedule->call(function(){
        	\App\Order::
        	whereRaw("ADDDATE(created_at, INTERVAL 20 MINUTE)<now()")
        	->where('pay_time','=',null)
        	->where('state',0)
        	->select('order_id')
        	->chunk(100, function($order) {
        		foreach ($order as $orderSub) {
        			\DB::transaction(function() use ($orderSub){
        				\App\Order::where('order_id',$orderSub->order_id)->update(['state'=>3,'cancel_by'=>4,'updated_at'=>\Carbon\Carbon::now()]);
        			});
        		}
        	});
        })->everyMinute();


        //取消过期未服务订单，并且退款
        $schedule->call(function(){
            $sel = \App\Order::where('state',0)->where('payed_amount', '!=', '')->where('actual_start_time','=',Null)
                ->select('payed_amount', 'order_id', 'consumer_id as user_id', 'intend_start_time as time', 'payment_by','pay_time')
                ->get()->toArray();
            if(!empty($sel)){
                foreach ($sel as $k => $v) {
                    $temp = strtotime(date('Y-m-d',time())) - strtotime($v['time']);  //如果大于0则表示过期为服务，该退款
                    $payWay = "";
                    switch($v['payment_by']){ //支付方式 1 余额 2 支付宝 3 银联 4 微信 5 医信健康月定制包
                        case 1:
                            $payWay = "余额";
                            break;
                        case 2:
                            $payWay = "支付宝";
                            break;
                        case 3:
                            $payWay = "银联";
                            break;
                        case 4:
                            $payWay = "微信";
                            break;
                        case 5:
                            $payWay = "医信健康月定制包";
                            break;
                    }
                    if ($temp > 0) { //只有超过预定的日期则退款

                        $balance = \App\Balance::where('user_id', $v['user_id'])->first();
                        $old = !empty($balance) ? $balance->balance : -1;
                        $date = date('Y-m-d H:i:s',time());
                        \DB::beginTransaction(); //开启事务
                        $update = \DB::table('ysbt_orders')->where('order_id', $v['order_id'])
                            ->update(['state' => 3, 'cancel_by' => 2, 'updated_at' => \DB::raw('NOW()')]);
                        $insert = \DB::table('ysbt_bills')->insert(['user_id' => $v['user_id'], 'amount' => "+".$v['payed_amount'],
                            'pay_describe' => "订单过期未服务退款,原订单支付方式:".$payWay.",日期是:".$date,
                            'payment_by' => $v['payment_by'], 'order_id' => $v['order_id'], 'created_at' => \DB::raw('NOW()'), 'type' => 4]);
                        if($old == -1){
                            $update1 = \DB::table('ysbt_balance')->insert(['user_id'=>$v['user_id'],'balance' => $v['payed_amount'],'created_at'=>\DB::raw('NOW()'), 'updated_at' => \DB::raw('NOW()')]);
                        }else{
                            $update1 = \DB::table('ysbt_balance')->where('user_id', $v['user_id'])->update(['balance' => $old + $v['payed_amount'], 'updated_at' => \DB::raw('NOW()')]);
                        }
                        if ($update && $insert && $update1) {
                            \DB::commit();
                        }else {
                            \DB::rollBack();
                        }
                    }
                }
            }

        })->hourly();


        //取消3个自然天内没有写健康报告的订单
        $schedule->call(function(){
            $sel = \App\Order::where('state', 1)->where('payed_amount', '!=', '')->where('report_time','=',Null)->select('payed_amount', 'order_id', 'consumer_id as user_id', 'actual_start_time as time', 'payment_by','pay_time')->get()->toArray();
            if(!empty($sel)){
                foreach ($sel as $k => $v) {
                    $temp = (48 + (24 - substr($v['time'], 11, 2))) - floor((time() - strtotime($v['time'])) / 3600);   //ceil进一法取整   floor向下取整
                    $payWay = "";
                    switch($v['payment_by']){ //支付方式 1 余额 2 支付宝 3 银联 4 微信 5 医信健康月定制包
                        case 1:
                            $payWay = "余额";
                            break;
                        case 2:
                            $payWay = "支付宝";
                            break;
                        case 3:
                            $payWay = "银联";
                            break;
                        case 4:
                            $payWay = "微信";
                            break;
                        case 5:
                            $payWay = "医信健康月定制包";
                            break;
                    }
                    //判断是否填写了健康报告
                    if ($temp == 0){ //只有满了三个自然日并且没有填写报告则自动退款
                        $balance = \App\Balance::where('user_id', $v['user_id'])->first();
                        $old = !empty($balance) ? $balance->balance : -1;
                        $date = date('Y-m-d H:i:s',time());
                        \DB::beginTransaction(); //开启事务
                        $update = \DB::table('ysbt_orders')->where('order_id', $v['order_id'])->update(['state' => 3, 'cancel_by' => 3, 'updated_at' => \DB::raw('NOW()')]);
                        $insert = \DB::table('ysbt_bills')->insert(['user_id' => $v['user_id'], 'amount' => "+".$v['payed_amount'],
                            'pay_describe' => "订单未按时填写报告退款,原订单支付方式:".$payWay.",日期是:".$date,
                            'payment_by' => $v['payment_by'], 'order_id' => $v['order_id'], 'created_at' => \DB::raw('NOW()'), 'type' => 5]);
                        if($old == -1){
                            $update1 = \DB::table('ysbt_balance')->insert(['user_id'=>$v['user_id'],'balance' => $v['payed_amount'],'created_at'=>\DB::raw('NOW()'), 'updated_at' => \DB::raw('NOW()')]);
                        }else{
                            $update1 = \DB::table('ysbt_balance')->where('user_id', $v['user_id'])->update(['balance' => $old + $v['payed_amount'], 'updated_at' => \DB::raw('NOW()')]);
                        }
                        if ($update && $insert && $update1) {
                            \DB::commit();
                        }else {
                            \DB::rollBack();
                        }
                    }
                }

            }

        })->hourly();
    }
}
