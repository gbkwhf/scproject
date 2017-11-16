<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27
 * Time: 9:50
 */

namespace App\Http\Controllers\Weixin;

use App\Http\Controllers\Controller;
use Acme\Exceptions\ValidationErrorException;
use Illuminate\Http\Request;
use App\Http\Requests;


/**
 * Class WeixinInfoController
 * @package App\Http\Controllers\Weixin
 * 该控制器主要用于完成微信公众号功能的开发
 */
class WeixinInfoController  extends Controller{



    //获取微信签名包
    public function GetSingPackage(Request $request){

        $validator = $this->setRules([
            'url'  => 'required|string',
        ])
            ->_validate($request->all());
        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);

        $url = $request->url;

        $appId = getenv('appId');
        $appSecret = getenv('appSecret');

        $jssdk = new JSSDK($appId,$appSecret);

        $data = $jssdk->getSignPackage($url);
        return $this->respond($this->format($data));

    }


    //获取公众号首页
    public function GetOfficalIndex(){

        define("TOKEN", getenv('TOKEN'));
//        define("TOKEN", "shuangchuanggxzhengshi");
//        echo urlencode('http://gxdev.yxjk99.com/shopping_mall/index.php');die();

        $wx = new wechatCallbackapiTest();

        $appId = getenv('appId');
        $appSecret = getenv('appSecret');

//        $redirect_uri = urlencode(getenv('CALL_BACK_URL'));

//        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appId."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_base&state=123#wechat_redirect";


        $jssdk = new JSSDK($appId,$appSecret);
        $getAccessToken = $jssdk->getAccessToken();


       //data 菜单数据
        $data = '{
                    "button": [
                        {
                            "type": "view",
                            "name": "双创商城",
                            "url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxc8575ef91eb4ec2b&redirect_uri=http%3A%2F%2Fgxdev.yxjk99.com%2Fshopping_mall%2Findex.php&response_type=code&scope=snsapi_base&state=123#wechat_redirect"
                        }
                    ]
                }';


//        $data_tmp = [
//                    "button"=>[
//                        [
//                            "type"=>"view",
//                            "name"=>"shuanghcuang",
//                            "url"=>$url
//                        ]
//                    ]
//                ];
//
//         $data = json_encode($data_tmp);


//        print_r($data);die();

// "url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx97bfadf3a81d8206&redirect_uri=http%3A%2F%2Fgx.yxjk99.com%2Fshopping_mall%2Findex.php&response_type=code&scope=snsapi_base&state=123#wechat_redirect"

        if(isset($_GET['echostr'])){
            $wx->valid(); //如果发来了echostr则进行验证
        }else{
            $wx->responseMsg(); //如果没有echostr，则返回消息
        }

        echo $wx->createMenu($getAccessToken,$data);

    }


    //4.判断该手机号码是否已经绑定了openId
    public function getBindState(Request $request)
    {
        $validator = $this->setRules([
            'openId'  => 'required|string',
        ])
            ->_validate($request->all());
        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);

        //判断该手机号码账户是否绑定了openId
        $is_bind = \DB::table('ys_session_info')->where('openId',$request->openId)->first();
        if(empty($is_bind)){
            $result['state'] = 0; //0 未绑定   1已绑定
        }else{
            $result['state'] =  1; //0 未绑定   1已绑定
        }
        return $this->respond($this->format($result));
    }

    //5.获取微信头像和姓名
    public function getOwnWeixinInfo(Request $request){

        $validator = $this->setRules([
            'ss'  => 'required|string',
        ])
            ->_validate($request->all());
        if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);


        $appId = getenv('appId');
        $appSecret = getenv('appSecret');

        $jssdk = new JSSDK($appId,$appSecret);

        $data = $jssdk->getUserInfo($request->ss);

        return $this->respond($this->format($data));


    }


}