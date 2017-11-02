<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>我的订单</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"><meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/my_order.css">
</head>

<body>

	<div class="wrapper">
		<div class="order-module">
			<h4>2017.10.25</h4>
			<i class="half-line"></i>
			<ul class="commodity-list">
				<li>
					<img src="images/zyyp.png" width="77"/>
					<p>长城 (Gmsdfgsdnm) 红酒 华夏葡萄  (711) 张裕解百纳</p>
				</li>
				<li>
					<img src="images/zyyp.png" width="77"/>
					<p>长城 (Gmsdfgsdnm) 红酒 华夏葡萄  (711) 张裕解百纳</p>
				</li>
				<li>
					<img src="images/zyyp.png" width="77"/>
					<p>长城 (Gmsdfgsdnm) 红酒 华夏葡萄  (711) 张裕解百纳</p>
				</li>
			</ul>
			<i class="half-line" style="float: right;"></i>
			<div class="summary">
				<p>实付款：<em>¥388.00</em></p>
				<span>共11件</span>
			</div>
			<div class="state-box">
				<p>已完成</p>
			</div>
		</div>
		<div class="order-module">
			<h4>2017.10.25</h4>
			<i class="half-line"></i>
			<ul class="commodity-list">
				<li>
					<img src="images/zyyp.png" width="77"/>
					<p>长城 (Gmsdfgsdnm) 红酒 华夏葡萄  (711) 张裕解百纳</p>
				</li>
				<li>
					<img src="images/zyyp.png" width="77"/>
					<p>长城 (Gmsdfgsdnm) 红酒 华夏葡萄  (711) 张裕解百纳</p>
				</li>
				<li>
					<img src="images/zyyp.png" width="77"/>
					<p>长城 (Gmsdfgsdnm) 红酒 华夏葡萄  (711) 张裕解百纳</p>
				</li>
			</ul>
			<i class="half-line" style="float: right;"></i>
			<div class="summary">
				<p>实付款：<em>¥388.00</em></p>
				<span>共11件</span>
			</div>
			<div class="state-box">
				<p>已完成</p>
			</div>
		</div>
	</div>
	
</body>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script>
	
	var winW = $(window).width();
	$('.commodity-list li p').width(winW-145);
	
	$('.commodity-list li:last').css('padding-bottom','0px');
	
</script>
<style type="text/css">
	.layui-layer.layui-anim.layui-layer-page{
		border-radius: 5px;
	} 
</style>
</html>