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

		<div class="wrapper wrapper02" id="wrapper02">
			<div class="scroller">
				<ul class="clearfix">
					<script type="text/html" id="navList">
						<li>
							<a href="javascript:void(0)" id="{{goods_second_id}}">{{goods_second_name}}</a>
						</li>
					</script>
				</ul>
			</div>
			<img src="images/bot.png" id="show" alt="">
		</div>

		<div class="msg">
			<ul class="tem">
				<script type="text/html" id="commentList">
				<li onclick="location.href='reclassify.php?store_id={{store_id}}&name={{name}}'" style="border-top:6px solid #f3f2f2">
					<div>
						<img src="{{logo}}" />
						<p>
							<span style="font-size:.4rem">{{store_name}}</span>
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
		<p style="line-height: 616px; text-align: center; color: rgb(198, 191, 191);display:none" class="show">暂无商品,敬请期待!</p>
</body>

</html>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/config.js"></script>
<script src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/navbar/flexible.js"></script>
<script type="text/javascript" src="js/navbar/iscroll.js"></script>
<script type="text/javascript" src="js/navbar/navbarscroll.js"></script>
<script type="text/javascript">
	let page=1
	let store_second_id
	if($_GET["store_first_id"]==1){
		$("title").text("商超")
	}else if($_GET["store_first_id"]==2){
		$("title").text("精品馆")
	}else{
		$("title").text("土特产")
	}

	$(".clarity").css("height", $(this).height() + "px")

	$("#show").click(function () {
		$(".float").show()
		$(".clarity").show()
	})

	$("#hide").click(function () {
		$(".float").hide()
		$(".clarity").hide()
	})

	console.log($_GET['store_first_id'])
	// 导航分类
	$.ajax({
		type: "POST",
		dataType: "json",
		url: commonsUrl + 'api/gxsc/get/second/info/list' + versioninfos,
		data: {
			ss: getCookie('openid'),
			store_first_id: $_GET['store_first_id']
		},
		success: (res) => {
			console.log(res)
			let data = res.result;
			store_second_id = data[0].store_second_id
			for (let val of data) {
				let temp = $("#navList").html()
				temp = temp.replace("{{goods_second_name}}", val.store_second_name).replace("{{goods_second_id}}", val.store_second_id)
				$(".clearfix").append(temp)
				$(".float ul").append("<li id=" + val.store_second_id + '>' + val.store_second_name + "</li>")
			}


			

			setTimeout(() => {
				$('.wrapper').navbarscroll();
				shop(store_second_id,page)
				$(".clearfix li").click(function (e) {
					store_second_id = e.target.id
					let index = $(this).index()
					$(this).addClass("select").siblings().removeClass("select");
					// $(".msg li").hide().eq(index).show()
					page=1
					$(".tem li").remove()

					shop(store_second_id,page)
				})



				$(".float li").click(function () {
					let store_second_id = $(this).attr("id")
					let index=$(this).index()
					let nums= -parseInt(index)/0.02
					$(".scroller").attr("style","width: "+ data.length*98 +"px;transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1);transition-duration: 0ms;transform: translate("+nums+"px, 0px) translateZ(0px);")
					$(".tem li").remove()
					shop(store_second_id,page)
					$(".float").hide()
					$(".clarity").hide()
					$(".clearfix li").each(function(){
						if(index==$(this).index()){
							$(this).addClass("cur").siblings().removeClass("cur")
						}
					})
				})
			}, 200);	
		}
	})






	



	$(this).scroll(function () {
		var viewHeight = $(this).height();//可见高度  
		var contentHeight = $("#body").get(0).scrollHeight;//内容高度  
		var scrollHeight = $(this).scrollTop();//滚动高度  
		if ((contentHeight - viewHeight) / scrollHeight <= 1) {
			page++
			shop(store_second_id,page,show)
		}
	})




	// // 门店	
	function shop(id,page,show) {
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
				if(data.length==0){
					if(!show){
						$(".show").show()
					}
				}else{
					$(".show").hide()
				}
				for (let val of data) {
					let temp = $("#commentList").html()
					temp = temp.replace("{{logo}}", val.logo).replace("{{store_name}}", val.store_name).replace("{{store_id}}", val.store_id).replace("{{name}}", val.store_name)
					$(".tem").append(temp)
				}
			}
		})
	}
</script>