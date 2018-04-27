<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/2
 * Time: 15:22
 */

namespace App\Http\Controllers\HandleProfession;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;


/**
 * Class GetUserOwnInfoController
 * @package App\Http\Controllers\HandleProfession
 * 该控制器主要完成用户个人中心的一些信息
 */
class GetUserOwnInfoController extends Controller{


  //1.获取我邀请的用户列表
  public function getInviteList(Request $request)
  {
      $validator = $this->setRules([
          'ss' => 'required|string',
      ])
          ->_validate($request->all());
      if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);

      $user_id = $this->getUserIdBySession($request->ss); //获取员工id

      $list = \DB::table('ys_member')->select('user_id','mobile','sex','name','address','image as thumbnail_image_url','created_at')->where('invite_id',$user_id)->orderBy('created_at','desc')->get();

      $http = getenv('HTTP_REQUEST_URL'); //获取域名
       if(!empty($list)){
           foreach($list as $k=>$v){
               $list[$k]->thumbnail_image_url =  empty($v->thumbnail_image_url) ? "" : $http.'/api/gxsc/show-ico/'.'thu_'.$v->thumbnail_image_url;
           }
       }else{
           $list = [];
       }
      return $this->respond($this->format($list));
  }



    //3.替用户提现（员工才具有该功能）
    public function replaceUserMoney(Request $request)
    {

        $validator = $this->setRules([
            'ss' => 'required|string', //员工session
            'mobile' => 'required|regex:/^1[34578][0-9]{9}$/', //所代替的用户手机号码
            'money' => 'required|string' //提现金额
        ])
            ->_validate($request->all());
        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);

        $employee_id = $this->getUserIdBySession($request->ss); //获取员工id
        //（1）首先判定角色身份，必须是员工才可以调用该接口
        $is_employee = \DB::table('ys_employee')->where('user_id',$employee_id)->first();
        if(empty($is_employee)){ //表示不是员工
            return $this->setStatusCode(1045)->respondWithError($this->message);
        }

        //(2)接着查看会员手机号码是否存在，如果不存在则让其注册后再买
        $is_member = \DB::table('ys_member')->where('mobile',$request->mobile)->first();
        if(empty($is_member)){ //该用户还不是会员
            return $this->setStatusCode(1044)->respondWithError($this->message);
        }

        //(3)判断用户所 提现金额手续费(提现金额的5%) + 提现金额   是否小于等于 用户余额
        $require_money = $request->money + ($request->money / 100 * 5);
        if($require_money >  $is_member->balance){ //余额不足 1102
            return $this->setStatusCode(1102)->respondWithError($this->message);
        }

        $order_id = generatorOrderIdNew();
        $insert_data = [
                'user_id' => $is_member->user_id,
                'amount' => "-".$request->money,
                'pay_describe' => "用户提现",
                'created_at' => date("Y-m-d H:i:s"),
                'type'=>1,
                'state'=>0, //0提现中    1提现成功
                'employee_id'=>$employee_id,
                'operate_order_id'=>$order_id,
            ];

        $tixian_money = number_format($request->money,2);

        $phone =\DB::table('ys_member')->where('user_id',$employee_id)->first()->mobile; //员工手机号


        \DB::beginTransaction(); //开启事务
        //写流水
         $insert = \DB::table('ys_operate_bills')->insert($insert_data);

        if ($insert){
            \DB::commit();
            $SMSApiPath = env("sendurl");
            $apikey = env("yunpianAPIKEY");
            //发短信
            $params = array(
                "apikey" => $apikey,
                "mobile" => $request->mobile,
                "text"	=>	"【双创共享】正在为您进行提现操作，操作者联系方式为：".$phone.",订单号为：".$order_id.",提现金额为：".$tixian_money."元。"
            );

            $res = PostCURL($SMSApiPath, $params);
            \Log::info(var_export($res, true));
            $resData=$res->getData();
            if($res->isSuccess() && $resData['code']==0){
                \Log::info('发送短信成功');
            }else{
                \Log::info('发送短信失败');
            }
            return $this->respond($this->format([],true));
        }else {
            \DB::rollBack();
            return $this->setStatusCode(9998)->respondWithError($this->message);
        }

    }

    //4.获取我的操作记录（流水） ----员工
    public function getOwnOpeBill(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'required|string', //员工session
        ])
            ->_validate($request->all());
        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);

        $employee_id = $this->getUserIdBySession($request->ss); //获取员工id
        //（1）首先判定角色身份，必须是员工才可以调用该接口
        $is_employee = \DB::table('ys_employee')->where('user_id',$employee_id)->first();
        if(empty($is_employee)){ //表示不是员工
            return $this->setStatusCode(1045)->respondWithError($this->message);
        }
        //获取我的操作流水记录
        $info = \DB::table('ys_operate_bills')
                 ->where('employee_id',$employee_id)
                 ->where('type',1)
                 ->select('operate_order_id as bill_id','employee_id','user_id','amount','pay_describe','created_at','type','state')
                 ->orderBy('created_at','desc')
                 ->get();
        $data = empty($info) ? [] : $info;
        return $this->respond($this->format($data));

    }

    //5.获取我的体现记录（流水） -----会员
    public function getBillsList(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'required|string', //会员session
        ])
            ->_validate($request->all());
        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id

        $info = \DB::table('ys_operate_bills')
               ->where('user_id',$user_id)
               ->whereIn('type',[1,2])
               ->select('operate_order_id as bill_id','employee_id','user_id','amount','pay_describe','created_at','type','state')
               ->orderBy('created_at','desc')
               ->get();

        $data = empty($info) ? [] : $info;
        return $this->respond($this->format($data));
    }

   //6.确认收款（会员）
    public function ackGetMoney(Request $request)
    {

        $validator = $this->setRules([
            'ss' => 'required|string', //会员session
            'bills_id'=>'required|string' //流水号
        ])
            ->_validate($request->all());
        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id

        //（1）判断操作流水记录是否存在
        $bills_info = \DB::table('ys_operate_bills')
                    ->where('user_id',$user_id)
                    ->where('operate_order_id',$request->bills_id)
                    ->where('state',0) //0提现中    1提现成功
                    ->where('type',1)
                    ->first();

        if(empty($bills_info)){ //未找到该记录 1048
            return $this->setStatusCode(1048)->respondWithError($this->message);
        }
        //继续判断用户余额是否充足
        //(2)判断用户所 提现金额手续费(提现金额的3%) + 提现金额   是否小于等于 用户余额
        $member_info  = \DB::table('ys_member')->where('user_id',$user_id)->first();
        $require_money = abs($bills_info->amount) + (abs($bills_info->amount) / 100 * 3);
        if($require_money >  $member_info->balance){ //余额不足 1102
            return $this->setStatusCode(1102)->respondWithError($this->message);
        }

        //（3）扣余额，写手续费流水，并且更改提现流水的状态为已完成
        $insert_data = [
            'user_id' => $user_id,
            'amount' => "-".(abs($bills_info->amount) / 100 * 3),
            'pay_describe' => "提现扣除手续费",
            'created_at' => date("Y-m-d H:i:s"),
            'finished_at' => date("Y-m-d H:i:s"),
            'type'=>2, //1用户提现, 2扣除手续费
            'state'=>1, //0提现中    1提现成功
            'employee_id'=>$bills_info->employee_id,
            'operate_order_id'=>$request->bills_id
        ];

        \DB::beginTransaction(); //开启事务
        //(4)从余额中扣除手续费和提现金额
        //扣余额
         $is_true = \DB::table('ys_member')->where('user_id',$user_id)->update([
              'balance' =>  $member_info->balance - $require_money,
              'updated_at' => \DB::Raw('Now()')
         ]);
        //写流水
        $insert = \DB::table('ys_operate_bills')->insert($insert_data);
        $update = \DB::table('ys_operate_bills')->where('operate_order_id',$request->bills_id)->update(['state'=>1,'finished_at'=>\DB::Raw('Now()')]);

        if ($is_true && $insert && $update){
            \DB::commit();
            return $this->respond($this->format([],true));
        }else {
            \DB::rollBack();
            return $this->setStatusCode(9998)->respondWithError($this->message);
        }
    }


    //7.根据用户手机号码获取余额
    public function getBalance(Request $request)
    {

        $validator = $this->setRules([
            'ss' => 'required|string', //员工session
            'mobile' => 'regex:/^1[34578][0-9]{9}$/', //所代替的用户手机号码
        ])
            ->_validate($request->all());
        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);

        //$employee_id = $this->getUserIdBySession($request->ss); //获取员工id
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id

        if(!empty($request->mobile)){
            $balance = \DB::table('ys_member')->where('mobile',$request->mobile)->first();
        }else{
            $balance = \DB::table('ys_member')->where('user_id',$user_id)->first();
        }


        if(empty($balance)){ //用户不存在
            return $this->setStatusCode(1039)->respondWithError($this->message);
        }
        return $this->respond($this->format(['balance'=>$balance->balance,'total_amount'=>$balance->total_amount,'user_lv'=>$balance->user_lv]));

    }

    //员工给顾客下单记录
    public function cashBackList(Request $request)
    {
        
    	$validator = $this->setRules([
    			'ss' => 'required|string',
    			//'page' => ''
    			])
    			->_validate($request->all());
    	if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);
    
    	$user_id = $this->getUserIdBySession($request->ss); //获取用户id
    	$start = $request->page <= 1 ? 0 : (($request->page) - 1) * 10;//分页
    	 
    	$bills=\App\BillModel::where('user_id',$user_id)
    			->select('amount','pay_describe','created_at')    	 
    			->orderBy('created_at','desc')
    			->skip($start)->take(10)
    			->get();
    	return  $this->respond($this->format($bills));
    }

}