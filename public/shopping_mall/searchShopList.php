<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>搜索列表</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="css/common.css" />
		<link rel="stylesheet" href="css/mui.min.css">
		<link rel="stylesheet" type="text/css" href="css/searchShopList.css" />
	</head>

	<body>
		<!-----搜索列表------>
		<div id="refreshContainer" class="mui-scroll-wrapper">
			<div class="mui-scroll">
				<div class="shopBox">
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
			</div>
		</div>
	</body>

</html>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/mui.min.js"></script>
<script type="text/javascript">
	var winH = $(window).height();
//	$('.wrapper').height(winH);
	var shopName = $_GET['shopName']; //获取商品名
	var shopNames = decodeURIComponent(shopName);
	pages= 1;
	showajax(pages);

	function showajax(pages) {
		layer.ready(function() {
			layer.load(2);
		})
		$.ajax({ //获取搜索的列表信息
			type: "post",
			dataType: 'json',
			url: commonsUrl + 'api/gxsc/get/search/commodity/list' + versioninfos,
			data: {
				"search": shopNames, //请求参数  商品名
				"page": pages, //
				"ss": getCookie('openid') //请求参数  openid
			},
			success: function(data) {
				console.log(data)
				layer.closeAll();
				if(data.code == 1) { //请求成功
					var con = data.result;
					if(con.length == 0 && pages == 1) {
						layer.closeAll();
						$('.shopBox').html('<p>抱歉，没有您搜到您要的商品哦！试试其他商品吧</p>');
						$('.shopBox p').css({
							'text-align': 'center',
							'color': '#c6bfbf',
							'line-height': winH + 'px'
						});
						
					} else {
						console.log(con);
						
						var html = '';
						$.each(con, function(k, v) {
							var goods_id = con[k].goods_id; //商品id
							console.log(goods_id);
							var ext_id = con[k].ext_id;
							var goods_name = con[k].goods_name; //商品名称
							var image1 = con[k].image; //商品图片
							var price = con[k].price; //商品单价
							var market_price = con[k].market_price; //市场价
							var spec_name = con[k].spec_name; //规格名称

							html += "<div class='shopList' goods_id=" + goods_id + " ext_id=" + ext_id + "><div class='listLeft'><div class='shopImgBox'><img src=" + image1 + "></div></div><div class='shopMessageBox'><div class='shopMessage'>" + goods_name + "</div><div class='shopPrice'>￥" + price + "</div></div></div>"
						});
						$('.shopBox').append(html); //动态显示搜索列表
						if(data.result.length > 0) {
							mui('#refreshContainer').pullRefresh().endPullupToRefresh(false);
						} else {
							layer.msg("已经到底了");
							mui('#refreshContainer').pullRefresh().endPullupToRefresh(true);
						}
					}

				} else {
					layer.msg(data.msg);
				}

			}
		});
	}
	mui.init({
		pullRefresh: {
			container: '#refreshContainer', //待刷新区域标识，querySelector能定位的css选择器均可，比如：id、.class等
			auto: true, // 可选,默认false.自动上拉加载一次
			height: 50,
			up: {
				callback: function() {
						pages++;
						showajax(pages);
						mui('#refreshContainer').pullRefresh().endPullupToRefresh();

					} //必选，刷新函数，根据具体业务来编写，比如通过ajax从服务器获取新数据；
			}

		}
	});

	mui('body').on('tap', '.shopList', function() {
		var ext_id = $(this).attr('ext_id');
		console.log(ext_id);
		mui.openWindow({
			url: "newShop_details.php?ext_id=" + ext_id
		})
	})
</script>