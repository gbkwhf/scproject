<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>物流详情</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="css/common.css">
		<link rel="stylesheet" href="css/logistical.css">
	</head>

	<body>
		<!--上面部分-->
		<div class="logisticBox">
			<!--<p class="logStaBox"><span class="logStatus">状态：</span><span class="logStatus1"></span></p>
			<p class="logStaBox1"><span class="logStatu2">订单编号：</span><span id="orderNumber"></span></p>-->
		</div>
		<!--下面部分内容-->
		<div class="logistBot">
			<ul id="lofinContext">
				<!--<li class="liBox">
				<div class="liBoxLeft">
					<div class="liTopWap3 liTopWap4"></div>
					<div class="liLine"></div>
				</div>
				<div class="liBoxRight">
					<div class="logCon"><span>期待菜鸟一哈哈哈哈哈哈哈哈哈哈期待菜鸟一哈哈哈哈哈哈哈哈</span>     <span class="exPress">韵达快递</span></div>
					<div class="logCon1"><span>2017-10-24  10.36.36</span></div>
				</div>
			</li>-->

			</ul>
		</div>
	</body>
	<script src="js/jquery.min.js"></script>
	<script src="js/layer/layer.js"></script>
	<script src="js/common.js"></script>
	<script src="js/config.js"></script>
	<script type="text/javascript">
		$(function() {
			$.ajax({ //获取物流信息
				type: "post",
				dataType: 'json',
				url: commonsUrl + "api/gxsc/get/commodity/sub_order/info" + versioninfos,
				data: {
					'ss': getCookie('openid'), //请求参数
					'sub_order_id': $_GET['sub_order_id'] //子订单id
				},
				success: function(data) {
					console.log(data)
					if(data.code == 1) { //请求成功
						var tm = '';
						tm += '<p class="logStaBox"><span class="logStatus">状态：</span><span class="logStatus1">' + data.result.express.state + '</span></p><p class="logStaBox1"><span class="logStatu2">快递单号：</span><span id="orderNumber">' + data.result.express_num + '</span></p><p class="logStaBox2"><span class="logStatu2">订单编号：</span><span id="orderNumber">' + data.result.sub_order_id + '</span></p>'
						$('.logisticBox').append(tm);
						var con = data.result.express.data;
						var express_name = data.result.express_name;//快递名称
						if(con.length != 0) {
							console.log(con);
							var html = '';
							$.each(con, function(k, v) {
								var context = con[k].context; //描述
								console.log(context);
								var time = con[k].time; //快递时间
								html += "<li class='liBox'>" +
									"<div class='liBoxLeft'>" +
									"<div class='liTopWap3 liTopWap4'></div>" +
									"<div class='liLine'></div>" +
									"</div>" +
									"<div class='liBoxRight'>" +
									"<div class='logCon'><span>" + context + "</span><span class='express'>" + express_name + "</span></div>" +
									"<div class='logCon1'><span>" + time + "</span></div>" +
									"</div>" +
									"</li>";
							});
							$('#lofinContext').append(html); //动态物流信息
							var val = "<div class='liTopWap'><div class='liTopWap1'><div class='liTopWap3'></div></div></div><div class='liLine'></div>";
							//这是最后那一个没有线条
							var lastVal = "<div class='liTopWap3 liTopWap4'></div>";
							$(".liBox:first").find('.liBoxLeft').html(val);
							$(".liBox:first").find('.liBoxRight').css('color', '#5ac8af');
							$(".liBox:last").find('.liBoxLeft').html(lastVal); 
						} else {
							$('.wrapper').html('<p>暂时没有您的物流信息哦！</p>');
							$('.wrapper p').css({
								'text-align': 'center',
								'color': '#c6bfbf',
								'line-height': winH + 'px'
							});
						}
					}

				}
			});

		})
	</script>

</html>