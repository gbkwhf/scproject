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
			<div class="ordeBox" >
				<div class="quanbu commClick getStyle" id="">全部</div>
			</div>
			<div class="ordeBox">
				<div class="fukuan commClick" id="">待付款</div>
			</div>
			<div class="ordeBox">
				<div class="fukuan1 commClick" id="">待收货</div>
			</div>
			<div class="ordeBox1">
				<div class="fukuan2 commClick" id="pingjia">待评价</div>
			</div>
		</div>
		<!--下面的内容-->
		<div class="boxContent">
			
			<div class="orderInfo">
				<script type="text/html" id="commentList">
					<!--标题部分-->
					<div class="orGe"></div>
					<!-- <div class="orderHea">
						<div class="orderStore">这是我的店铺哈哈哈哈哈哈</div>
						<div class="orderStatus">等待卖家发货</div>
					</div> -->
					<!--下面的产品-->
					<div class="shopInfoBox">
						<div class="shopBoxCon">
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
						<!--商品的总数量-->
						<div class="shopNumSum">
							<span class="sumShop">共2件商品</span>
							<span class="hejiCon">合计{{require_amount}}元(含运费0.00元)</span>
						</div>
						
						<!--下面的查看物流 内容-->
						<div class="wuliuConter">
							<div class="wuliBox">
								<span class="checkcont">查看物流</span>
							</div>
							<!--<div class="wuliBox">
								<span class="checkcont">申请退货</span>
							</div>-->
							<div class="wuliBox">
								<span class="checkcont">确认收货</span>
							</div>
						</div>
					</div>
					
				</script>
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
		console.log(orderId);
		$(".commClick").click(function(){
			$(this).addClass("getStyle").parent().siblings().find(".commClick").removeClass("getStyle")
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

		$.ajax({
			type: "post",
			url: commonsUrl + "api/gxsc/v2/get/order/info/obligation/list" + versioninfos,
			data: {
				page:"1",	
				ss: getCookie('openid')
			},
			success:(res)=>{
				console.log(res.result)
				try {
                    if (res.code == "1") {
                        let data = res.result
                        for (let i=0; i<data.length;i++) {
							console.log(data[i])
							for(let j=0;j<data[i].goods_list.length;j++){
								console.log(j)
								// let temp = $("#commentList").html()
								// temp = temp.replace("{{image}}", vals.image)
								// 	.replace("{{goods_name}}", vals.goods_name)
								// 	.replace("{{spec_name}}", vals.spec_name)
								// 	.replace("{{price}}", vals.price)
								// 	.replace("{{num}}", vals.num)
								// 	// .replace("{{require_amount}}", val.require_amount)
								// $(".orderInfo").append(temp)
							}

                        }

                    }
                } catch (e) {
                    console.log(e)
                }
			}
		})
	})
</script>