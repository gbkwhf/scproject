<?php
/**
 * Created by PhpStorm.
 * User: mandrills
 * Date: 16-1-21
 * Time: 上午10:49
 */

namespace Acme\Repository;


class ErrorCode
{
	const API_ERR_USERNAME_EXIST = "1002";   //用户名已经存在
	const API_ERR_INVALID_SESSION = "1011";   //无效的session	
	const API_ERR_ACCOUNT_OR_PASSWD = "1010";      //用户名或密码错误

	
	
	
	
	
	
	
	
    const API_ERR_USERNAME_FORMAT = "1001";   //用户名格式不对

    const API_ERR_NEED_PINCODE = "1003";   //该账号需要picode验证
    const API_ERR_REG_MANY_TIMES = "1004";   //注册次数太多
    const API_ERR_SEND_SMS_FAIL = "1005";   //发送短信失败
    const API_ERR_ROLE = "1006";   //用户角色不对
    const API_ERR_REG_PINCODE_ERROR = "1007";   //pincode错误
    const API_ERR_REG_PINCODE_TIMEOUT = "1008";   //pincode过期
    const API_ERR_PINCODE_TRY_EXCEED = "1009";   //pincode尝试超限

    const API_ERR_NO_UPDATE = "1012";   //没有发现更新
    const API_ERR_PHONE_FORMAT = "1013";   //手机格式不对
    const API_ERR_PHONE_NO_EXSIT = "1014";   //手机号码不存在
    const API_ERR_FORGET_MANY_TIMES = "1016";   //忘记密码操作次数超限
    const API_ERR_EMAIL_FORMAT = "1017";   //email格式不对
    const API_ERR_EMAIL_NO_EXSIT = "1018";   //email不存在
    const API_ERR_NOT_PRIVILEGE = "1019";   //没有访问个人健康资料权限
    const API_ERR_GENPINCODE_TOO_QUICK = "1020";      //生成注册码太快
    const API_ERR_ALREADY_EXIST_PW = "1021";   //已经存在支付密码
    const API_ERR_PAY_ACCOUNT_OR_PASSWD = "1022";      //用户名或支付密码错误
    const API_ERR_PAY_PASSWORD_MISSING = "1023";      //请先设置支付密码
    const API_ERR_VERSSION = "1024";      //个人信息版本号一致
    const FILE_DOWN_ERR = "1025";   //图片下载失败
    const FILE_SEND_ERR = "1026";   //图片上传失败
    const API_ERR_CONTACT_FORMAT = "1027";   //通讯录格式不对
    const API_ERR_CONTACT_UPLOAD = "1028";   //通讯录上传失败
    const API_ERR_ADD_FRIEND_AUTH = "1031";   //该用户需要认证
    const API_ERR_FRIEND_NOT_EXISTS = "1032";   //添加好友的uid不存在
    const API_ERR_WAIT_CHECK = "1033";   //等待验证状态
    const API_ERR_ADD_FRIEND_GROUP = "1034";   //添加分组错误
    const API_ERR_USER_REGISTER_SOURCE = "1035"; //用户注册来源错误
    const API_ERR_DUPLICATE_ENTRY = "1101";   //重复的地址簿条目
    const API_NOT_SUFFICIENT_FUNDS = "1102";   //余额不足
    const API_ERR_HAVE_NOT_ROLE = "1103";      //还未选择角色
    const API_ERR_SERVICE_CANT_CANALE = "1104";      //服务不能退订
    const API_ERR_SERVICE_HAVING_CANALE = "1105";      //退订处理中
    const API_ERR_SERVICE_HAD_CANALE = "1106";      //已完成退订
    const API_NOT_SUFFICIENT_K = "1302";   //k币不足
    const API_NOT_MATCH_THE_MOBILE = "1303";   //手机号码不匹配

    const API_ERR_FILE_TOO_LARGE = "2001";     //文件大小超过限制
    const API_ERR_MESSAGE_SEND_ERROR = "2002";        //消息发送失败
    const API_ERR_MESSAGE_GET_ERROR = "2003";        //获取消息失败
    const API_ERR_MESSAGE_ACK_ERROR = "2004";     //ack响应失败
    const API_ERR_ACK_HAVE_MESSAGE = "2005";     //ack响应队列中还有消息，需要用户再取一次
    const API_ERR_MMS_FILE_NO_EXSIT = "2006";        //文件不存在
    const API_ERR_AMS_SAVE_ERROR = "2007";     //AMS存储失败
    const API_ERR_AMS_NUMS_FULL = "2008";     //咨询次数已满

    
    const USER_CASE_NOT_IN = "3000";      //病例不存在
    
    
    const UP_USER_PHOTO_FALSE = "6043";      //头像跟新失败
    const IMAGE_SIZE_TO_BIG = "6044";      //图片超过2MB
    const IMAGE_TYPE_NOT_ALLOW = "6045";      //图片类型不允许
    const ORDER_NO_FIND = "6100";      //订单不存在
    const ORDER_CANT_PAY = "6101";      //订单已付款
    const OLD_PW_FALSE = "6102";      //旧密码错误
    const GOODS_CANT_PUY = "6103";      //商品已下架



    const SYSTEM_VERSION_LOW = "9997"; //系统版本过低
    const SYSTEM_ERR = "9998";   //系统错误
    const API_ERR_MISSED_PARAMATER = "9999";   //参数错误
    








    private $messageArr = [
        "1001" => "用户名格式不对",
        "1002" => "手机号已经存在,请直接登录",
        "1003" => "该账号需要验证码验证 ",
        "1004" => "注册次数太多",
        "1005" => "发送短信失败",
        "1006" => "用户角色不对",
        "1007" => "验证码错误",
        "1008" => "验证码过期 ",
        "1009" => "验证码尝试超限",
        "1010" => "用户名或密码错误",
        "1011" => "无效的session",
        "1012" => "没有发现更新",
        "1013" => "手机号码格式不对",
        "1014" => "手机号码未注册",
        "1016" => "忘记密码操作次数超限",
        "1017" => "email格式不对",
        "1018" => "email不存在",
        "1019" => "没有访问个人健康资料权限",
        "1020" => "短信码发送太快",
        "1021" => "已经存在支付密码",
        "1022" => "支付密码错误",
        "1023" => "请先设置支付密码",
        "1024" => "个人信息版本号一致",
        "1025" => "图片下载失败",
        "1026" => "上传失败",
        "1027" => "通讯录格式不对",
        "1028" => "通讯录上传失败",
        "1031" => "该用户需要认证",
        "1032" => "添加好友的uid不存在",
        "1033" => "等待验证状态",
        "1034" => "添加分组错误",
        "1035" => "用户注册来源错误",

        "1036" => "注册失败，请稍后重试",
        "1037" => "生成短信验证码过快",
        "1038" => "该用户没有上传头像",
        "1039" => "该用户不存在",
        "1040" => "绑定失败，稍后尝试",
        "1041" => "该收货地址不存在",
        "1042" => "该商品不存在",



        "1101" => "重复的地址薄条目",
        "1102" => "余额不足",
        "1103" => "还未选择角色",
        "1104" => "服务不能退订",
        "1105" => "退订处理中",
        "1106" => "已完成退订",
        "1302" => "k币不足",
        "1303"=>"手机号码不正确",
        '1100'=>'订单不存在',

        "2001" => "文件大小超过限制",
        "2002" => "消息发送失败",
        "2003" => "获取消息失败",
        "2004" => "ack响应失败",
        "2005" => "ack响应队列中还有消息，需要用户再取一次",
        "2006" => "文件不存在",
        "2007" => "AMS存储失败",
        "2008" => "咨询次数已满",
        
        
        
        "3000" => "病例不存在",
        "3001" => "授权码不存在",
        
        '6043'=>'用户头像上传失败',
        '6044'=>'图片大小超过2MB',
        '6045'=>'图片类型不允许',
        '6100'=>'订单不存在',
        '6101'=>'订单已付款',
        '6102'=>'原密码错误',
        '6103'=>'商品已下架',




        "9997" => "软件版本过低",
        "9998" => "系统错误",
        "9999" => "参数错误 ",

        "8000"=>"只能拥有一个定制包",
        "8001"=>"请先签约再购买",

        "9988" => "你所更改的部位已经存在",

    ];


//    private $code = '1';
//
////    function setStatusCode($code)
////    {
////        $this->code = $code;
////        return $this;
////    }

    function getMessage($code)
    {
        return $this->messageArr[$code];
    }


}