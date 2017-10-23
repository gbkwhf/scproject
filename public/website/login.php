<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>登录</title>
		<link rel="stylesheet" href="css/common.css">
	    <link rel="stylesheet" href="css/login.css">
	</head>
	<body>
		<?php include 'header.php' ?>
		<div class="wrapper">
			<div class="content">
				<div class="reg">
					<div class="reginput">
						<div class="inp">
							<div class="inp-name">账号:</div>
							<input type="text" placeholder="手机号码" name="account" id="account" maxlength="11" onkeyup="this.value=this.value.replace(/\D/g,'')"/>
						</div>
						<div class="inp">
							<div class="inp-name">密码:</div>
							<input type="password" placeholder="密码" name="pwd" id="pwd"/>
						</div>
						<div class="inp">
							<div class="forget"><a href="forgot_password.php">忘记密码？</a></div>
						</div>
						<div id="submit">登<span style="display: inline-block;width:8px;"></span>录</div>
					</div>
					<div class="noaccount">
						<div class="text">
							<p>还没有账号？</p>
							<div style="cursor: pointer;" onclick="location.href='register.php'">立即注册<img src="images/register-login/go.png"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php include 'footer.php' ?>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
        <script type="text/javascript" src="js/layer/layer.js"></script>
        <script type="text/javascript" src="js/function.js"></script>
		<script>
			$('#submit').click(function(){
				var account=$('#account').val();
				var pwd=$('#pwd').val();
				
				var checkPwd = testPwd(pwd);
				var checkAccount = testAccount(account);
				
				if(checkAccount&&checkPwd){
					layer.load(2);
					$.ajax({
				    	type:'POST',
				    	url:commonUrl + 'api/stj/auth/login'+versioninfo,
				    	data:{
				    		'ct':'3',
				    		'un':account,
				    		'pw':pwd
				    	},
				    	success:function(data){
					    	layer.closeAll();
//					    	console.log(data);
		                  	if(data.code == 1){
		                  		layer.msg("登录成功",{time:1000},function(){
                                    setCookie('sessionArr',JSON.stringify(data.result));
                                    if(getCookie('sessionArr')){
                                        location.href='index.php';
//										setTimeout('history.go(-1)',1000);
                                    }
		                        });
		                	}else{
		                		layer.msg(data.msg);
		                	}
		              	}
		          	});
				}
				//校验账号
		        function testAccount(val){
		        	var reg = /^1[034578][0-9]{9}$/;
		            if(!reg.test(val)){
		                index= layer.tips("请输入11位有效的手机号码","#account",{tips:[2,'#21c0d5']});
		                $('#account').focus();
		                return false;
		            }else if(!val){
		                index= layer.tips("请输入帐号","#account",{tips:[2,'#21c0d5']});
		                $('#account').focus();
		                return false;
		            }else{
		                return true;
		            }
		        }
		        //校验密码
		        function testPwd(val){
		            if(!val){
		                index= layer.tips("请输入密码","#pwd",{tips:[2,'#21c0d5']});
		                $('#pwd').focus();
		                return false;
		            }else{
		                return true;
		            }
		        }
			});
			function showError(msg){
	            layer.alert(msg,{icon:2,title:false,closeBtn:false});
	        }
            
		</script>
	</body>
</html>
