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
    public function GetSingPackage(){

        $appId = getenv('appId');
        $appSecret = getenv('appSecret');

        $jssdk = new JSSDK($appId,$appSecret);

        $data = $jssdk->getSignPackage();
        return $this->respond($this->format($data));

    }


    //获取公众号首页
    public function GetOfficalIndex(){

        define("TOKEN", "shuangchuanggx");
//        echo urlencode('http://gxdev.yxjk99.com/shopping_mall/index.php');die();


        $wx = new wechatCallbackapiTest();

        $appId = getenv('appId');
        $appSecret = getenv('appSecret');
        $jssdk = new JSSDK($appId,$appSecret);
        $getAccessToken = $jssdk->getAccessToken();

       //data 菜单数据
        $data = '{
                    "button": [
                        {
                            "type": "view",
                            "name": "双创商城",
                            "url": "http://t.cn/RWW5po6"
                        },
                        {
                            "name": "加盟连锁",
                            "sub_button": [
                                {
                                    "type": "view",
                                    "name": "合伙开店",
                                    "url": ""
                                },
                                {
                                    "type": "view",
                                    "name": "门店植入",
                                    "url": ""
                                }
                            ]
                        },
                        {
                            "name": "督市天地",
                            "sub_button": [
                                {
                                    "type": "view",
                                    "name": "督脉正阳",
                                    "url": ""
                                },
                                {
                                    "type": "view",
                                    "name": "空中诊所",
                                    "url": ""
                                },
                                {
                                    "type": "view",
                                    "name": "门店管理",
                                    "url": ""
                                },
                                {
                                    "type": "view",
                                    "name": "关于我们",
                                    "url": ""
                                }
                            ]
                        }
                    ]
                }';



        if(isset($_GET['echostr'])){
            $wx->valid(); //如果发来了echostr则进行验证
        }else{
            $wx->responseMsg(); //如果没有echostr，则返回消息
        }

        echo $wx->createMenu($getAccessToken,$data);

    }





}