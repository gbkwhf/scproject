<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>店铺详情</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/shopDetails.css" />
		<link rel="stylesheet" type="text/css" href="css/swiper-3.4.0.min.css">
	</head>
	<body>
		<!-----------顶部固定----------->
		<div class="shopTitle">
			<a class="shop dd" onclick="click_scroll1();">商品</a>
			<a class="details" onclick="click_scroll2();">详情</a>
			<a class="evaluate" onclick="click_scroll3();">评价</a>
		</div>

		<!-----------轮播图------------->
		<div class="swiper-container aa" id="001">
			<div class="swiper-wrapper"></div>
			<div class="swiper-pagination"></div>
		</div>
		<div class="shopIntroduce"></div>
		<div class="shopPrice">
			<div class="price"></div>
			<div class="bor"></div>
			<div class="super">超级返?</div>
		</div>
		<div class="kong"></div>
		<div class="shopInformation">
			<div class="shopIntoduce">
				<a href="#002">商品介绍</a>
			</div>
			<div class="apprarise">
				<a href="#003">评论</a>
			</div>
		</div>
		<!-----------底部固定------------->
		<div class="shopBuy">
			<div class="addCar">加入购物车</div>
			<!--<div class="addXian"></div>
			<div class="promBuy">立即购买</div>-->
		</div>
		<!------------商品详情------------>
		<div class="detailTitle aa" id="002">商品详情</div>
		<div class="shopImg">
			<!--<img src="images/shopImg.png" />
			<img src="images/shopImg2.png" />-->
		</div>
		<!------------商品评价------------>
		<div class="shopApprarise aa" id="003">商品评价</div>
		<div class="apprariseBox">
			<div class="apprariseNav">
				<div class="userMessage">
					<div class="userImg">
						<img src="images/userImg1.png" />
					</div>
					<div class="userName">
						<p class="user-name">外屏总是碎</p>
						<img src="images/star.png" class="star" />
					</div>
					<div class="apprariseDate">2017-02-22</div>
				</div>
				<div class="evaluationContent">不错！很好吃，一直在她家买，下次多买点全 家都非常喜欢吃</div>
			</div>
			<div class="apprariseNav">
				<div class="userMessage">
					<div class="userImg">
						<img src="images/userImg2.png" />
					</div>
					<div class="userName">
						<p class="user-name">袁腾飞</p>
						<img src="images/star.png" class="star" />
					</div>
					<div class="apprariseDate">2017-02-22</div>
				</div>
				<div class="evaluationContent">不错！很好吃，一直在她家买.</div>
			</div>
			<div class="apprariseNav">
				<div class="userMessage">
					<div class="userImg">
						<img src="images/userImg1.png" />
					</div>
					<div class="userName">
						<p class="user-name">外屏总是碎</p>
						<img src="images/star.png" class="star" />
					</div>
					<div class="apprariseDate">2017-02-22</div>
				</div>
				<div class="evaluationContent">不错！很好吃，一直在她家买，下次多买点全 家都非常喜欢吃</div>
			</div>
			<div class="apprariseNav">
				<div class="userMessage">
					<div class="userImg">
						<img src="images/userImg2.png" />
					</div>
					<div class="userName">
						<p class="user-name">多啦爱梦</p>
						<img src="images/star.png" class="star"/>
					</div>
					<div class="apprariseDate">2017-02-22</div>
				</div>
				<div class="evaluationContent">不错！很好吃，一直在她家买.</div>
			</div>
		</div>
	</body>
	<script type="text/javascript" src="js/common.js"></script>
	<script type="text/javascript" src="js/config.js"></script>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/swiper-3.4.0.min.js"></script>
	<script type="text/javascript" src="js/shopDetails.js"></script>
	<script type="text/javascript" src="js/layer/layer.js"></script>
	<script type="text/javascript">
		$(function() {
			var goods_id = $_GET['goods_id'];
			console.log(goods_id + '+++++');
			$.ajax({
				type: "get",
				dataType: 'json',
				url: commonsUrl + 'api/gxsc/get/commodity/info/' + goods_id + versioninfos,
				data: {
					"goods_id": goods_id
				},
				success: function(data) {
					console.log(data)
					if(data.code == 1) { //请求成功
						var con = data.result; //
						var content = con.content; //商品详情
						var goods_id = con.goods_id; //商品id
						console.log(goods_id);
						var goods_name = con.goods_name; //商品名称
						var img_url = con.img_url; //商品图片
						var num = con.num; //商品数量
						var price = con.price; //商品单价
						var sales = con.sales; //商品销量
						//------------进行赋值---------------
						$('.swiper-pagination-total').html(img_url.length);//轮播图计数
						$('.shopIntroduce').html(goods_name);//商品名
						$('.price').html('￥' + price);//商品单价
						$('.shopImg').html(content);//商品内容
						console.log(img_url);
						//---------------循环图片（轮播图）-----
						$.each(img_url, function(k, v) {
							var src = img_url[k].image;
							var imgId = img_url[k].img_id;
							var t = "<div class='swiper-slide'><image src=" + src + "/></div>";
							$('.swiper-wrapper').append(t)
						});
					}
				}
			});
			//------------创建购物车------------
			$(".addCar").click(function() {
				//console.log(getCookie("openid") + '这是cookie');
				var goods_id = $_GET['goods_id'];
				console.log(goods_id + '+++++');
				$.ajax({
					type: "post",
					dataType: 'json',
					url: commonsUrl + '/api/gxsc/add/goods/car/commodity' + versioninfos,
					data: {
						"goods_id": goods_id,//商品id
						"number": 1,//商品数量
						"ss": 'adlkfjadiaodsmmmmm'//openid
					},
					success: function(data) {
						//				console.log(data)
						if(data.code == 1) {//请求成功
							setTimeout(function() {
								layer.msg("加入购物车成功了，请到购物车查看");
							}, 1000)
							location.href = "shopCart.php";
						}
					}
				});
			})
		});
	</script>
	<script type="text/javascript">
		function click_scroll1() {
			var scroll_offset = $("#001").offset(); //得到pos这个div层的offset，包含两个值，top和left 
			$("body,html").animate({
				scrollTop: scroll_offset.top //让body的scrollTop等于pos的top，就实现了滚动 
			}, 0);
		}

		function click_scroll2() {
			var scroll_offset = $("#002").offset(); //得到pos这个div层的offset，包含两个值，top和left 
			$("body,html").animate({
				scrollTop: scroll_offset.top //让body的scrollTop等于pos的top，就实现了滚动 
			}, 0);
		}

		function click_scroll3() {
			var scroll_offset = $("#003").offset(); //得到pos这个div层的offset，包含两个值，top和left 
			$("body,html").animate({
				scrollTop: scroll_offset.top //让body的scrollTop等于pos的top，就实现了滚动 
			}, 0);
		}
	</script>

</html>