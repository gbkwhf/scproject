<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>重置密码</title>
		<link rel="stylesheet" href="css/common.css">
	    <link rel="stylesheet" href="css/forgot_password.css">
	</head>
	<body>
		<?php include 'header.php' ?>
		<div class="reg">
			<div class="content">
				<p class="reset">重置密码</p>
				<div class="reginput">
					<div class="inp">
						<div class="inp-name">账<span style="display: inline-block;width:2em;"></span>号</div>
						<input type="text" placeholder="请输入您要找回的账号" name="tel" id="tel" maxlength="11" onkeyup="this.value=this.value.replace(/\D/g,'')"/>
						<div class="clearfix"></div>
					</div>
					<div class="inp">
						<div class="inp-name">密<span style="display: inline-block;width:2em;"></span>码</div>
						<input type="password" placeholder="设置您的密码" name="pwd1" id="pwd1"/>
					</div>
					<div class="inp">
						<div class="inp-name">确认密码</div>
						<input type="password" placeholder="请再次输入密码" name="pwd2" id="pwd2"/>
					</div>
					<div class="inp yanz">
						<div class="inp-name">验<span style="display: inline-block;width:0.5em;"></span>证<span style="display: inline-block;width:0.5em;"></span>码</div>
						<input type="text" placeholder="验证码" name="code" id="code"/>
						<div class="code">获取验证码</div>
						<div class="wait">发送中..<span>60</span></div>
						<div class="clearfix"></div>
					</div>
					<div id="submit" onclick="gosubmit()">提<span style="display: inline-block;width:8px;"></span>交</div>
				</div>
			</div>
		</div>
		<?php include 'footer.php' ?>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
		<script type="text/javascript" src="js/layer/layer.js"></script>
		<script>
			$('.code').click(function(){
	            var tel = $('#tel').val(); //电话
	            var pwd1 = $('#pwd1').val(); //设置密码
	            var pwd2 = $('#pwd2').val(); //确认密码
	            
	            var checkPwd = testPwd(pwd1,pwd2);
	            var checkTel = testTel(tel);
	            
	            if(checkPwd&&checkTel){
	            	layer.load(2);
					$.ajax({
				    	type:'POST',
				    	url:commonUrl + 'api/stj/auth/reset/password/send/sms'+versioninfo,
				    	data:{
				    		'un':tel
				    	},
				    	success:function(data){
				    		layer.closeAll();
//				    		console.log(data);
		                    if(data.code == 1){
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
        	//提交
        	function gosubmit(){
        		var tel = $('#tel').val(); //电话
	            var pwd1 = $('#pwd1').val(); //设置密码
	            var pwd2 = $('#pwd2').val(); //确认密码
	            var code =$('#code').val();
	            
	            var checkCode = testCode(code);
	            var checkPwd = testPwd(pwd1,pwd2);
	            var checkTel = testTel(tel);
	            
	            if(checkCode&&checkPwd&&checkTel){
	            	$.ajax({
	                    type: "POST",
	                    url: commonUrl + 'api/stj/auth/reset/password' +versioninfo,
	                    data: {
	                    	'un': tel,
	                    	'pw': pwd1,
	                    	'pin': code
	                    },
	                    dataType: 'json',
	                    success: function(data){
	//	                    console.log(data);
	                        if(data.code==1){
	                            layer.msg('重置密码成功',{time:1000},function(){
									location.href='login.php';
	                            });
	                        }else{
	                            layer.msg(data.msg);
	                        }
	                    }
	                });
	            }
            	
			}
	        //校验手机号
	        function testTel(val){
	        	var reg = /^1[034578][0-9]{9}$/;
	            if(!reg.test(val)){
	                index= layer.tips("请输入11位有效的手机号码","#tel",{tips:[2,'#21c0d5']});
	                $('#tel').focus();
	                return false;
	            }else if(!val){
	                index= layer.tips("请输入要找回的账号","#tel",{tips:[2,'#21c0d5']});
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
	            	$('#pwd1').focus();
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
		</script>
	</body>
</html>
