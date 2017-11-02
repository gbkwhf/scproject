<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27
 * Time: 14:50
 */

namespace App\Http\Controllers\Weixin;


class wechatCallbackapiTest {


    public function valid(){ //valid signature , option

        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){ //调用验证字段
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg(){

        echo "";
        /*
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"]; //接收微信发来的XML数据

        //extract post data
        if(!empty($postStr)){

            //解析post来的XML为一个对象$postObj
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

            $fromUsername = $postObj->FromUserName; //请求消息的用户
            $toUsername = $postObj->ToUserName; //"我"的公众号id
            $keyword = trim($postObj->Content); //消息内容
            $time = time(); //时间戳
            $msgtype = 'text'; //消息类型：文本
            $url='http://'.$_SERVER['HTTP_HOST'].'/wx/gift.php';
            if($postObj->MsgType == 'event'){ //如果XML信息里消息类型为event
                if($postObj->Event == 'subscribe'){ //如果是订阅事件
                    $contentStr = "嗨，小督终于等到您了，在此为您送上  百元健康红包“<a href=\"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx793ad9dbda32fa46&redirect_uri=http%3a%2f%2fysbt.kospital.com%2fwx%2fgift.php&response_type=code&scope=snsapi_base&state=123#wechat_redirect\">点击领取</a>”

祝您神采飞扬
every day~

小督专修颈腰椎不适、提补精气神、调理亚健康，长时间电脑工作、低头玩手机、久站久坐的朋友，赶快来“du”一下吧";
//                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgtype, $contentStr);

                    echo $this->_response_text($postObj,$contentStr);
                }
            }
            //回复图文消息
            if(!empty( $keyword )){
                if($keyword == '空中诊所'){
                    $contentStr = "<a href=\"http://u868888.jisuapp.cn/s?id=1202112&to_id=86326e1d90b4c3dda9d8cfae19b09354&from=singlemessage\">点击查看《空中诊所私人医生服务套餐》</a>";
                    echo $this->_response_text($postObj,$contentStr);
                }else if($keyword == "女人节"){
                    $newsContent = array(
                        "title"=>"【女王福利】浪漫女人节 今天我买单",
                        "description"=>"养脊柱·保健康",
                        "picUrl"=>"http://ysbtdev.kospital.com/wx/common/images/chatu.jpg",
                        "url"=>"http://mp.weixin.qq.com/s/HkWzq6irvDa5xCMFKEMopw"
                    );
                    echo $this->_response_news($postObj,$newsContent);
                }else if($keyword == "详情"){
                    $newsContent = array(
                        "title"=>"【督·福利】中医理疗 免费体验",
                        "description"=>"另有38元超值优惠券限量抢购，卖完为止！",
                        "picUrl"=>"http://ysbt.kospital.com/wx/common/images/weixinxiangqing.jpg",
                        "url"=>"http://mp.weixin.qq.com/s?__biz=MzIzNTY1MjEyOA==&mid=100000026&idx=1&sn=f9e17230c8cc33eb61d4f4bbd971ce74&chksm=68e294555f951d432fc373d6e13cb8b0aa3433e38ff1cbb888b6a1b3afeeffcfc61aec27094b&scene=0#rd"
                    );
                    echo $this->_response_news($postObj,$newsContent);
                }else{
                    $contentStr = "<a href=\"http://u868888.jisuapp.cn/s?id=1202112&to_id=86326e1d90b4c3dda9d8cfae19b09354&from=singlemessage\">点击查看《空中诊所私人医生服务套餐》</a>";
                    echo $this->_response_text($postObj,$contentStr);
                }
            }
        }else {
            echo "";
        }
        */
    }

    //验证字段
    private function checkSignature(){

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
    //创建菜单
    public function createMenu($getAccessToken,$data){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$getAccessToken);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $tmpInfo;
    }
    public function _response_text($object,$content){
        //回复文本消息

        $textTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[%s]]></MsgType>
                <Content><![CDATA[%s]]></Content>
            </xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(),"text", $content);
        return $resultStr;
    }
    public function _response_news($object,$newsContent){
        //回复图文消息
        $newsTplHead = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[news]]></MsgType>
                <ArticleCount>1</ArticleCount>
                <Articles>";
        $newsTplBody = "<item>
                <Title><![CDATA[%s]]></Title>
                <Description><![CDATA[%s]]></Description>
                <PicUrl><![CDATA[%s]]></PicUrl>
                <Url><![CDATA[%s]]></Url>
                </item>";
        $newsTplFoot = "</Articles>
                <FuncFlag>0</FuncFlag>
                </xml>";
        //头
        $header = sprintf($newsTplHead, $object->FromUserName, $object->ToUserName, time());
        $title = $newsContent['title'];
        $desc = $newsContent['description'];
        $picUrl = $newsContent['picUrl'];
        $url = $newsContent['url'];
        //body
        $body = sprintf($newsTplBody, $title, $desc, $picUrl, $url);
        //foot
        $FuncFlag = 0;
        $footer = sprintf($newsTplFoot, $FuncFlag);
        return $header.$body.$footer;

    }





}