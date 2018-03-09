<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>商城</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="css/common.css">
		<link rel="stylesheet" type="text/css" href="css/memberPages.css" />
		<link rel="stylesheet" type="text/css" href="css/swiper.min.css" />
	</head>

	<body>
		<!-----------头部开始（搜索框）-->
		<div class="searchTop">
			<div class="searchBox">
				<div class="seachImg"><img src="images/searchImg.png" /></div>
				<div class="insearchBox"><input type="text" placeholder="请输入需要查找的商品" class="insearch" /></div>
			</div>
			<div class="searchSub">搜索</div>
		</div>
		<!------------轮播图------------->
		<div class="swiper-container" id="001">
			<div class="swiper-wrapper">
				<div class="swiper-slide "> <img src="images/banners.png" /></div>
				<div class="swiper-slide "> <img src="images/banners.png" /></div>
				<div class="swiper-slide "> <img src="images/banners.png" /></div>
				<div class="swiper-slide "> <img src="images/banners.png" /></div>
			</div>
			<div class="swiper-pagination"></div>
		</div>
		<!-------------商品分类----->
		<div class="container">
			<ul>
				<li>
					<img src="images/shipin_03.png" />
					<em>食品土特产</em>
				</li>
				<li>
					<img src="images/jiaju_03.png" />
					<em>家居家装</em>
				</li>
				<li>
					<img src="images/health_03.png" />
					<em>健康商城</em>
				</li>
				<li>
					<img src="images/linyi.png" />
					<em>聆医馆</em>
				</li>
				<li>
					<img src="images/yijing_03.png" />
					<em>非遗景泰蓝</em>
				</li>
				<li>
					<img src="images/shouhou_03.png" />
					<em>售后交流</em>
				</li>
			</ul>

		</div>
		<!-------------福利区------->
		<div class="welfare"><img src="images/welfare.png" /></div>
		<!-----------新品上线------->
		<div class="newBox">
			<div class="newShopBox">新品上线</div>
			<div class="moreShop"><img src="images/back1.png" /></div>
		</div>

		<!----------商品信息----->
		<div style="margin-bottom: 70px;">
			<div class="shopBox">
				<div class="shopImg"><img src="images/shop_img.png" /></div>
				<div class="shopName">可玉可求 飘香翡翠手镯 女款玉手镯 玉器玉石收手镯子， 送礼自带均可</div>
				<div class="shopMessage">
					<div class="shops">
						<span class="shopPrice">￥22000</span>
						<span class="fan">返利0.2</span>
					</div>
					<div class="toBuy">立即抢购</div>
				</div>
			</div>
			<div class="shopBox">
				<div class="shopImg"><img src="images/shop_img.png" /></div>
				<div class="shopName">可玉可求 飘香翡翠手镯 女款玉手镯 玉器玉石收手镯子， 送礼自带均可</div>
				<div class="shopMessage">
					<div class="shops">
						<span class="shopPrice">￥22000</span>
						<span class="fan">返利0.2</span>
					</div>
					<div class="toBuy">立即抢购</div>
				</div>
			</div>
			
		</div>

		<!---------底部----->
		<div id="commId"></div>
	</body>

</html>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/swiper-3.4.0.min.js"></script>
<script type="text/javascript" src="js/memberPages.js"></script>
<script type="text/javascript">
	$(function(){
		//公共的底部
		$('#commId').load('commfooter.php');
		
			setTimeout(function(){  //#e63636
				$(".memberIndex dd").css('color','#e63636');
				$(".memberIndex dt img").attr("src","images/in2.jpg")
				$(".shopCar dt img").attr("src","images/che1.jpg");
				$(".shopCar dd").css('color','#333333');
				$('.shopCar').click(function(){	
					location.href="newShop_cart.php";
				})
			},100)
	})
</script>