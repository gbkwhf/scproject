<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>共享商城</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"><meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>

	<div class="wrapper">
		<div class="module-box" onclick="location.href='member_zone.php'">
			<div class="pic-module">				
				<img src="images/shopping-pic.jpg" width="100%"/>
				<span></span>
			</div>
			<h4>购物商城</h4>
		</div>
		<div class="module-box" onclick="location.href='personal_center.php'">
			<div class="pic-module">				
				<img src="images/personal-center.jpg" width="100%"/>
				<span></span>
			</div>
			<h4>个人中心</h4>
		</div>
	</div>
</body>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script>
	
	$.ajax({
		type:"get",
		url: commonsUrl + "api/gxsc/get/user/openId" +versioninfos,
		data:{
			"code":$_GET['code']
		},success:function(data){
			if(data.code==1){
				setCookie("openid",data.result.openId);
				alert(getCookie("openid"));
			}
		}
	});
	
</script>
<style type="text/css">
	.layui-layer.layui-anim.layui-layer-page{
		border-radius: 5px;
	} 
</style>
</html>