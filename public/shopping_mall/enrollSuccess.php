<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>报名成功</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="css/common.css">
		<link rel="stylesheet" type="text/css" href="css/enrollSuccess.css" />
	</head>

	<body>
		<div class="successPrompt">报名成功</div>
		<div class="gift">成功领取了一份精美礼品，前往店内领取哦~</div>
		<div class="look"><img src="images/look1.png" /></div>
		<div class="receiveSub">立即领取</div>
	</body>

</html>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script type="text/javascript">
	$(function() {
		var user_id = $_GET['user_id']; //获取用户id
		$(".receiveSub").click(function() {
			$.ajax({ //用户进店确认领取礼品
				type: "post", //请求方式
				dataType: 'json',
				url: commonsUrl + 'api/gxsc/invitememberreceive' + versioninfos, //请求接口
				data: {
					"user_id": user_id //用户id
				},
				success: function(data) {
					console.log(data)
					if(data.code == 1) { //请求成功
						location.href = "receiveSuccess.php";
					} else {
						layer.msg(data.msg);
					}

				}
			});
		})

	})
</script>