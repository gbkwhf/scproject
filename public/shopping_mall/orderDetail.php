<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>订单详情</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="css/common.css">
		<link rel="stylesheet" type="text/css" href="css/myOrderList.css" />
		<link rel="stylesheet" type="text/css" href="css/orderDetail.css" />
	</head>
	<style type="text/css">

	</style>

	<body>
		<!--上面的发货状态-->
		<div class="orderDetHe">交易成功</div>
		<!--物流信息-->
		<div class="orderWuliu">
			<!--车-->
			<div class="cheWuliu"></div>
			<!--配送状态-->
			<div class="peisong">配送完成</div>
			<!--跳转-->
			<div class="tiaozhuang"></div>
		</div>
		<!--个人信息-->
		<div class="personInfo">
			<div class="perInfoma">收货人：黄豆子 181*****9546</div>
			<div class="addreIndo">地址：陕西省哈陕西省哈哈哈哈哈哈哈哈哈陕西省哈哈哈哈哈哈哈哈哈陕西省哈哈哈哈哈哈哈哈哈哈哈哈哈哈哈哈哈</div>
		</div>

		<div class="boxContent">
			<div class="orderInfo">


				<!--标题部分-->
				<div class="orGe"></div>
				
				
				<!-- <div class="orderHea">
					<div class="orderStore">这是我的店铺哈哈哈哈哈哈</div>
				</div> -->
				<!--下面的产品-->
				<div class="shopInfoBox">
					<script type="text/html" id="commentList">
						<div class="shopBoxCon">
							<!--图片-->
							<div class="imgInfo"><img src="{{image}}" alt="" /></div>
							<!--标题信息-->
							<div class="titleInfoBox">
								<!--商品标题-->
								<div class="orderTitle">{{goods_name}}</div>
								<!--标签-->
								<div class="orderBiaoQian1">
									<span class="biao1 biao2">{{spec_name}}</span>
								</div>
								<!--价格 以及数量-->
								<div class="orderPriceBox">
									<span class="orderPrice orderPrice1">￥{{goods_price}}元</span>
									<span class="orderNum">+{{num}}</span>
								</div>
							</div>
						</div>
						
						
					</script>
						
					<!--商品的总数量-->
					<!-- <div class="shopNumSum">
						<span class="sumShop" style="float: left;margin-left: 17px;">运费：8元 实付款：18元</span>	
					</div> -->
				</div>
			</div>
		</div>

		<!--订单编号  下单时间-->
		<div class="orderInBox">
			<p>订单编号：12378774644</p>
			<p>下单时间：12378774644</p>
			<p>支付方式：12378774644</p>
			<p>留言信息：12378774644</p>
		</div>
		<!--下面的查看物流 内容-->
		<div class="wuliuConter">
			
		</div>
	</body>

</html>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script type="text/javascript">
	console.log($_GET['id'])
	if($_GET['id']==0){
		$(".orderDetHe").text("等待买家发货")
		$(".wuliuConter").append('<div class="wuliBox"><span class="checkcont">立即支付</span></div>')
		$.ajax({
			type: 'POST',
			dataType: "json",
			url: commonsUrl + '/api/gxsc/v2/get/base_order/info' + versioninfos,
			data: {
				base_order_id: $_GET['base_order_id'],
				ss: getCookie('openid')
			},
			success: (res) => {
				try{
					console.log(res)
					let data=res.result
					let TYPE=data.pay_type==1?"微信":"线下支付"
					$(".perInfoma").text("收货人：" + data.user_name + "   " +data.user_mobile)
					$(".addreIndo").text("地址 : " + data.address)
					$(".orderInBox p:nth-child(1)").text("订单编号：" + data.base_order_id)
					$(".orderInBox p:nth-child(2)").text("下单时间：" + data.create_time) 
					$(".orderInBox p:nth-child(3)").text("支付方式：" + TYPE) 
					$(".orderInBox p:nth-child(4)").text("留言信息：" + data.user_remark) 

					for(let i=0;i<data.info.length;i++){
						console.log(data.info[i])

						$(".shopInfoBox").append('<div class="orderHea"><div class="orderStore">'+data.info[i].supplier_name+'</div></div>')
						for(let j=0;j<data.info[i].goods_list.length;j++){
							console.log(data.info[i].goods_list[j].num)
							let temp = $("#commentList").html()
							temp = temp.replace("{{image}}", data.info[i].goods_list[j].image)
							.replace("{{goods_name}}", data.info[i].goods_list[j].goods_name)
							.replace("{{spec_name}}", data.info[i].goods_list[j].spec_name)
							.replace("{{goods_price}}", data.info[i].goods_list[j].goods_price)
							.replace("{{num}}", data.info[i].goods_list[j].num)
							$(".shopInfoBox").append(temp)
						}

					}

					$(".shopInfoBox").append('<div class="shopNumSum"><span class="sumShop" style="float: left;margin-left: 17px;">运费：'+data.shipping_price+'元 实付款：'+data.price+'元</span></div>')
					
				}catch(e){
					console.log(e)
				}
				
			},
			error: (res) => {
				console.log(res)
			}
		})
	}else{
		$(".orderDetHe").text("卖家已发货")
		
		$.ajax({
			type: 'POST',
			dataType: "json",
			url: commonsUrl + '/api/gxsc/v2/get/sub_order/info' + versioninfos,
			data: {
				sub_order_id: $_GET['sub_order_id'],
				ss: getCookie('openid')
			},
			success: (res) => {
				try{
					console.log(res)
					let data=res.result
					let TYPE=data.pay_type==1?"微信":"线下支付"
					$(".perInfoma").text("收货人：" + data.name + "   " +data.mobile)
					$(".addreIndo").text("地址 : " + data.address)
					$(".orderInBox p:nth-child(1)").text("订单编号：" + data.base_order_id)
					$(".orderInBox p:nth-child(2)").text("下单时间：" + data.create_time) 
					$(".orderInBox p:nth-child(3)").text("支付方式：" + TYPE) 
					$(".orderInBox p:nth-child(4)").text("留言信息：" + data.user_remark) 
					$(".wuliuConter").append('<div class="wuliBox"><span class="checkcont"  onclick="location.href="logistical.php?sub_order_id='+data.sub_order_id+'"">查看物流</span></div><div class="wuliBox"><span class="checkcont">确认收货</span></div>')
						// $(".shopInfoBox").append('<div class="orderHea"><div class="orderStore">'+data.info[i].supplier_name+'</div></div>')
						for(let j=0;j<data.goods_list.length;j++){
							console.log(data.goods_list[j].num)
							let temp = $("#commentList").html()
							temp = temp.replace("{{image}}", data.goods_list[j].image)
							.replace("{{goods_name}}", data.goods_list[j].goods_name)
							.replace("{{spec_name}}", data.goods_list[j].spec_name)
							.replace("{{goods_price}}", data.goods_list[j].goods_price)
							.replace("{{num}}", data.goods_list[j].num)
							$(".shopInfoBox").append(temp) 
						}

					$(".shopInfoBox").append('<div class="shopNumSum"><span class="sumShop" style="float: left;margin-left: 17px;">运费：'+data.shipping_price+'元 实付款：'+data.price+'元</span></div>')
					
				}catch(e){
					console.log(e)
				}
				
			},
			error: (res) => {
				console.log(res)
			}
		})
	}
	
</script>