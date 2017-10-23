<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>注册</title>
		<link rel="stylesheet" href="css/common.css">
	    <link rel="stylesheet" href="css/register.css">
	</head>
	<body>
		<?php include 'header.php' ?>
		<div class="reg">
			<div class="content">
				<div class="reginput">
					<div class="inp">
						<div class="inp-name"><span>*</span>用户名</div>
						<input type="text" placeholder="您的账户名" name="name" id="name" maxlength="6" onkeyup="filter()"/>
					</div>
					<div class="inp">
						<div class="inp-name"><span>*</span>手机号码</div>
						<input type="text" placeholder="建议使用常用手机" name="tel" id="tel" maxlength="11" onkeyup="this.value=this.value.replace(/\D/g,'')"/>
					</div>
					<div class="inp">
						<div class="inp-name"><span>*</span>密码</div>
						<input type="password" placeholder="设置您的密码" name="pwd1" id="pwd1"/>
					</div>
					<div class="inp">
						<div class="inp-name"><span>*</span>确认密码</div>
						<input type="password" placeholder="请再次输入密码" name="pwd2" id="pwd2"/>
					</div>
					<div class="inp yanz">
						<div class="inp-name"><span>*</span>手机验证码</div>
						<input type="text" placeholder="验证码" name="code" id="code"/>
						<div class="code">获取验证码</div>
						<div class="wait">发送中..<span>60</span></div>
					</div>
					<!--<div class="agree">
						<input type="checkbox" name="agreen" checked>我已阅读并同意<span>《注册协议》</span>
					</div>-->
					<div id="submit">注<span style="display: inline-block;width:8px;"></span>册</div>
				</div>
				<img src="images/register-login/logo2.png" style="margin-top:113px"/>
				<div class="clearfix"></div>
			</div>
		</div>
		<?php include 'footer.php' ?>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
		<script type="text/javascript" src="js/layer/layer.js"></script>
		<script>
			
        	$('.code').click(function(){
        		var name = $('#name').val(); //用户名
	            var tel = $('#tel').val(); //电话
	            var pwd1 = $('#pwd1').val(); //设置密码
	            var pwd2 = $('#pwd2').val(); //确认密码
	            
	            //格式校验
	            var checkPwd = testPwd(pwd1,pwd2);
	            var checkTel = testTel(tel);
	            var checkName = testName(name);
	            
	            if(checkName&&checkTel&&checkPwd){
	            	layer.load(2);
					$.ajax({
				    	type:'POST',
				    	url:commonUrl + 'api/stj/auth/register/send/sms'+versioninfo,
				    	data:{
				    		'un':tel
				    	},
				    	success:function(data){
				    		layer.closeAll();
//				    		console.log(data);
		                    if(data.code == 1){
	//                          //发送成功
	                          	layer.msg('发送成功！');
	                          	code=data.result.pin;
	                          	$('.code').hide();
	                          	$('.wait').show();
	                          	endtimes = $('.wait>span').html();
	                          	ptimes = function(){
	                              	if(endtimes>1){
	                                  	endtimes --;
	                                  	$('.wait>span').html(endtimes);
	                              	}else{
	                                  	$('.code').show();
	                                  	$('.wait').hide();
	                                  	$('.code').html('重新获取验证码');
	                              	}
	                          	}
	                          	setInterval(ptimes,1000);
	                      	}else{
	                      		layer.msg(data.msg);
	                      	}
		                }
		            });
	           }
        	});
        	
        	$('#submit').click(function(){
	            var name = $('#name').val(); //用户名
	            var tel = $('#tel').val(); //电话
	            var pwd1 = $('#pwd1').val(); //设置密码
	            var pwd2 = $('#pwd2').val(); //确认密码
	            var code = $('#code').val(); //验证码
	            
	            //格式校验
	            var checkCode = testCode(code);
	            var checkPwd = testPwd(pwd1,pwd2);
	            var checkTel = testTel(tel);
	            var checkName = testName(name);
	            
	            if(checkName&&checkTel&&checkPwd&&checkCode){
	            	layer.load(2);
					$.ajax({
				    	type:'POST',
				    	url:commonUrl + 'api/stj/auth/register'+versioninfo,
				    	data:{
				    		'name':name,
				    		'pin':code,
				    		'pw':pwd2,
				    		'un':tel
				    	},
				    	success:function(data){
				    		layer.closeAll();
//				    		console.log(data);
		                    if(data.code == 1){
	//                          //发送成功
	                          	layer.msg('注册成功！');
                                setTimeout("location.href='login.php'",1000);
	                      	}else{
	                      		layer.msg(data.msg);
	                      	}
		                }
		            });
	            }
	            
        	});
        	//校验用户名有问题
	        function testName(val){
	            if(val.length>18){
	                index= layer.tips("用户名长度不超过6位","#name",{tips:[2,'#21c0d5']});
	                $('#name').focus();
	                return false;
	            }else if(val==''){
	            	index= layer.tips("请填写用户名，长度不超过6位","#name",{tips:[2,'#21c0d5']});
	                $('#name').focus();
	                return false;
	            }else{
	                return true;
	            }
	        }
	        //校验手机号
	        function testTel(val){
	            var reg = /^1[034578][0-9]{9}$/;
	            if(!reg.test(val)){
	                index= layer.tips("请填写正确手机号","#tel",{tips:[2,'#21c0d5']});
	                $('#tel').focus();
	                return false;
	            }else{
	                return true;
	            }
	        }
	        //校验密码
	        function testPwd(val1,val2){
	        	var reg = /^[a-zA-Z0-9]{8,20}$/;
	            if(!val1){
	                layer.tips("请填写密码","#pwd1",{tips:[2,'#21c0d5']});
	                $('#pwd1').focus();
	                return false;
	            }else if(!val2){
	                layer.tips("请确认密码","#pwd2",{tips:[2,'#21c0d5']});
	                $('#pwd2').focus();
	                return false;
	            }else if(val1!=val2){
	                layer.tips("两次密码不一致，请重新填写","#pwd2",{tips:[2,'#21c0d5']});
	                $('#pwd2').focus();
	                return false;
	            }else if(!reg.test(val1)){
	            	layer.tips("密码由8-20位字母,数字组成","#pwd1",{tips:[2,'#21c0d5']});
			        return false;
			    }else{
	                return true;
	            }
	        }
	        //校验验证码
	        function testCode(val){
	            if(!val){
	                layer.tips("请填写验证码","#code",{tips:[2,'#21c0d5']});
	                return false;
	            }else{
	                return true;
	            }
	
	        }
	        //校验用户名
	        function filter(){
				var name=$("#name").val();
				name=name.replace(/([^0-9a-zA-Z\u4e00-\u9fa5]+)$/,'');//英文字母、数字、中文
				$("#name").val(name);
			}
		</script>
	</body>
</html>
