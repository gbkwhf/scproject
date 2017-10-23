<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>修改密码</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/reset_password.css" />
	</head>
	<body>
		<div class="container">
			<div class="content">
				<div class="border-box">
					<input type="password" placeholder="请输入原密码" id="yma" onkeyup="value=value.replace(/[^\w]/ig,'')" />
				</div>
				<div class="border-box">
					<input type="password" placeholder="请输入新密码" id="xma" onkeyup="value=value.replace(/[^\w]/ig,'')" />
				</div>
				<div class="border-box">
					<input type="password" placeholder="请确认新密码" id="qrma" onkeyup="value=value.replace(/[^\w]/ig,'')" />
				</div>
				<p class="explain">密码由8-20个英文字母、数字组成</p>
				<a href="javascript:;" onclick="confirm()" class="confirm">确定</a>
			</div>			
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script src="js/layer/layer.js"></script>
		<script>
		
			session = getCookie('session');
			session=session.substr(1,session.length-2);
		
			winH=$(window).height();
			winW=$(window).width();
			$(".container").height(winH);
			$(".content").width(winW-30);
			
			$("input").focus(function(){
				$(this).parent(".border-box").siblings(".border-box").css("border","1px solid #ccc");
				$(this).parent(".border-box").css("border","1px solid #21c0d5")
			})
		
			function confirm(){
                yma = $('#yma').val();
                xma = $('#xma').val();
                qrma=$('#qrma').val();
                
                if(yma == ''||xma==''||qrma==''){
                    layer.msg("请填写完整！");
                }else{
                	if(xma!=qrma){
	                	layer.msg("两次密码不一致，请重新输入")
	                }else{
	                    //修改成功
	                    $.ajax({
	                    	type:"post",
	                    	url:commonUrl+'api/stj/auth/update/password'+versioninfo,
	                    	data:{
	                    		'npw':xma,
	                    		'opw':yma,
	                    		'ss':session
	                    	},
	                    	success:function(data){
	                    		if(data.code==1){
	                    			console.log(data);
									layer.msg("修改成功");
									backlogin=function(){
										location.href='sign_in.php'
									}
									setInterval(backlogin,3000)
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