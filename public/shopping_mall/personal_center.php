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
<script>
    //解决IOS微信webview后退不执行JS的问题
    window.onpageshow = function(event) {
        if (event.persisted) {
            window.location.reload();
        }
    };
</script>
<body>

	<div class="wrapper">
		<header>
			<div class="user-info" onclick="location.href='personal_data.php'">
				<div class="head-portrait">
					<img src="images/head-portrait.png" width="55"/>
				</div>
				<p></p>
			</div>
			<div class="account-info">
				<div class="balance">
					<em>¥</em>
					<p>0</p>
					<span>余额（可提现）</span>
				</div>
				<div class="cashback">
					<em>¥</em>
					<p>0</p>
					<span>昨日返现</span>
				</div>
			</div>
		</header>
		<ul class="menu-list">
			<li onclick="location.href='my_orders.php'">
				我的订单
				<img src="images/right-arrow.png" width="8"/>
			</li>
			<li onclick="location.href='invitation.php'">
				我的邀请
				<img src="images/right-arrow.png" width="8"/>
			</li>
			<li onclick="location.href='withdrawals_record.php?identity=0'">
				提现记录
				<img src="images/right-arrow.png" width="8"/>
			</li>
			<li class="substitute" onclick="location.href='destoon_finance_cash.php?identity=1'">
				替用户提现
				<img src="images/right-arrow.png" width="8"/>
			</li>
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
	
		if(getCookie('is_member')==1){
			$('.substitute').show();
		}
	
		//获取微信个人信息
//		$.ajax({
//			type:'post',
//			url:commonsUrl+ 'api/gxsc/get/user/weixin/info' +versioninfos,
//			data:{'ss':getCookie('openid')},
//			success:function(data){
//				if(data.code==1){
//					console.log(data);					
//					$('.head-portrait img').attr('src',data.result.headimgurl);
//					$('.user-info p').html(data.result.nickname);
//				}else{
//					layer.msg(data.msg);
//				}
//			}
//		})
		
		
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
  					var numberShop = 0;
  					for(var i=0;i<data.result.info.length;i++){
						numberShop += parseInt(data.result.info[i].number) ;
  					}
					$('.shopping-cart span').html(numberShop);
  					
  				}else if(data.code==1011){
  					layer.msg('身份已失效，请重新绑定');
  					setTimeout(function(){location.href='register.php';},1000);
  				}else{
  					layer.msg(data.msg);  					
  				}
  			}
  		});
  		
  		//获取用户基本信息
  		$.ajax({
  			type:"post",
  			url:commonsUrl+"api/gxsc/user/profile"+versioninfos,
  			data:{'ss':getCookie('openid')},
  			success:function(data){
  				if(data.code==1){
  					console.log(data);
  					if(data.result.thumbnail_image_url!=""){
  						$('.head-portrait img').attr('src',data.result.thumbnail_image_url);  						
  					}
					$('.user-info p').html(data.result.name);
  					$('.balance p').html(data.result.balance);
  					$('.cashback p').html(data.result.yesterday_return_money);
  				}else if(data.code==1011){
  					layer.msg('身份已失效，请重新绑定');
  					setTimeout(function(){location.href='register.php';},1000);
  				}else{
  					layer.msg(data.msg)
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