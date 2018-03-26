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
		<!--<div class="moreBox">
			<div class="moreWid">
				<div class="classify addStyleMi">食品干货</div>
				<div class="classify">精选海鲜</div>
				<div class="classify">茶饮酒水</div>
				<div class="classify">休闲食品</div>
				<div class="classify">营养滋补</div>
				<div class="classify">精选海鲜</div>
				<div class="classify">茶饮酒水</div>
				<div class="classify">食品干货</div>
			</div>
		</div>-->
		<!-------商品列表------>
		<div class="shoplist_box">
			<!--<div class="shopListBox">
				<div class="shopImg"><img src="images/shop1.png" /></div>
				<div class="shopListNames">可玉可求 飘香翡翠手镯女款玉手镯 玉器玉石收手</div>
				<div class="shops">
					<span class="shopPrice">￥22000</span>
					<span class="fan">返利0.2</span>
				</div>
			</div>-->
		</div>

	</body>

</html>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script type="text/javascript">
	$(function() {
		var winH = $(window).height();
		var second_id = $_GET['second_id']; //获取二级分类id
		$.ajax({ //获取商品列表
			type: "get",
			dataType: 'json',
			url: commonsUrl + "api/gxsc/get/commodity/lists/" + second_id + versioninfos,
			data: {
				"second_id": second_id //请求参数  
			},
			success: function(data) {
				console.log(data)
				var html="";
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
							html += '<div class="shopListBox" goods_id=' + goods_id + ' >' +
								'<div class="shopImg"><img src=' + images + ' /></div>' +
								'<div class="shopListNames">' + goods_name + '</div>' +
								'<div class="shops"><span class="shopPrice">￥' + price + '</span>' +
								'<span class="fan"></span></div>' +
								'</div>';
						});
						$('.shoplist_box').html(html); //动态商品列表
						$(".shopListBox").click(function(){
							var goods_id=$(this).attr('goods_id');
							location.href = "shopDetails.php?goods_id=" + goods_id;
						})
					} else {
						$('.shoplist_box').html('<p>暂无商品,敬请期待!</p>');
						$('.shoplist_box p').css({
							'line-height': winH + 'px',
							'text-align': 'center',
							'color': '#c6bfbf'
						});
					}

				}

			}
		});
		
	})
</script>