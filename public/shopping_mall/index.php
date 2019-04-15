<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>共享商城</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="css/common.css">
		<link rel="stylesheet" type="text/css" href="css/newIndex.css" />
	</head>

	<body>
		<div class="companyBanner"><img src="images/comBanner.jpg" /></div>
		<div class="member" onclick="location.href='memberPages.php'">
			<img src="images/gxMall.png" />
		</div>
		<!--<div class="no_member">
			<img src="images/association.png" onclick="location.href='setMeal.php'" />
		</div>-->
		<div class="gameBox" onclick="waitting()">
			<img src="images/amusement.png" />
		</div>
	</body>
	<script src="js/jquery.min.js"></script>
	<script src="js/layer/layer.js"></script>
	<script src="js/common.js"></script>
	<script src="js/config.js"></script>
	<script>
		//权限请求

		//获取openid
		$.ajax({
			type: "get",
			url: commonsUrl + "api/gxsc/get/user/openId" + versioninfos,
			data: {
				"code": $_GET['code']
			},
			success: function(data) {
				if(data.code == 1) {
					//                if(getCookie("openid")){
					//                    setCookie("is_member",data.result.is_member);
					//                    checkBind();
					//                }else{
					//                    setCookie("openid",data.result.openId);
					//                    setCookie("is_member",data.result.is_member);
					//                    checkBind();
					//                }
					setCookie("openid", data.result.openId);
					setCookie("is_member", data.result.is_member);
					checkBind();
				} else {
					layer.msg(data.msg);
				}
			}
		});

		function checkBind() {
			//验证openid是否绑定
			$.ajax({
				type: 'post',
				url: commonsUrl + 'api/gxsc/scan/this/phone/bind/openId' + versioninfos,
				data: {
					'openId': getCookie("openid")
				},
				success: function(data) {
					if(data.code == 1) {
						console.log(data);
						if(data.result.state == 0) { //未绑定
							location.href = 'register.php';
						} else if(data.result.state == 1) { //已绑定
						
						}
					} else {
						layer.msg(data.msg);
					}
				}
			})
		};

		function waitting() {
			layer.msg('暂未开通，敬请期待哦！')
//			location.href="http://www.365118.cn/game/index.html "
		}
	</script>
	<style type="text/css">
		.layui-layer.layui-anim.layui-layer-page {
			border-radius: 5px;
		}
	</style>

</html>