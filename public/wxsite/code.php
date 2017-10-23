<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>二维码名片</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/code.css" />
	</head>
	<body>
		<div class="container">
			<div class="content">
				<div class="code-box">
					<!--<p>已经为您生成邀请码</p>
					<img src="" />-->
				</div>
				<p class="prompt">快去邀请您的好友成为会员吧</p>
			</div>		
		</div>
		<script src="js/common.js"></script>
		<script src="js/jquery.min.js"></script>
		<script src="js/layer/layer.js"></script>
		<script>
			winW=$(window).width();
			winH=$(window).height();
			$(".content").width(winW-30);
			$(".content").css("top",winH*0.5);
			
			pic=$_GET['pic'];
			html="";			
			html+='<p>已经为您生成邀请码</p>';
			html+='<img src="'+pic+'" />';
			$(".code-box").html(html);
		</script>
	</body>
</html>