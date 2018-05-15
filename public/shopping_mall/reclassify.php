<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="format-detection" content="telephone=no" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="stylesheet" href="css/common.css">
	<link rel="stylesheet" href="css/reclassify.css">
	<title>商超店铺</title>
</head>

<body>
	<div class="wrapper wrapper03" id="wrapper03">
		<div class="scroller">
			<ul class="clearfix">
				<script type="text/html" id="navList">
					<li>
						<a href="javascript:void(0)" id="{{goods_second_id}}">{{goods_second_name}}</a>
					</li>
				</script>
			</ul>
		</div>
	</div>

	<div class="commodity">
		<ul>
			<script type="text/html" id="commentList">

			<li onclick="location.href='newShop_details.php?ext_id={{ext_id}}'">
				<div>
					<img src="{{image}}" alt="">
				</div>
				<p>{{goods_name}}</p>
				<div>
					<span>￥{{price}}</span>
					<span>￥{{market_price}}</span>
				</div>
			</li>
			
		</script>
		</ul>
	</div>
	<p style="line-height: 616px; text-align: center; color: rgb(198, 191, 191);display:none" class="show">暂无商品,敬请期待!</p>	
</body>

</html>

<script src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/navbar/flexible.js"></script>
<script type="text/javascript" src="js/navbar/iscroll.js"></script>
<script type="text/javascript" src="js/navbar/navbarscroll.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/config.js"></script>
<script>
	console.log($_GET['store_id'])
	// 获取导航列表
	$.ajax({
		type: "post",
		dataType: "json",
		url: commonsUrl + 'api/gxsc/get/store/class/list' + versioninfos,
		data: {
			store_id: $_GET['store_id'],  
			ss: getCookie('openid')
		},
		success: (res) => {
			let store_class_id=res.result[0].store_class_id
			let data = res.result
			for (let val of data) {
				let temp = $("#navList").html()
				temp = temp.replace("{{goods_second_name}}", val.store_class_name).replace("{{goods_second_id}}", val.store_class_id)
				$(".clearfix").append(temp)
			}
			
			
			// 获取商品列表数据  
			$.ajax({
				type: "post",
				dataType: "json",
				url: commonsUrl + 'api/gxsc/get/store_class/goods/list' + versioninfos,
				data: {
					store_class_id: store_class_id,
					page: "1",
					ss: getCookie('openid')
				},
				success: (res) => {
				    console.log(res)
					let data = res.result
					if(data.length==0){
						$(".show").show()
					}else{
						$(".show").hide()
					}
					for (let val of data) {
						let temp = $("#commentList").html()
						temp = temp.replace("{{goods_name}}", val.goods_name).replace("{{image}}", val.image).replace("{{price}}", val.price).replace("{{market_price}}", val.market_price).replace("{{ext_id}}", val.ext_id)
						$(".commodity ul").append(temp)
					}
				}
			})
		}
	})
	$(function () {
		setTimeout(() => {
			$('.wrapper').navbarscroll();

			$(".clearfix a").click(function (e) {
				let id = $(this).attr("id")
				console.log(id)
				$(".commodity ul li").remove()
				$.ajax({
					type: "post",
					dataType: "json",
					url: commonsUrl + 'api/gxsc/get/store_class/goods/list' + versioninfos,
					data: {
						store_class_id: ，id,
						page: "1",
						ss: getCookie('openid')
					},
					success: (res) => {
						console.log(res)
						let data = res.result
						if(data.length==0){
						$(".show").show()
						}else{
							$(".show").hide()
						}
						for (let val of data) {
							let temp = $("#commentList").html()
							temp = temp.replace("{{goods_name}}", val.goods_name).replace("{{image}}", val.image).replace("{{price}}", val.price).replace("{{market_price}}", val.market_price).replace("{{ext_id}}", val.ext_id)
							$(".commodity ul").append(temp)
						}
					}
				})
			})
		}, 200);

   		



	})

</script>