<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>搜索列表</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/searchShopList.css" />
	</head>

	<body>
		<!-----搜索列表------>
		<div class="wrapper">
			<!--<div class="shopList">
				<div class="listLeft">
					<div class="shopImgBox">
						<img src="images/rice2.png"/>
					</div>
				</div>
				<div class="shopMessageBox">
					<div class="shopMessage">原装2017新米生态种植东 北特产5kg真空礼盒包装</div>
					<div class="shopPrice">￥18.00</div>
				</div>
			</div>-->

		</div>
	</body>

</html>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script type="text/javascript">
	$(function() {
		var winH = $(window).height();
		$('.wrapper').height(winH);
		var vid = $_GET['vid']; //获取vid
		var shopName = $_GET['shopName']; //获取商品名
		var shopNames=decodeURIComponent(shopName);
		if(vid == 0) {
			$.ajax({ //获取搜索的列表信息
				type: "post",
				dataType: 'json',
				url: commonsUrl + 'api/gxsc/searchgoods' + versioninfos,
				data: {
					"name": shopNames, //请求参数  商品名
					"vip": 0 //非会员区
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
								console.log(goods_id);
								var goods_name = con[k].goods_name; //商品名称
								var image1 = con[k].image; //商品图片
								var num = con[k].num; //商品数量
								var price = con[k].price; //商品单价
								var sales = con[k].sales; //商品销量

								html += "<div class='shopList' goods_id="+goods_id+" onclick='goDetail("+goods_id+")'><div class='listLeft'><div class='shopImgBox'><img src="+image1+"></div></div><div class='shopMessageBox'><div class='shopMessage'>"+goods_name+"</div><div class='shopPrice'>￥"+price+"</div></div></div>"
							});
							$('.wrapper').append(html); //动态显示搜索列表
						} else {
							$('.wrapper').html('<p>抱歉，非返利区没有您要的商品哦！到返利区试试吧</p>');
							$('.wrapper p').css({
								'text-align': 'center',
								'color': '#c6bfbf',
								'line-height': winH + 'px'
							});
						}
					}

				}
			});
		}else{
			$.ajax({ //获取搜索的列表信息
				type: "post",
				dataType: 'json',
				url: commonsUrl + 'api/gxsc/searchgoods' + versioninfos,
				data: {
					"name": shopNames, //请求参数  商品名
					"vip": 1 //会员区
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
								console.log(goods_id);
								var goods_name = con[k].goods_name; //商品名称
								var image1 = con[k].image; //商品图片
								var num = con[k].num; //商品数量
								var price = con[k].price; //商品单价
								var sales = con[k].sales; //商品销量

								html += "<div class='shopList' goods_id=" + goods_id + " onclick='goDetail("+goods_id+")' ><div class='listLeft'><div class='shopImgBox'><img src="+image1+" ></div></div><div class='shopMessageBox'><div class='shopMessage'>" + goods_name + "</div><div class='shopPrice'>￥" + price + "</div></div></div>"
							});
							$('.wrapper').append(html); //动态搜索列表
						} else {
							$('.wrapper').html('<p>抱歉，返利区没有您要的商品哦！试试其他商品吧</p>');
							$('.wrapper p').css({
								'text-align': 'center',
								'color': '#c6bfbf',
								'line-height': winH + 'px'
							});
						}
					}

				}
			});
		};
	})
	function goDetail(goods_id){
//		console.log(goods_id);
		location.href="shopDetails.php?goods_id="+goods_id;
	}	
</script>