<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>开通邀请权限</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
</head>
<style>
    body{
        background: #f3f2f2;
    }
    input{
        border: none;
        width: 77%;
    }
    li{
        width: 92%;
        height: 50px;
        display: flex;
        align-items: center;
        padding: 0 4%;
        background: #fff;
        margin-bottom: 1px;
    }
    button{
        width: 92%;
        margin: 50px auto;
        height: 40px;
        border:none;
        border-radius: 5px;
        display: block;
        color: #fff;
        background: #eb3738;
    }
</style>
<body>
    <div>
        <ul>
            <li>
                <label>姓 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名 : &nbsp;&nbsp;</label>
                <input type="text" name="name" placeholder="请输入名字">
            </li>
            <li>
                <label>手机号码 : &nbsp;&nbsp;</label>
                <input type="text" name="pel" maxlength="11" placeholder="请输入手机号码">
            </li>
            <li>
                <label>支付金额 : &nbsp;&nbsp;</label>
                <span class="per"></span>
            </li>
        </ul>
        <button>去开通支付邀请权限</button>
    </div>
</body>
</html>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script>
$.ajax({
        type:"post",
        url: commonsUrl + "api/gxsc/user/profile" +versioninfos,
        data:{
           'ss':getCookie('openid')	
        },
        success:function(data){
              $(".per").text(data.result.need_deposit==0?data.result.deposit+"元":data.result.need_deposit+"元")
              console.log(data.result);
                if(data.result.user_lv=="5"){
                $("button").css("background","#b5b5b5")
              }

           
              $("button").click(function(){
                if(data.result.user_lv=="5"){
                    layer.msg("事业合伙人需支付金额较大，故采用线下支付银行到账后，自动开通邀请权限")
                }else{
                    const name=$("input[name='name']").val()
                    const pel=$("input[name='pel']").val()
                    const reg=/^[1][3,4,5,7,8][0-9]{9}$/
                    if(name.length==0){
                        layer.msg("名字不能为空")
                    }else if(pel.length==0){
                        layer.msg("手机号不能为空")
                    }else if(!reg.test(pel)){
                        layer.msg("手机号格式有误")
                    }else{
                
                        $.ajax({
                            type:"post",
                            url:commonsUrl + 'api/gxsc/userapplyinviterole' + versioninfos,
                            data:{
                                mobile:pel,
                                name:name,
                                open_id: getCookie("openid"),
                                ss:getCookie('openid')
                            },
                            success:data=>{
                                console.log(data)
                                if(data.code == 1) {
                                    console.log(data);
                                    data.result.timeStamp = data.result.timeStamp.toString();
                                    retStr = data.result;
                                    callpay();
                                    //调用微信JS api 支付
                                    function jsApiCall() {
                                        WeixinJSBridge.invoke(
                                            'getBrandWCPayRequest',
                                            retStr,
                                            function(res) {
                                                if(res.err_msg == "get_brand_wcpay_request:ok") {
                                                    //支付成功
                                                    location.href = 'myOrderList.php';
                                                } else {
                                                    //												             alert(res.err_msg);
                                                }
                                            }
                                        );
                                    }

                                    function callpay() {
                                        if(typeof WeixinJSBridge == "undefined") {
                                            if(document.addEventListener) {
                                                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                                            } else if(document.attachEvent) {
                                                document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                                                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                                            }
                                        } else {
                                            jsApiCall();
                                        }
                                    }
                                } else {
                                    layer.msg(data.msg);
                                }
                            }
                        })
                    }
                }

               
            })
        }
    });

    
</script>