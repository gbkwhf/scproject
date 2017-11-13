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

      $list = \DB::table('ys_member')->select('user_id','mobile','sex','name','address','image as thumbnail_image_url')->where('invite_id',$user_id)->get();

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

        //(3)判断用户所 提现金额手续费(提现金额的3%) + 提现金额   是否小于等于 用户余额
        $require_money = $request->money + ($request->money / 100 * 3);
        if($require_money >  $is_member->balance){ //余额不足 1102
            return $this->setStatusCode(1102)->respondWithError($this->message);
        }

        $insert_data = [

            [
                'user_id' => $is_member->user_id,
                'amount' => "-".$request->money,
                'pay_describe' => "用户提现",
                'created_at' => date("Y-m-d H:i:s"),
                'type'=>4
            ],
            [
                'user_id' => $is_member->user_id,
                'amount' => "-".($request->money / 100 * 3),
                'pay_describe' => "提现扣除手续费",
                'created_at' => date("Y-m-d H:i:s"),
                'type'=>5
            ]

        ];

        \DB::beginTransaction(); //开启事务
        //(4)从余额中扣除手续费和提现金额
         //扣余额
         $is_true = \DB::table('ys_member')->where('mobile',$request->mobile)->update([
              'balance' =>  $is_member->balance - $require_money,
              'updated_at' => \DB::Raw('Now()')
         ]);
          //写流水
          $insert = \DB::table('ys_bills')->insert($insert_data);

        if ($is_true && $insert){
            \DB::commit();
            return $this->respond($this->format([],true));
        }else {
            \DB::rollBack();
            return $this->setStatusCode(9998)->respondWithError($this->message);
        }

    }
    //4.获取我的体现记录（流水）
    public function getBillsList(Request $request)
    {

        $validator = $this->setRules([
            'ss' => 'required|string', //会员session
        ])
            ->_validate($request->all());
        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id

        $info = \DB::table('ys_bills')->where('user_id',$user_id)->whereIn('type',[4,5])->select('id as bill_id','amount','pay_describe','created_at','type')->orderBy('created_at','desc')->get();

        $data = empty($info) ? [] : $info;
        return $this->respond($this->format($data));

    }





}