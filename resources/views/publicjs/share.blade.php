<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>用户注册</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"><meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="../../../public/css/common.css">
    <link rel="stylesheet" href="../../../public/css/userRegister.css">
</head>
<body>
<div class="usregHead"></div>
<div class="registerBox">
    <div class="userMobil">
        <input type="tel" class="inp inpmobile" maxlength="11" onkeyup="value=value.replace(/[^0-9.]/g,'') " id="inpmobile" placeholder="请输入手机号" />
    </div>
    <div class="inCode">
        <input class="inp verify" id="verify" maxlength="6" type="text" placeholder="请输入验证码"/>
        <div class="getCode" onkeyup="value=value.replace(/[^0-9.]/g,'') " onclick="getcode()">获取验证码</div>
    </div>
    <div class="registerBtn" onclick="reg()">注册</div>
</div>
<div class="regFooter">
    <div class="activeBox">
        <div class="inviTitle">活动内容</div>
        <div class="nativeNav">在双创共享平台会员区通过现金或线上支付进行下单交易后， 其消费的金额，按照利润共享标准，给予相应的返利，由系统每天自动返还。其利润共享的金额显示在会员“可用余额”里，并可以直接提现。</div>
    </div>
    <div class="maImg">
        <p><img src="../../../public/images/ma.jpg"/></p>
        <p style="padding-left: 69.5%;color: #ffffff;font-size: 12px;">双创共享</p>
    </div>
    <!--<div class="peopleImg"><img src="images/people.png"/></div>-->


</div>
</body>
</html>
<script type="text/javascript" src="{{base_path('js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{base_path('js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{base_path('js/config.js')}}"></script>
<script type="text/javascript" src="../../../public/js/layer/layer.js"></script>
<script>
    var user_id = $_GET['user_id'];//用户id
    console.log($_GET['user_id']);
    console.log('{{$open_id}}');
    setCookie("openid",'{{$open_id}}');

    //获取openId
//    $.ajax({
//        type:"get",
//        url: commonsUrl + "api/gxsc/get/user/openId" +versioninfos,
//        data:{
//            "code":$_GET['code']
//        },success:function(data){
//            if(data.code==1){
//                if(getCookie("openid")){
//                    setCookie("is_member",data.result.is_member);
//                }else{
//                    setCookie("openid",data.result.openId);
//                    setCookie("is_member",data.result.is_member);
//                }
//            }else{
//                layer.msg(data.msg);
//            }
//        }
//    });
    //获取验证码
    function getcode(){
        mobile = $('#inpmobile').val();
        if(testTel(mobile)){
            getCodeajax();
        }else{
            layer.msg('请输入正确的手机号');
        }
    }
    function downtime(){
        tt = 60;
        $('.getCode').html(tt);
        fortime = function timeDown(){
            if(tt==0){
                clearInterval(myInterval);
                $('.getCode').html('重新获取');
                $('.getCode').attr('onclick','getcode()');
            }else{
                tt--;
                $('.getCode').html(tt);
            }
        }
        myInterval = setInterval('fortime()',1000);
    }
    //获取验证码
    function getCodeajax(){
        $('.getCode').attr('onclick','');
        layer.load(2);
        $.ajax({
            url:commonsUrl+'api/gxsc/auth/send/user/sms'+versioninfos,
            timeout:TIMEOUT,
            method:'POST',
            data:{
                service_type:5,
                un:mobile
            },
            success:function(data){
                layer.closeAll();
                if(data.code==1){
                    downtime();
                }else{
                    layer.msg(data.msg);
                }
            },
            type:'JSON'
        });
    }
    //绑定
    function reg(){
        verify = $('#verify').val();
        inpmobile = $('#inpmobile').val();
        if(!verify||!inpmobile){
            layer.msg('请填写手机验证码');
        }else{
            verify = $('#verify').val();
            inpmobile = $('#inpmobile').val();
            $.ajax({
                url:commonsUrl+'api/gxsc/bind/user/openId'+versioninfos,
                timeout:TIMEOUT,
                method:'POST',
                data:{
                    'pin':verify,
                    'openId': getCookie('openid'),
                    'un':inpmobile,
                    "invite_id":user_id
                },
                success:function(data){
                    layer.closeAll();
                    if(data.code==1){
                        setTimeout(function(){
                            layer.msg("注册成功");
                        },300);

                        location.href='index.php';

                    }else{
                        layer.msg(data.msg);
                        $('#verify').val("");
                    }
                },
                type:'JSON'
            });
        }
    }
</script>