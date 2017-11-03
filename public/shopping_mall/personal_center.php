<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>个人中心</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"><meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/personal_center.css">
</head>

<body>

	<div class="wrapper">
		<header>
			<div class="user-info">
				<div class="head-portrait">
					<img src="images/head-portrait.png" width="55"/>
				</div>
				<p></p>
			</div>
			<div class="account-info">
				<div class="balance">
					<em>¥</em>
					<p>3888.00</p>
					<span>余额（可提现）</span>
				</div>
				<div class="cashback">
					<em>¥</em>
					<p>563.00</p>
					<span>当日返现</span>
				</div>
			</div>
		</header>
		<ul class="menu-list">
			<li onclick="location.href='my_orders.php'">
				我的订单
				<img src="images/right-arrow.png" width="8"/>
			</li>
			<!--<li onclick="location.href='invitation.php'">
				我的邀请
				<img src="images/right-arrow.png" width="8"/>
			</li>-->
		</ul>
	</div>
	<!--购物车-->
	<div class="shopping-cart" onclick="location.href='shopCart.php'">
		<img src="images/shopping-cart.png"/>
		<span></span>
	</div>
</body>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script>
		//获取个人信息
		$.ajax({
			type:'post',
			url:commonsUrl+ 'api/gxsc/get/user/weixin/info' +versioninfos,
			data:{'ss':getCookie('openid')},
			success:function(data){
				if(data.code==1){
					console.log(data);					
					$('.head-portrait img').attr('src',data.result.headimgurl);
					$('.user-info p').html(data.result.nickname);
				}else{
  					layer.msg(data.msg);
  				}
			}
		})
		
		//获取购物车中的商品数量
  		$.ajax({
  			type: "post",
  			url: commonsUrl + '/api/gxsc/get/goods/car/commodity/info' + versioninfos, 
  			data: {
  				"ss": getCookie('openid')
  			},
  			success: function(data) {
  				if(data.code == 1) { //请求成功
  					console.log(data);
  					$('.shopping-cart span').html(data.result.info.length)
  					
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