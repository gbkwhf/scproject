<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>商城</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link href="css/common.css" type="text/css" rel="stylesheet" />
		<style>
			body {
				background: #f3f5f7;
			}
			
			
			
			input[type="button"] {
				-webkit-appearance: none;
			}
			
			.topBox {
				width: 100%;
				height: 55px;
				background-color: #f3f5f7;
				position: fixed;
				z-index: 300;
			}
			
			.serchBox {
				width: 78%;
				height: 37px;
				border: solid 1px #bfbfbf;
				border-radius: 5px;
				margin-left: 12px;
				margin-top: 9px;
				float: left;
			}
			
			.imgBox {
				float: left;
			}
			
			.searchImg {
				width: 15px;
				height: 15px;
				margin-left: 12px;
				margin-top: 11px;
			}
			
			.inBox {
				float: left;
				width: 85%;
				margin-left: 5px;
			}
			
			.insearch {
				height: 37px;
				width: 90%;
				border: none;
				outline: none;
				line-height: 37px;
				padding-left: 5px;
				background-color: #f3f5f7;
			}
			
			.searchSubmit {
				width: 14.4%;
				height: 37px;
				line-height: 37px;
				text-align: center;
				color: #707070;
				background-color: #dcdcdc;
				border-radius: 6px;
				margin-right: 7px;
				float: right;
				margin-top: 9px;
			}
			
			.container {
				clear: both;
				padding: 60px 12px;
			}
			
			.container>ul {
				overflow: hidden;
				width: 100%;
			}
			
			.container>ul>li {
				float: left;
				width: 48%;
				text-align: center;
				background: #fff;
				padding: 33px 0;
				box-sizing: border-box;
				margin-bottom: 6px;
				border-radius: 5px;
				border: 1px solid #e6e6e6;
				box-shadow: 0 3px 5px #e3e5e7;
			}
			
			.container>ul>li>img {
				width: 42px;
				height: 44px;
				vertical-align: top;
			}
			
			.container>ul>li>em {
				font-style: normal;
				font-size: 15px;
				color: #4d4d4d;
				display: block;
				width: 100%;
				text-align: center;
				margin-top: 15px;
			}
		</style>
	</head>

	<body>
		<div class="topBox">
			<div class="serchBox">
				<div class="imgBox"><img src="images/serach.png" class="searchImg" /></div>
				<div class="inBox"><input type="text" placeholder="请输入需要查找的商品" class="insearch" /></div>
			</div>
			<div class="searchSubmit">搜索</div>
		</div>
		<div class="container">
			<ul>
				<li style="margin-right:9px;" onclick="location.href='healthy_mall_list.php?first_id=1'">
					<img src="images/tutechan.png" />
					<em>食品土特产</em>
				</li>
				<li onclick="location.href='healthy_mall_list.php?first_id=2'">
					<img src="images/jiaju.png" />
					<em>家居家装</em>
				</li>
				<li style="margin-right:9px;" onclick="location.href='healthy_mall_list.php?first_id=3'">
					<img src="images/health_shoping.png" />
					<em>健康商城</em>
				</li>
				<li onclick="location.href='lyg_index.php?first_id=5'">
					<img src="images/linyi_shop.png" />
					<em>聆医馆</em>
				</li>
				<li style="margin-right:9px;" onclick="waitting()">
					<img src="images/life.png" />
					<em>生活缴费</em>
				</li>
				<li onclick="waitting()">
					<img src="images/Customer.png" />
					<em>售后交流</em>
				</li>
			</ul>

		</div>
		<!--购物车-->
		<div class="shopping-cart" onclick="location.href='shopCart.php'">
			<img src="images/shopping-cart.png" />
			<span></span>
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/layer/layer.js"></script>
		<script src="js/common.js"></script>
		<script src="js/config.js"></script>
		<script type="text/javascript">
			function waitting() {
				layer.msg('暂未开通，敬请期待哦！')
			}

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
						for(var i = 0; i < data.result.info.length; i++) {
							numberShop += parseInt(data.result.info[i].number);
						}
						$('.shopping-cart span').html(numberShop);

					} else if(data.code == 1011) {
						layer.msg('身份已失效，请重新绑定');
						setTimeout(function() {
							location.href = 'register.php';
						}, 1000);
					} else {
						layer.msg(data.msg);
					}
				}
			});
			//-----------搜索----------
			$(function() {
				$(".searchSubmit").click(function() {
					var shopName = $(".insearch").val();
					console.log(shopName);
					if(shopName == "" || shopName == undefined) {
						layer.msg("商品名称不能为空");
					} else {
						location.href = "searchShopList.php?vid=1&shopName="+shopName;
					}

				})
			})
		</script>
	</body>

</html>