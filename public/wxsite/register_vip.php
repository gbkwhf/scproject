<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>注册</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/register_vip.css" />
	</head>
	<body>
		<div class="container">
			<img src="image/sign-icon.png" class="sign" />
			<div class="form-box">
				<div class="pic-box">
					<img src="image/phone.png" width="13px" height="19px" />
				</div>
				<div class="input">
					<input type="text" placeholder="请输入手机号" maxlength="11" id="phone" onkeyup="this.value=this.value.replace(/\D/g,'')" />
				</div>
			</div>
			<div class="form-box">
				<div class="pic-box">
					<img src="image/yz-icon.png" width="16px" height="18px" style="margin-top: 20px;"/>					
				</div>
				<div class="input">
					<input type="text" placeholder="请输入验证码" maxlength="6" id="telcode" />
					<a class="yz-btn checkCode" href="javascript:;">获取验证码</a>
                    <a class="yz-btns waitCode" href="javascript:;">60</a>
				</div>
			</div>
			<div class="form-box">
				<div class="pic-box">
					<img src="image/mm-icon.png" width="15px" height="17px" style="margin-top: 20px;" />					
				</div>
				<div class="input">
					<input type="password" placeholder="请输入密码" id="password" onkeyup="value=value.replace(/[^\w]/ig,'')" />
				</div>
			</div>
			<a href="javascript:;" onclick="regist()" class="regist">注册</a>
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script src="js/layer/layer.js"></script>
		<script>			
			$(".yz-btn").click(function(){
				phone = $('#phone').val();
				
				var myreg = /^1[034578][0-9]{9}$/; //正则判断手机号是否有效
								
				if(phone==""||phone.length<11||!myreg.test($("#phone").val())){
					layer.msg("请填写11位有效的手机号")
				}else{
					$.ajax({
						type:"post",
						url:commonUrl+'api/stj/auth/register/send/sms'+versioninfo,
						data:{
							'un':phone
						},
						success:function(data){
							if(data.code==1){
								console.log(data);
								checkcode=data.result.msg.replace(/[^0-9]/ig,"");
								console.log(checkcode);
								//发送成功
	                            layer.msg('发送成功！');
	                            $('.checkCode').hide();
	                            $('.waitCode').show();
	                            endtimes = $('.waitCode').html();
	                            ptimes = function(){
	                                if(endtimes>1){
	                                    endtimes --;
	                                    $('.waitCode').html(endtimes);
	                                }else{
	                                    $('.checkCode').show();
	                                    $('.waitCode').hide();
	                                    $('.checkCode').html('重新获取验证码');
										
	                                }
	                            }
	                            setInterval(ptimes,1000);
								
							}else{
								layer.msg(data.msg);
							}
						}
					});
				}
			})
			
			function regist(){
                phone = $('#phone').val();
                telcode = $('#telcode').val();
                passwords=$('#password').val();
                if(passwords == ''||phone==''||telcode==''){
                    layer.msg("请填写完整！");
                }else if(passwords.length<8||passwords.length>20){
						layer.msg("请输入8-20位英文字母、数字")
				}else{
	                layer.load(2);
	                //成功注册
	                $.ajax({
	                	type:"post",
	                	url:commonUrl+'api/stj/auth/register'+versioninfo,
	                	data:{
	                		'pin':telcode,
	                		'pw':passwords,
	                		'un':phone
	                	},
	                	success:function(data){
	                		layer.closeAll();
	                		if(data.code==1){	                			
	                			console.log(data);
	                			layer.msg("注册成功，赶快去登录吧");
	                			signnext=function(){
	                				location.href='sign_in.php';
	                			}
	                			setInterval(signnext,2000);
	                	}else{
	                			layer.msg(data.msg);
	                		}
	             		
	                	}
	                });
            }
			}
		</script>
	</body>
</html>