<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>商城列表</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="css/common.css">
		<link rel="stylesheet" type="text/css" href="css/member_mall_list.css" />
	</head>
	<style type="text/css">
		::-webkit-scrollbar {
			display: none;
		}
	</style>

	<body>
		<!----------商品分类-->
		<div class="moreBox">
			<div class="moreWid">
				<!--<div class="classify addStyleMi">食品干货</div>
				<div class="classify">精选海鲜</div>
				<div class="classify">茶饮酒水</div>
				<div class="classify">休闲食品</div>
				<div class="classify">营养滋补</div>
				<div class="classify">精选海鲜</div>
				<div class="classify">茶饮酒水</div>
				<div class="classify">食品干货</div>-->
			</div>
		</div>
		<!-------商品列表------>
		<div style="margin-top: 52px;" class="shopBox">
			<!--<div class="shopListBox">
				<div class="shopImg"><img src="images/shop1.png" /></div>
				<div class="shopListNames">可玉可求 飘香翡翠手镯女款玉手镯 玉器玉石收手</div>
				<div class="shops">
					<span class="shopPrice">￥22000</span>
					<span class="fan">返利0.2</span>
				</div>
			</div>
			<div class="shopListBox">
				<div class="shopImg"><img src="images/shop1.png" /></div>
				<div class="shopListNames">可玉可求 飘香翡翠手镯女款玉手镯 玉器玉石收手</div>
				<div class="shops">
					<span class="shopPrice">￥22000</span>
					<span class="fan">返利0.2</span>
				</div>
			</div>-->
		</div>
		<!--购物车-->
		<div class="shopping-cart" onclick="location.href='newShop_cart.php'">
			<img src="images/shopping-cart.png" />
			<span></span>
		</div>
	</body>

</html>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script type="text/javascript">
</script>
<script type="text/javascript">
	$(function() {
		var winW = $(window).width();
		var winH = $(window).height();

		var first_id = $_GET['first_id'];
		$.ajax({ //获取商品二级分类
			type: "get",
			dataType: 'json',
			url: commonsUrl + "api/gxsc/get/commodity/secondary/classification/" + first_id + versioninfos,
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
							var second_name = con[k].second_name; //分类名称
							html += "<div class='classify' second_id=" + second_id + ">" + second_name + "</div>"
						});
						$('.moreWid').append(html); //动态显示分类名称
						$(".classify").eq(0).addClass("addStyleMi").siblings().removeClass("addStyleMi");
						shopList(con[0].second_id);
						$('.classify').click(function() {
							$(this).addClass('addStyleMi').siblings().removeClass('addStyleMi');
							shopList($(this).attr("second_id"));
							//							$('.moreWid').append(html);
						})
					} else {
						layer.msg(data.msg);
					}

				}

			}
		});
	});
	//专区切换
	function shopList(second_id) {
		var winH = $(window).height();
		var html = '';
		var con = "";
		$.ajax({ //获取商品列表
			type: "get",
			dataType: 'json',
			url: commonsUrl + "api/gxsc/get/commodity/lists/" + second_id + versioninfos,
			data: {
				"second_id": second_id //请求参数  openid
			},
			success: function(data) {
				console.log(data)
				if(data.code == 1) { //请求成功
					var content = data.result;
					con = content
					if(con.length != 0) {
						console.log(con)
						$.each(con, function(k, v) {
							var goods_id = con[k].goods_id; //商品id
							console.log(goods_id);
							var goods_name = con[k].goods_name; //商品名称
							var images = con[k].image; //商品图片
							var price = con[k].price; //商品价格
							html += '<div class="shopListBox" goods_id=' + goods_id + ' onclick="goDetail(' + goods_id + ')">' +
								'<div class="shopImg"><img src=' + images + ' /></div>' +
								'<div class="shopListNames">' + goods_name + '</div>' +
								'<div class="shops"><span class="shopPrice">￥' + price + '</span>' +
								'<span class="fan"></span></div>' +
								'</div>';
						});
						$('.shopBox').html(html); //动态商品列表
					} else {
						$('.shopBox').html('<p>暂无商品,敬请期待!</p>');
						$('.shopBox p').css({
							'line-height': winH - 51 + 'px',
							'text-align': 'center',
							'color': '#c6bfbf'
						});
					}

				}

			}
		});
	};
	//获取购物车中的商品数量
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
				$('.shopping-cart span').html(numberShop);

			} else {
				layer.msg(data.msg);
			}
		}
	});

	function goDetail(goods_id) {
		//		console.log(goods_id);
		location.href = "shopDetails.php?goods_id=" + goods_id;
	}
</script>