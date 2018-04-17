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
	<title></title>
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

			<li onclick="location.href='newShop_details.php?id={{ext_id}}'">
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
</body>

</html>

<script src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/navbar/flexible.js"></script>
<script type="text/javascript" src="js/navbar/iscroll.js"></script>
<script type="text/javascript" src="js/navbar/navbarscroll.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/config.js"></script>
<script>
	let urlId=location.search.substring(4)
	alert(urlId)
	// 获取导航列表
	$.ajax({
		type: "post",
		dataType: "json",
		url: commonsUrl + 'api/gxsc/get/goods/second/list' + versioninfos,
		data: {
			goods_first_id: "28",
			ss: getCookie('openid')
		},
		success: (res) => {
			let data = res.result
			for (let val of data) {
				let temp = $("#navList").html()
				temp = temp.replace("{{goods_second_name}}", val.goods_second_name).replace("{{goods_second_id}}", val.goods_second_id)
				$(".clearfix").append(temp)
			}
		}
	})
	$(function () {
		setTimeout(() => {
			$('.wrapper').navbarscroll();

			$(".clearfix a").click(function (e) {
				let id = $(this).attr("id")
				$(".commodity ul li").remove()
				$.ajax({
					type: "post",
					dataType: "json",
					url: commonsUrl + 'api/gxsc/get/goods_class/list' + versioninfos,
					data: {
						goods_second_id: id,
						page: "1",
						ss: getCookie('openid')
					},
					success: (res) => {
						let data = res.result
						for (let val of data) {
							let temp = $("#commentList").html()
							temp = temp.replace("{{goods_name}}", val.goods_name).replace("{{image}}", val.image).replace("{{price}}", val.price).replace("{{market_price}}", val.market_price).replace("{{ext_id}}", val.ext_id)
							$(".commodity ul").append(temp)
						}
					}
				})


			})
		}, 200);

   
		// 获取商品列表数据
		$.ajax({
			type: "post",
			dataType: "json",
			url: commonsUrl + 'api/gxsc/get/goods_class/list' + versioninfos,
			data: {
				goods_second_id: "4",
				page: "1",
				ss: getCookie('openid')
			},
			success: (res) => {
			    console.log(res)
				let data = res.result
				for (let val of data) {
					let temp = $("#commentList").html()
					temp = temp.replace("{{goods_name}}", val.goods_name).replace("{{image}}", val.image).replace("{{price}}", val.price).replace("{{market_price}}", val.market_price).replace("{{ext_id}}", val.ext_id)
					$(".commodity ul").append(temp)
				}
			}
		})



	})

</script>