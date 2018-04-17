<!DOCTYPE html>
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="format-detection" content="telephone=no" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="stylesheet" href="css/common.css">
	<link rel="stylesheet" href="css/Super.css" />
	<meta charset="UTF-8">
	<title>商超</title>
</head>

<body>

	<div class="clarity"></div>
	<div id="body">

		<div class="list">
			<ul>
				<img src="images/bot.png" id="show" />
			</ul>
		</div>

		<div class="msg">
			<ul class="tem">
				<script type="text/html" id="commentList">
				<li onclick="location.href='reclassify.php?id={{store_id}}'">
					<div>
						<img src="{{logo}}" />
						<p>
							<span>{{store_name}}</span>
							<span>进入专场</span>
						</p>
					</div>
				</li>
			</script>
			</ul>
			<div class="float">
				<ul>
					<img src="images/top.png" id="hide" />
				</ul>
			</div>
		</div>

</body>

</html>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/config.js"></script>
<script src="js/jquery.min.js"></script>
<script type="text/javascript">
	let page=1
	let store_second_id


	$(".clarity").css("height", $(this).height() + "px")

	$("#show").click(function () {
		$(".float").show()
		$(".clarity").show()
	})

	$("#hide").click(function () {
		$(".float").hide()
		$(".clarity").hide()
	})



	// 导航分类
	$.ajax({
		type: "POST",
		dataType: "json",
		url: commonsUrl + 'api/gxsc/get/second/info/list' + versioninfos,
		data: {
			ss: getCookie('openid'),
			store_first_id: 1
		},
		success: (res) => {
			let data = res.result;
			store_second_id = data[0].store_second_id
			for (let i = 0; i < data.length; i++) {
				if (i == 0) {
					$(".list ul").append("<li id=" + data[i].store_second_id + " " + 'class="select"' + '>' + data[i].store_second_name + "</li>")
				} else {
					$(".list ul").append("<li id=" + data[i].store_second_id + '>' + data[i].store_second_name + "</li>")
				}
				$(".float ul").append("<li id=" + data[i].store_second_id + '>' + data[i].store_second_name + "</li>")
			}
		}
	})






	setTimeout(() => {
		shop(store_second_id,page)



		$(".list li").click(function (e) {
			store_second_id = e.target.id
			let index = $(this).index()
			$(this).addClass("select").siblings().removeClass("select");
			// $(".msg li").hide().eq(index).show()

			$(".tem li").remove()

			shop(store_second_id,page)
		})



		$(".float li").click(function () {
			let store_second_id = $(this).attr("id")
			$(".tem li").remove()
			shop(store_second_id,page)
			$(".float").hide()
			$(".clarity").hide()
		})
	}, 200);



	$(this).scroll(function () {
		var viewHeight = $(this).height();//可见高度  
		var contentHeight = $("#body").get(0).scrollHeight;//内容高度  
		var scrollHeight = $(this).scrollTop();//滚动高度  
		if ((contentHeight - viewHeight) / scrollHeight <= 1) {
			page++
			shop(store_second_id,page)
		}
	})




	// // 门店	
	function shop(id,page) {
		$.ajax({
			type: "POST",
			dataType: "json",
			url: commonsUrl + 'api/gxsc/get/store/list' + versioninfos,
			data: {
				ss: getCookie('openid'),
				store_second_id: id,
				page: page
			},
			success: (res) => {
				console.log(res)
				let data = res.result
				for (let val of data) {
					let temp = $("#commentList").html()
					temp = temp.replace("{{logo}}", val.logo).replace("{{store_name}}", val.store_name).replace("{{store_id}}", val.store_id)
					$(".tem").append(temp)
				}
			}
		})
	}
</script>