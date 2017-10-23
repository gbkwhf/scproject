<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>登录</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/register_vip.css" />
	</head>
	<body>
		<div class="container">
			<img src="image/logo2.png" class="sign" />
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
					<img src="image/mm-icon.png" width="15px" height="17px" style="margin-top: 20px;" />					
				</div>
				<div class="input">
					<input type="password" placeholder="请输入密码" id="password" />
				</div>
			</div>
			<div class="btn-box">
				<a href="register_vip.php" class="zc-btn">注册</a>
				<a href="javascript:;" onclick="sign()" class="dl-btn">登录</a>
			</div>
			<a href="forget_password.php" class="forget-btn">忘记密码？</a>			
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script src="js/layer/layer.js"></script>
		<script>
			mright=$(".btn-box").css("margin-right");
			$(".forget-btn").css("margin-right",mright);	
				
			function sign(){
				phone = $('#phone').val();
				passwords=$('#password').val();
				
				var myreg = /^1[034578][0-9]{9}$/; //正则判断手机号是否有效
                
				if(passwords == ''||phone==''){
                    layer.msg("请填写完整！");
                }else{
					if(phone.length<11||!myreg.test($("#phone").val())){
						layer.msg("请填写11位有效的手机号")
					}else{
						$.ajax({
							type:"post",
							url:commonUrl+'api/stj/auth/login'+versioninfo,
							data:{
								'ct':3,
								'pw':passwords,
								'un':phone
							},
							success:function(data){
								if(data.code==1){
									
									console.log(data);
		                            setCookie('session',JSON.stringify(data.result.session));
		                            if(getCookie('session')){
											                            	
										var referrer=document.referrer;//上一页面的url
										if(referrer.indexOf("register_vip.php")>0||referrer.indexOf("forget_password.php")>0||referrer.indexOf("reset_password.php")>0||referrer.indexOf("personal_information.php")>0||referrer==""){
											//判断上一页面是否为注册页，若为注册页、忘记密码页、修改密码页则默认返回首页
											location.href='index.php';
										}else{
											//返回上一页
//											 history.go(-1);
											location.href=document.referrer
										}
										
		                                
		                            }
								}else{
									layer.msg(data.msg);
								}							
							}
						});
					}
				}
			}
		</script>
	</body>
</html>