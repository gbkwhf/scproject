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
		<link rel="stylesheet" type="text/css" href="css/outsider.css" />
		<link rel="stylesheet" type="text/css" href="css/swiper.min.css" />
		<link rel="stylesheet" type="text/css" href="css/commfooter.css"/>
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
				<!--<div class="swiper-slide "> <img src="images/banners.png" /></div>
				<div class="swiper-slide "> <img src="images/banners.png" /></div>
				<div class="swiper-slide "> <img src="images/banners.png" /></div>
				<div class="swiper-slide "> <img src="images/banners.png" /></div>-->
			</div>
			<div class="swiper-pagination"></div>
		</div>
		<!-------------商品分类----->
		<div class="container">
			<ul class="shop_container">
				<!--<li>
					<img src="images/jinpin_03.png" />
					<em>精品特产</em>
				</li>
				<li>
					<img src="images/xiuxian_03.png" />
					<em>休闲食品</em>
				</li>
				<li>
					<img src="images/baihuo_03.png" />
					<em>日用百货</em>
				</li>
				<li>
					<img src="images/shengxian.png" />
					<em>双创生鲜</em>
				</li>
				<li>
					<img src="images/chashui_03.png" />
					<em>茶叶酒水</em>
				</li>
				<li>
					<img src="images/foods_03.png" />
					<em>五谷杂粮</em>
				</li>-->
			</ul>

		</div>
		<!-------------福利区------->
		<!--<div class="welfare"><img src="images/welfare.png" /></div>-->
		<!-----------新品上线------->
		<div class="newBox">
			<div class="newShopBox">新品上线</div>
			<div class="moreShop"><img src="images/back1.png" /></div>
		</div>

		<!----------商品信息----->
		<div style="margin-bottom: 70px;" class="shop_Box">
			<!--<div class="shopBox">
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
			</div>-->

		</div>
		<!---------底部----->
		<!--<div id="commId"></div>-->
		<!---------底部----->
		<div class="shopBottom">
			<div class="memberIndex">
				<dl>
					<dt><img src="images/in2.jpg"/></dt>
					<dd style="color: #e63636;">首页</dd>
				</dl>
			</div>
			<div class="shopCar" onclick="location.href='newShop_cart.php'">
				<dl>
					<dt>
						<img src="images/che1.jpg"/>
						<span>0</span>
					</dt>
					<dd style="color: #333333;">购物车</dd>
				</dl>
			</div>
			<div class="personal" onclick="location.href='personal_center.php'">
				<dl>
					<dt><img src="images/my.png"/></dt>
					<dd style="color: #333333;">我的</dd>
				</dl>
			</div>
		</div>

	</body>

</html>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/swiper-3.4.0.min.js"></script>
<!--<script type="text/javascript" src="js/memberPages.js"></script>-->
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script type="text/javascript">
	$(function() {

		var first_id = $_GET['first_id'];
		$.ajax({ //获取商品二级分类
			type: "get",
			dataType: 'json',
			url: commonsUrl + "/api/gxsc/get/commodity/secondary/classification/" + first_id + versioninfos,
			data: {
				"first_id": first_id //请求参数  openid
			},
			success: function(data) {
				console.log(data)
				if(data.code == 1) { //请求成功
					var con = data.result;
					if(con.length != 0) {
						console.log(con);
						var html = '';
						$.each(con, function(k, v) {
							var second_id = con[k].second_id; //二级分类id
							console.log(second_id);
							var imgsrc = 'images/jin' + k + '.png'
							var second_name = con[k].second_name; //分类名称
							html += "<li second_id=" + second_id + "  class='secClick' ><img src=" + imgsrc + " /><em>" + second_name + "</em></li>"
						});
						$('.shop_container').append(html); //动态显示分类名称
						$(".secClick").click(function() {
							var second_id = $(this).attr("second_id");
							location.href = "outsider_mall_list.php?second_id=" + second_id;
						})
					} else {
						layer.msg(data.msg);
					}

				}

			}
		});

		var tarr = [];
		var numberShop = 0;
		//获取购物车的数量
		$.ajax({
			type: "post",
			url: commonsUrl + '/api/gxsc/get/goods/car/commodity/info' + versioninfos,
			data: {
				"ss": getCookie('openid')
			},
			success: function(data) {
				if(data.code == 1) { //请求成功
					console.log(data);
					var arr = data.result.info;
					$.each(arr, function(k, v) {
						$.each(v.goods_list, function(key, value) {
							//console.log(value.number);
							numberShop += parseInt(value.number)
						})
					})
					console.log(numberShop + 'iiiiiii')
					$('.shopCar span').html(numberShop);

				} else {
					layer.msg(data.msg);
				}
			}
		});
		//获取新发布的商品
		$.ajax({
			type: "post",
			dataType: 'json',
			url: commonsUrl + 'api/gxsc/get/new/commodity/class' + versioninfos,
			data: {
				"flag":0,//非返利区
				"page": 1 //请求参数  openid
			},
			success: function(data) {
				console.log(data)
				if(data.code == 1) { //请求成功
					var con = data.result;
					if(con.length != 0) {
						console.log(con);
						var html = '';
						$.each(con, function(k, v) {
							var goods_id = con[k].goods_id; //商品id
							var goods_name = con[k].goods_name; //商品名称
							var images = con[k].image; //商品图片
							var price = con[k].price; //商品价格
							var num = con[k].num; //库存
							var time = con[k].time; //商品上架时间
							var sales = con[k].sales; //商品销量
							console.log(goods_id);
							html += '<div class="shopBox" goods_id=' + goods_id + ' >' +
								'<div class="shopImg"><img src=' + images + ' /></div>' +
								'<div class="shopName">' + goods_name + '</div>' +
								'<div class="shopMessage">' +
								'<div class="shops">' +
								'<span class="shopPrice">￥' + price + '</span>' +
								'<span class="fan"></span>' +
								'</div>' +
								'<div class="toBuy">立即抢购</div>' +
								'</div>' +
								'</div>'
						});
						$('.shop_Box').append(html); //动态显示商品列表
						$(".shopBox").click(function(){
							var goods_id=$(this).attr('goods_id');
							location.href = "shopDetails.php?goods_id=" + goods_id;
						})
					} else {
						$('.newBox').hide();
					}

				}

			}

		});
		//banner图轮播
		$.ajax({
			type: "post",
			dataType: 'json',
			url: commonsUrl + 'api/gxsc/get/banner/list' + versioninfos,
			success: function(data) {
				console.log(data)
				if(data.code == 1) { //请求成功
					var con = data.result; //
					var sort = con.sort; //排序
					//---------------循环图片（轮播图）-----
					$.each(con, function(k, v) {
						var src = con[k].img_url; //图片地址
						var imgId = con[k].id; //图片id
						var sort = con[k].sort; //排序
						var t = "<div class='swiper-slide'> <img src=" + src + " /></div>";
						$('.swiper-wrapper').append(t)
					});
				}
				//swiper插件实现轮播图
				var mySwiper = new Swiper('.swiper-container', {
					autoplay: 1500, //可选选项，自动滑动
					loop: true,
					pagination: '.swiper-pagination',
				});

			}
		});
		//-----------搜索------------
		$(".searchSub").click(function() {
				var first_id = $_GET['first_id'];
				console.log(first_id);
				if(first_id == 4) { //1返利区，0非返利区
					var shopName = $(".insearch").val();
					console.log(shopName);
					if(shopName == "" || shopName == undefined) {
						layer.msg("商品名称不能为空");
					} else {
						location.href = "searchShopList.php?vid=0&shopName=" + shopName;
					}
				} else {
					var shopName = $(".insearch").val();
					console.log(shopName);
					if(shopName == "" || shopName == undefined) {
						layer.msg("商品名称不能为空");
					} else {
						location.href = "searchShopList.php?vid=1&shopName=" + shopName;
					}
				}
			})
		//公共的底部
		$('#commId').load('commfooter.php');

		setTimeout(function() { //#e63636
			$(".memberIndex dd").css('color', '#e63636');
			$(".memberIndex dt img").attr("src", "images/in2.jpg")
			$(".shopCar dt img").attr("src", "images/che1.jpg");
			$(".personal dt img").attr("src", "images/my.png");
			$(".personal dd").css('color', '#333333');
			$(".shopCar dd").css('color', '#333333');
			$('.shopCar').click(function() {
				location.href = "newShop_cart.php";
			});
			$('.personal').click(function() {
			location.href = "personal_center.php";
			})
		}, 100)

	})
</script>