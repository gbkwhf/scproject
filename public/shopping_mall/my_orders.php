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
		<!--<div class="order-module">
			<h4>2017.10.25</h4>
			<i class="half-line"></i>
			<ul class="commodity-list">
				<li>
					<div class="picbox">						
						<img src="images/rice.png" width="100%"/>
					</div>
					<div class="commodity-info">
						<em>长城 (Gmsdfgsdnm) 红酒 华夏葡 萄 (711) 张裕解百纳</em>
						<div class="price-info">
							<p>¥388.00</p>
							<span>x 1</span>
						</div>
					</div>
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
		</div>-->
	</div>
	
</body>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script>
	
	var winW = $(window).width();
	
	
	//获取已完成的订单列表
	$.ajax({
		type:"post",
		url:commonsUrl+"api/gxsc/get/commodity/order/info/list"+versioninfos,
		data:{'ss':getCookie('openid')},
		success:function(data){
			if(data.code==1){
				console.log(data);
				html='';
				for(var i=0;i<data.result.length;i++){
					html+='<div class="order-module">'+
					'	<h4>'+data.result[i].create_time+'</h4>'+
					'	<i class="half-line"></i>'+
					'	<ul class="commodity-list">';
					for(var j=0;j<data.result[i].goods_info.length;j++){
						html+='<li>'+
						'	<div class="picbox">'+						
						'		<img src="'+data.result[i].goods_info[j].image+'" width="100%"/>'+
						'	</div>'+
						'	<div class="commodity-info">'+
						'		<em>'+data.result[i].goods_info[j].goods_name+'</em>'+
						'		<div class="price-info">'+
						'			<span>x '+data.result[i].goods_info[j].number+'</span>'+
						'		</div>'+
						'	</div>'+
						'</li>';
					}
					html+='	</ul>'+
					'	<i class="half-line" style="float: right;"></i>'+
					'	<div class="summary">'+
					'		<p>实付款：<em>¥'+data.result[i].price+'</em></p>'+
					'		<span>共'+data.result[i].number+'件</span>'+
					'	</div>'+
					'	<div class="state-box">'+
					'		<p>已完成</p>'+
					'	</div>'+
					'</div>';
				}
				$('.wrapper').html(html);
				$('.commodity-info').width(winW-155);
				
			}else{
				layer.msg(data.msg);
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