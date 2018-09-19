<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>商品列表</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/promotion.css" />
	</head>

	<body>
		<div id="active">
			<ul class="shop_box"></ul>
		</div>
	</body>

</html>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script type="text/javascript">
	$(function() {
		var banner_id = $_GET['imgId']; //获取banner_id
		var winW = $(window).width();
		var winH = $(window).height();
		$.ajax({
			type: "post",
			dataType: 'json',
			url: commonsUrl + 'api/gxsc/bannergoods' + versioninfos,
			data: {
				"banner_id": banner_id
			},
			success: function(data) {
				console.log(data)
				if(data.code == 1) { //请求成功
					var con = data.result;
					if(con.length != 0) {
						console.log(con);
						$.each(con, function(k, v) {
							var goods_id = con[k].goods_id; //商品id
							console.log(goods_id);
							var goods_name = con[k].goods_name; //商品名称
							var images = con[k].image; //商品图片
							console.log(images);
							var price = con[k].price; //商品价格
							var ext_id=con[k].ext_id;//扩展表id
							var spec_name = con[k].spec_name; //规格名称
							var markPrice = con[k].market_price;//原价
							var goods_gift = con[k].goods_gift;//商品类别
							var use_score = con[k].use_score;//积分数
							var show = isShowImg(con[k].goods_gift);
							var shows = isShow(con[k].goods_gift);
							if(price == null || price == undefined) {
								price1 = '0';
							}
							if(markPrice == null || markPrice == undefined) {
								markPrice = '0';
							}
							var html = "";
							html += '<li class="shopBox" onclick="goDetails(' + ext_id + ')" ext_id='+ext_id+' goods_id='+goods_id+'>' +
								'<div class="shopImg"><img src=' + images + ' /></div>' +
								'<div class="shopListName">' + goods_name + '</div>' +
								'<div class="shopPrice">' +
								'<span class="price">￥' + price + '</span><span class="useScore" style="display:' + show + '">需' + use_score + '积分</span>' +
								'<span class="marketPrice" style="display:' + shows + ';">￥'+markPrice+'</span>'+
								'</div>' +
								'</li>'
							$(".shop_box").append(html);
						});

					} else {
						$('.shop_box').html('<p>暂无商品,敬请期待!</p>');
						$('.shop_box p').css({
							'line-height': winH - 51 + 'px',
							'text-align': 'center',
							'color': '#c6bfbf'
						});
					}

				}
			}

		});

		
	})
</script>
<script type="text/javascript">
	function isShowImg(goods_gift) {
		if(goods_gift == 1) {
			return 'none';
		} else {
			return 'block'
		}
	};

	function isShow(goods_gift) {
		if(goods_gift == 1) {
			return 'block';
		} else {
			return 'none'
		}
	};
	function goDetails(ext_id) {
			//		console.log(goods_id);
			location.href = "newShop_details.php?ext_id=" + ext_id;
		};
</script>