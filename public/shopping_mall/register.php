<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>用户绑定</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes"><meta name="format-detection" content="telephone=no" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
        <link rel="stylesheet" type="text/css" href="css/common.css"/>
        <link rel="stylesheet" type="text/css" href="css/register.css"/>
        <style>
            
        </style>
    </head>
	<body>

		
        <!-- 未绑定 -->
        <div class="regbox">
        	<img src="images/logoimg.png" class="logoimg" />
            <div class="mesLogin">
                <div class="inpdiv">
                	<input class="inp inpmobile" maxlength="11" onkeyup="value=value.replace(/[^0-9.]/g,'') " id="inpmobile" type="text" placeholder="请输入手机号"/>
                </div>
                <div class="inpdiv">
                    <input class="inp verify" id="verify" maxlength="6" type="text" placeholder="请输入验证码"/>
                    <div class="getCode" onkeyup="value=value.replace(/[^0-9.]/g,'') " onclick="getcode()">获取验证码</div>
                    <div class="clearfix"></div>
                </div>
                <div class="checkbox">
                	<input type="checkbox" name="consent" id="" style="-webkit-appearance:checkbox"/>同意授权条约
                </div>
                <div class="clearfix"></div>
                <div class="note"></div>
                <div class="inpdiv" style="border: none;">
                    <div class="bind-btn" onclick="reg()">绑定</div>
                </div>
            </div>
        </div>
	
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript" src="js/config.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script>
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
            layer.load(2);
            $.ajax({
                url:commonsUrl+'api/gxsc/auth/send/user/sms'+versioninfos,
//              timeout:TIMEOUT,
                method:'POST',
                data:{
                	service_type:5,
                    un:mobile
                },
                success:function(data){
                    layer.closeAll();
                    if(data.code==1){
                        $('.getCode').attr('onclick','');
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
            let checked=$("input[type='checkbox']").is(':checked')
            if(!verify||!inpmobile){
                layer.msg('请填写手机验证码');
            }else if(!checked){
            	layer.msg('请同意授权');
            }else{
            	verify = $('#verify').val();
            	inpmobile = $('#inpmobile').val();
                $.ajax({
                    url:commonsUrl+'api/gxsc/bind/user/openId'+versioninfos,
//                  timeout:TIMEOUT,
                    method:'POST',
                    data:{
                        'pin':verify,
                        'openId': getCookie('openid'),
                        'un':inpmobile
                    },
                    success:function(data){
                        layer.closeAll();
                        if(data.code==1){
							layer.msg("绑定成功");
							
							var lastpage = window.document.referrer;
							setTimeout(function(){
								if (/(iPhone|iPad|iPod)/i.test(navigator.userAgent)) {
	                                window.location.href = window.document.referrer;
	                            } else {
	                                window.history.go(-1);	 
	                        	}
							},1000)
                            
                        }else{
                            layer.msg(data.msg);
                        }
                    },
                    type:'JSON'
                });
            }
        }
    </script>
	</body>
</html>
