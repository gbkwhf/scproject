
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>授权码</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/authorization_code.css" />
	</head>
	<body>
		<div class="container">
			<div class="border-box">
				<h4>已经为您生成授权码</h4>
				<img src="image/refresh.png" width="19" class="refresh" />
				<div class="sqm">
					<!--<p>授权码：<span>52144444565456</span></p>-->
				</div>
				<p>您的所有病历已生成为授权码, 您可以将它分享给您的主治医师</p>
				<img src="image/create.png" width="100" class="create" />				
			</div>
			<p class="share-p">快去分享给专家吧</p>
			<!--<a href="javascript:;" class="share-btn">立即分享</a>-->
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script>
		
		session = getCookie('session');
		session=session.substr(1,session.length-2);
		
		code();
		$(".refresh").click(function(){
			code();
		})
		function code(){
			$.ajax({
				type:"post",
				url: commonUrl + 'api/stj/auth_code'+versioninfo,
				data:{
					'ss':session
				},
				success:function(ret){
					if(ret.code==1){
						console.log(ret)
						html="";
						html+='<p>授权码：<span>'+ret.result.code+'</span></p>';
						$(".sqm").html(html);
					}else if(ret.code==1011){
						layer.msg('该用户登陆数据已过期，请重新登陆');
	                	setTimeout("location.href='sign_in.php'",1000);
					}else{
						layer.msg(ret.msg)
					}
				}
			})
		}
			
		</script>
		<style>
	        .layui-layer{
	            left:0;
	        }
	        .ui-loader{
	            display: none;
	        }
	    </style>
	</body>
</html>