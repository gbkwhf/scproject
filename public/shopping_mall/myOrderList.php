<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>订单列表</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="css/common.css">
		<link rel="stylesheet" type="text/css" href="css/myOrderList.css"/>
	</head>
	<body>
		<!--顶部-->
		<div class="orderHeader">
			<!-- <div class="ordeBox" >
				<div class="quanbu commClick getStyle" id="">全部</div>
			</div> -->
			<div class="ordeBox">
				<div class="fukuan commClick getStyle" id="0">待付款</div>
			</div>
			<div class="ordeBox">
				<div class="fukuan1 commClick" id="1">待收货</div>
			</div>
			<div class="ordeBox">
				<div class="fukuan1 commClick" id="2">待评价</div>
			</div>
		</div>
		<!--下面的内容-->
		<div class="boxContent">
			
			<div class="orderInfo">
				
					<!--标题部分-->
					<div class="orGe"></div>
					<!-- <div class="orderHea">
						<div class="orderStore">这是我的店铺哈哈哈哈哈哈</div>
						<div class="orderStatus">等待卖家发货</div>
					</div> -->
					<!--下面的产品-->
					<div class="shopInfoBox">
						<script type="text/html" id="commentList">
							<div class="shopBoxCon" onclick="">
									<!--图片-->
									<div class="imgInfo"><img src="{{image}}" alt="" /></div>
									<!--标题信息-->
									<div class="titleInfoBox">
										<!--商品标题-->
										<div class="orderTitle">{{goods_name}}</div>
										<!--标签-->
										<div class="orderBiaoQian1">
											<span class="biao1">{{spec_name}}</span>
										</div>
										<!--价格 以及数量-->
										<div class="orderPriceBox">
											<span class="orderPrice">￥{{price}}元</span>
											<span class="orderNum">+{{num}}</span>
										</div>
									</div>
							</div>
						</script>
						<!--商品的总数量-->
						<!-- <div class="shopNumSum">
							<span class="sumShop">共2件商品</span>
							<span class="hejiCon">合计{{require_amount}}元(含运费0.00元)</span>
						</div> -->
						
						<!--下面的查看物流 内容-->
						<!-- <div class="wuliuConter">
							<div class="wuliBox">
								<span class="checkcont">查看物流</span>
							</div> -->
							<!--<div class="wuliBox">
								<span class="checkcont">申请退货</span>
							</div>-->
							<!-- <div class="wuliBox">
								<span class="checkcont">确认收货</span>
							</div>
						</div> -->
					</div>
					
			</div>
		</div>
	</body>
</html>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script type="text/javascript">
	$(function(){
		var orderId=$_GET['orderId'];
		let URL="api/gxsc/v2/get/order/info/obligation/list"
		let id=0
		console.log(orderId);
		packaging(URL)
		$(".commClick").click(function(){
			$(".shopBoxCon").remove();$(".shopNumSum").remove();$(".wuliuConter").remove();$(".orderHea").remove()

			$(this).addClass("getStyle").parent().siblings().find(".commClick").removeClass("getStyle")
			id=$(this).attr("id")
			switch(id){
				case "0":
					URL="api/gxsc/v2/get/order/info/obligation/list"
					packaging(URL)
				break;
				case "1":
					URL="api/gxsc/v2/get/order/info/list"
					packaging(URL)
				break;
				case "2":
					$.ajax({
					type: "post",
					url: commonsUrl + "api/gxsc/v2/get/order/info/comment/list"+ versioninfos,
					data: {
						page:"1",	
						ss: getCookie('openid')
					},
					success:(res)=>{
						console.log(res.result)
						try {
							if (res.code == "1") {
								let data = res.result
								for (let val=0; val<data.length;val++) {
									$(".orderInfo").before(' <div class="orderHea"><div class="orderStore">'+data[val].supplier_name+'</div><div class="orderStatus">交易成功</div></div>')
											let temp = $("#commentList").html()
											temp = temp.replace("{{image}}", data[val].image)
											.replace("{{goods_name}}", data[val].goods_name)
											.replace("{{spec_name}}", data[val].spec_name)
											.replace("{{price}}", data[val].price)
											.replace("{{num}}", data[val].num)
											$(".shopInfoBox").append(temp)
											let aa='<div class="shopNumSum"><span class="sumShop">共'+data[val].num+'件商品</span><span class="hejiCon">合计'+data[val].goods_price+'元(含运费0.00元)</span></div><div class="wuliuConter"><div class="wuliBox"><span class="checkcont">查看物流</span></div><div class="wuliBox"><span class="checkcont">确认收货</span></div></div>'
										$(".shopInfoBox").append(aa)
								}

							}
						} catch (e) {
							console.log(e)
						}
					}
				})
			}
		});


		// console.log(orderId+'******');
		// if(orderId==0){ //待付款
		// 	$('.fukuan').addClass("getStyle").parent().siblings().find(".commClick").removeClass("getStyle")
		// }else if(orderId==1){ //待收货
		// 	$('.fukuan1').addClass("getStyle").parent().siblings().find(".commClick").removeClass("getStyle")
		// }else if(orderId==2){//待评价
		// 	$('.fukuan2').addClass("getStyle").parent().siblings().find(".commClick").removeClass("getStyle")
		// }else{
		// 	$('.quanbu').addClass("getStyle").parent().siblings().find(".commClick").removeClass("getStyle")
		// }


			function  packaging(URL){
				$.ajax({
				type: "post",
				url: commonsUrl + URL+ versioninfos,
				data: {
					page:"1",	
					ss: getCookie('openid')
				},
				success:(res)=>{
					console.log(res.result)
					try {
						if (res.code == "1") {
							let data = res.result
							for (let val=0; val<data.length;val++) {

								if(val>=0){
									 if(id==1){
										$(".shopInfoBox").append('<div class="orderHea"><div class="orderStore">这是我的店铺哈哈哈哈哈哈</div></div>')
									}
								}

								let num
								for(let vals=0; vals<data[val].goods_list.length;vals++){
									num=data[val].goods_list.length
										let temp = $("#commentList").html()
										temp = temp.replace("{{image}}", data[val].goods_list[vals].image)
										.replace("{{goods_name}}", data[val].goods_list[vals].goods_name)
										.replace("{{spec_name}}", data[val].goods_list[vals].spec_name)
										.replace("{{price}}", data[val].goods_list[vals].price)
										.replace("{{num}}", data[val].goods_list[vals].num)
										$(".shopInfoBox").append(temp)
										if(id==0){
											$(".shopBoxCon").attr("onclick","location.href='orderDetail.php?base_order_id="+data[val].base_order_id+"&id=0'")
										}else if(id==1){
											$(".shopBoxCon").attr("onclick","location.href='orderDetail.php?sub_order_id="+data[val].sub_order_id+"&id=1'")
										}
								}
								if(val>=0){
									if(id==0){
										let aa='<div class="shopNumSum"><span class="sumShop">共'+num+'件商品</span><span class="hejiCon">合计'+data[val].require_amount+'元(含运费0.00元)</span></div><div class="wuliuConter"><span class="checkcont">立即支付</span></div></div>'
										$(".shopInfoBox").append(aa)
									}else if(id==1){
										// $(".shopInfoBox").prepend('<div class="orderHea"><div class="orderStore">这是我的店铺哈哈哈哈哈哈</div></div>')
										let aa='<div class="shopNumSum"><span class="sumShop">共'+num+'件商品</span><span class="hejiCon">合计'+data[val].price+'元(含运费'+data[val].shipping_price+'元)</span></div><div class="wuliuConter"><span class="checkcont">查看物流</span><span class="checkcont">确定收货</span></div></div>'
										$(".shopInfoBox").append(aa)
									}
									
								}

							}

						}
					} catch (e) {
						console.log(e)
					}
				}
		})
	}
		
	})
</script>