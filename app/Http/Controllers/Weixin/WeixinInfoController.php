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







}