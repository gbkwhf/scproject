<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/25
 * Time: 10:22
 */

namespace App\Http\Controllers\Baseuser;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

/**
 * Class SendToUserSMSController
 * @package App\Http\Controllers\Baseuser
 * 该控制器主要作用就是完成短信模块所有的功能接口统一
 */
class SendToUserSMSController  extends  Controller{


    //完成发送短信功能
    public function sendToUserSMS(Request $request){


        $validator = $this->setRules([
            'un' => 'required|regex:/^1[34578][0-9]{9}$/',
            'service_type' => 'required|integer|in:1,3,4', //1注册   2邮箱找回密码  3手机重置密码    4.短信登陆（免密登陆）
        ])
            ->_validate($request->all());
        if (!$validator) return $this->setStatusCode(9999)->respondWithError($this->message);


        //注意：如果给env配置文件中，写的是中文字符串，好像使用env获取不到值
        switch($request->get('service_type')){
            case 1:
                    $message = "【双创共享】欢迎您使用双创共享！您的注册所需验证码为:[XXX]，10分钟内有效。"; //注册
                    $state = $this->SendSMSHandleReg($request->get('un'),$request->get('service_type'),$message);
                    break;
            case 3:
                    $message = "【双创共享】感谢您使用双创共享！您的重置密码所需验证码为:[XXX]，10分钟内有效。"; //重置密码
                    $state = $this->SendSMSHandle($request->get('un'),$request->get('service_type'),$message);
                    break;

            case 4:
                    $message = "【双创共享】感谢您使用双创共享！您的短信登录所需验证码为:[XXX]，10分钟内有效。"; //短信登陆
                    $state = $this->SendSMSHandle($request->get('un'),$request->get('service_type'),$message);
                    break;
        }



        if($state == '1111'){//发送短信成功
            return  $this->respond($this->format([],true));
        }elseif($state == '-1111'){//发送短信失败
            return  $this->setStatusCode(1005)->respondWithError($this->message);
        }elseif($state == '2222'){ //当天尝试获取短信验证码超过限制次数
            return $this->setStatusCode(1004)->respondWithError($this->message);
        }elseif($state == '3333'){//该用户已经在系统内了，请直接登陆
            return  $this->setStatusCode(1002)->respondWithError($this->message);
        }elseif($state == '4444'){  //该用户不存在
            return  $this->setStatusCode(1014)->respondWithError($this->message);
        }elseif($state == '5555'){  //1分钟之内最多发送一条短信，
            return  $this->setStatusCode(1037)->respondWithError($this->message);
        }

    }


   //（1）处理注册发短信业务逻辑
    private function SendSMSHandleReg($mobile,$type,$message){

        $pin_num = env('PIN_NUM'); //每天最多发送短信的次数
        $userful_time = env('USEFUL_TIME'); //有效时间，单位是分钟
        $SMSApiPath = env("sendurl");
        $apikey = env("yunpianAPIKEY");

        $user = \DB::table('ys_member')->where('mobile',$mobile)->first();
        if (empty($user)){//如果用户不存在
            $res_un = \DB::table('user_pincode_history')->where('mobile',$mobile)->where('service_type',$type)->where('pin_made_time','like','%'.date('Y-m-d').'%')->get();
            if (!empty($res_un)){//如果该用户已申请过验证码

                 //如果已经有短信，那么当短信的有效时间是10分钟，如果超过十分钟，那么就重新生成短信验证码，并且完成发送给用户
                 //如果验证码时间没超过十分钟，那么就把该短信验证码发送给用户即可，并且把尝试次数增加1次
                 //如果尝试次数超过6次，可以配置，那么就不发送短信，给予用户错误码提示，提示短息验证码超过次数限制
                 //并且当使用验证码进行验证的时候，一定要考虑到该验证码的时效性，也就是在有效时间内是否完成了验证，如果失效则不能验证通过
                 $tmp = array_reverse($res_un)[0]; //这是最后一次发送的验证码的信息

                 if($tmp->pin_try_accmulation > $pin_num){ //如果重试次数超过6次，则提示当天尝试次数超过限制
                     return '2222';//当天尝试获取短信验证码超过限制次数
                 }

                //每一分钟最多只能请求一次短信接口
                $interval_f = date('Y-m-d H:i:s',strtotime("$tmp->pin_made_time+1 minute"));//间隔时间
                $datetime = date('Y-m-d H:i:s',time());//当前时间
                if ($datetime < $interval_f) { //如果申请时间未超过上一条1分钟
                    return "5555"; //'1分钟之内已经产生过一次验证码';
                }



                 $minute=floor((time()-strtotime($tmp->pin_made_time))%86400/60);

                 if($minute >= $userful_time){ //大于10分钟，重新生成验证码并发送，次数等于最后一次尝试次数加1

                             //生成验证码，并且检查是否和数据库中现存的冲突，如果冲突则继续生成，直到不冲突为止返回
                             $replace_content = $this->createPInCode();//短信验证码

                             //发短信
                             $params = array(
                                 "apikey" => $apikey,
                                 "mobile" => $mobile,
                                 "text"	=>	str_replace("[XXX]",$replace_content,$message)
                             );


                             $res = PostCURL($SMSApiPath, $params);

                            \Log::info(var_export($res, true));
                             $resData=$res->getData();
                             if($res->isSuccess() && $resData['code']==0){
                                 \Log::info('发送短信成功');

                                 //重新给发送验证码历史表写一条数据
                                 \DB::table('user_pincode_history')->insert([
                                     'mobile'=>$mobile,
                                     'pin_code'=>$replace_content,
                                     'pin_made_time'=>\DB::Raw('now()'),
                                     'pin_try_accmulation'=>$tmp->pin_try_accmulation + 1,
                                     'service_type'=>$type
                                 ]);

                                 return '1111'; //获取短信成功

                             }else{
                                 \Log::info('发送短信失败');
                                 return '-1111'; //获取短信失败
                             }

                 }else{  //小于10分钟，直接发送现有的最后一次生成的验证码，并且把尝试次数加1

                             $replace_content = $tmp->pin_code; //短信验证码
                             //发短信
                             $params = array(
                                 "apikey" => $apikey,
                                 "mobile" => $mobile,
                                 "text"	=>	str_replace("[XXX]",$replace_content,$message)
                             );
                             $res = PostCURL($SMSApiPath, $params);
                             //\Log::info(var_export($res, true));
                             $resData=$res->getData();
                             if($res->isSuccess() && $resData['code']==0){
                                 \Log::info('发送短信成功');

                                 //把最后一条短信记录的尝试次数加1
                                 \DB::table('user_pincode_history')->where('id',$tmp->id)->update([
                                     'pin_try_accmulation'=>$tmp->pin_try_accmulation + 1,
                                 ]);
                                 return '1111'; //获取短信成功
                             }else{
                                 \Log::info('发送短信失败');
                                 return '-1111'; //获取短信失败
                             }
                 }


            }else{ //表示用户今天还没发过短信，则这里进行发送短信即可

                $replace_content = $this->createPInCode();//短信验证码
                //发短信
                $params = array(
                    "apikey" => $apikey,
                    "mobile" => $mobile,
                    "text"	=>	str_replace("[XXX]",$replace_content,$message)
                );

                $res = PostCURL($SMSApiPath, $params);

                //\Log::info(var_export($res, true));
                $resData=$res->getData();
                if($res->isSuccess() && $resData['code']==0){
                    \Log::info('发送短信成功');

                    //重新给发送验证码历史表写一条数据
                    \DB::table('user_pincode_history')->insert([
                        'mobile'=>$mobile,
                        'pin_code'=>$replace_content,
                        'pin_made_time'=>\DB::Raw('now()'),
                        'pin_try_accmulation'=>1,
                        'service_type'=>$type
                    ]);

                    return '1111'; //获取短信成功
                }else{
                    \Log::info('发送短信失败');
                    return '-1111'; //获取短信失败
                }
            }
        }else{

            return "3333";//该用户已经在系统内了，请直接登陆
        }
    }

    //（2）处理免密登陆和重置密码业务逻辑
    private function SendSMSHandle($mobile,$type,$message){

        $pin_num = env('PIN_NUM'); //每天最多发送短信的次数
        $userful_time = env('USEFUL_TIME'); //有效时间，单位是分钟
        $SMSApiPath = env("sendurl");
        $apikey = env("yunpianAPIKEY");

        $user = \DB::table('ys_member')->where('mobile',$mobile)->first();
        if (!empty($user)){//如果用户存在
            $res_un = \DB::table('user_pincode_history')->where('mobile',$mobile)->where('service_type',$type)->where('pin_made_time','like','%'.date('Y-m-d').'%')->get();
            if (!empty($res_un)){//如果该用户已申请过验证码

                //如果已经有短信，那么当短信的有效时间是10分钟，如果超过十分钟，那么就重新生成短信验证码，并且完成发送给用户
                //如果验证码时间没超过十分钟，那么就把该短信验证码发送给用户即可，并且把尝试次数增加1次
                //如果尝试次数超过6次，可以配置，那么就不发送短信，给予用户错误码提示，提示短息验证码超过次数限制
                //并且当使用验证码进行验证的时候，一定要考虑到该验证码的时效性，也就是在有效时间内是否完成了验证，如果失效则不能验证通过
                $tmp = array_reverse($res_un)[0]; //这是最后一次发送的验证码的信息

                if($tmp->pin_try_accmulation > $pin_num){ //如果重试次数超过6次，则提示当天尝试次数超过限制
                    return '2222';//当天尝试获取短信验证码超过限制次数
                }


                //每一分钟最多只能请求一次短信接口
                $interval_f = date('Y-m-d H:i:s',strtotime("$tmp->pin_made_time+1 minute"));//间隔时间
                $datetime = date('Y-m-d H:i:s',time());//当前时间
                if ($datetime < $interval_f) { //如果申请时间未超过上一条1分钟
                    return "5555"; //'1分钟之内已经产生过一次验证码';
                }



                $minute=floor((time()-strtotime($tmp->pin_made_time))%86400/60);

                if($minute >= $userful_time){ //大于10分钟，重新生成验证码并发送，次数等于最后一次尝试次数加1

                    //生成验证码，并且检查是否和数据库中现存的冲突，如果冲突则继续生成，直到不冲突为止返回
                    $replace_content = $this->createPInCode();//短信验证码

                    //发短信
                    $params = array(
                        "apikey" => $apikey,
                        "mobile" => $mobile,
                        "text"	=>	str_replace("[XXX]",$replace_content,$message)
                    );


                    $res = PostCURL($SMSApiPath, $params);

                    \Log::info(var_export($res, true));
                    $resData=$res->getData();
                    if($res->isSuccess() && $resData['code']==0){
                        \Log::info('发送短信成功');

                        //重新给发送验证码历史表写一条数据
                        \DB::table('user_pincode_history')->insert([
                            'mobile'=>$mobile,
                            'pin_code'=>$replace_content,
                            'pin_made_time'=>\DB::Raw('now()'),
                            'pin_try_accmulation'=>$tmp->pin_try_accmulation + 1,
                            'service_type'=>$type
                        ]);

                        return '1111'; //获取短信成功

                    }else{
                        \Log::info('发送短信失败');
                        return '-1111'; //获取短信失败
                    }

                }else{  //小于10分钟，直接发送现有的最后一次生成的验证码，并且把尝试次数加1

                    $replace_content = $tmp->pin_code; //短信验证码
                    //发短信
                    $params = array(
                        "apikey" => $apikey,
                        "mobile" => $mobile,
                        "text"	=>	str_replace("[XXX]",$replace_content,$message)
                    );
                    $res = PostCURL($SMSApiPath, $params);
                    //\Log::info(var_export($res, true));
                    $resData=$res->getData();
                    if($res->isSuccess() && $resData['code']==0){
                        \Log::info('发送短信成功');

                        //把最后一条短信记录的尝试次数加1
                        \DB::table('user_pincode_history')->where('id',$tmp->id)->update([
                            'pin_try_accmulation'=>$tmp->pin_try_accmulation + 1,
                        ]);
                        return '1111'; //获取短信成功
                    }else{
                        \Log::info('发送短信失败');
                        return '-1111'; //获取短信失败
                    }
                }


            }else{ //表示用户今天还没发过短信，则这里进行发送短信即可

                $replace_content = $this->createPInCode();//短信验证码
                //发短信
                $params = array(
                    "apikey" => $apikey,
                    "mobile" => $mobile,
                    "text"	=>	str_replace("[XXX]",$replace_content,$message)
                );

                $res = PostCURL($SMSApiPath, $params);

                //\Log::info(var_export($res, true));
                $resData=$res->getData();
                if($res->isSuccess() && $resData['code']==0){
                    \Log::info('发送短信成功');

                    //重新给发送验证码历史表写一条数据
                    \DB::table('user_pincode_history')->insert([
                        'mobile'=>$mobile,
                        'pin_code'=>$replace_content,
                        'pin_made_time'=>\DB::Raw('now()'),
                        'pin_try_accmulation'=>1,
                        'service_type'=>$type
                    ]);

                    return '1111'; //获取短信成功
                }else{
                    \Log::info('发送短信失败');
                    return '-1111'; //获取短信失败
                }
            }

        }else{ //如果用户不存在

            return "4444";//用户不存在
        }
    }

    //生成验证码
    private function createPInCode(){
       do{
           $pin = rand('100000','999999');
           $res = \DB::table('user_pincode_history')->where('pin_code',$pin)->first();
       }while(!empty($res)); //如果该$pin不存在于数据库，那么就把该验证码返回
        return $pin;
    }


}