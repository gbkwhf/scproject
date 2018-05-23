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
			<div class="perInfoma"></div>
			<div class="addreIndo"></div>
			<img src="images/site.png" alt="">
		</div>
		<div style="height:7px;background:#f3f2f2"></div>
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
			<p>物流单号：12378774644</p>
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
		$(".orderDetHe").text("待付款 ( 亲，赶快下单哈！)")
		
		$.ajax({
			type: 'POST',
			dataType: "json",
			url: commonsUrl + 'api/gxsc/v2/get/base_order/info' + versioninfos,
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
					$(".peisong").text(data.info[0].express.state)
					$(".orderInBox p:nth-child(1)").text("订单编号：" + data.base_order_id)
					$(".orderInBox p:nth-child(2)").text("") 
					$(".orderInBox p:nth-child(3)").text("下单时间：" + data.create_time) 
					$(".orderInBox p:nth-child(4)").text("支付方式：" + TYPE) 
					$(".orderInBox p:nth-child(5)").text("留言信息：" + data.user_remark) 
					$(".wuliuConter").append('<div class="wuliBox"><span class="checkcont pay" id="'+data.base_order_id+'">立即支付</span></div>')
					for(let i=0;i<data.info.length;i++){
						console.log(data.info[i])

						$(".shopInfoBox").append('<div class="orderHea" style="border:none"><div class="orderStore">'+data.info[i].supplier_name+'</div></div>')
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
				delay()
			},
			error: (res) => {
				console.log(res)
			}
		})
	}else{
		
		$.ajax({
			type: 'POST',
			dataType: "json",
			url: commonsUrl + 'api/gxsc/v2/get/sub_order/info' + versioninfos,
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
					$(".peisong").text(data.express.state)
					if(data.express.state=="未发货"){
						$(".orderDetHe").text("等待卖家发货")
					}else{
						$(".orderDetHe").text(data.express.state)
					}
					
					$(".orderInBox p:nth-child(1)").text("订单编号：" + data.base_order_id)
					$(".orderInBox p:nth-child(2)").text("物流单号：" + data.express_num) 
					$(".orderInBox p:nth-child(3)").text("下单时间：" + data.create_time) 
					$(".orderInBox p:nth-child(4)").text("支付方式：" + TYPE) 
					$(".orderInBox p:nth-child(5)").text("留言信息：" + data.user_remark) 
					$(".wuliuConter").append('<div class="wuliBox"><span class="checkcont phy"  data-id="'+data.sub_order_id+'">查看物流</span></div><div class="wuliBox"><span class="checkcont aff" data-id="'+data.sub_order_id+'">确认收货</span></div>')
						// $(".shopInfoBox").append('<div class="orderHea" style="border:none"><div class="orderStore">'+data.info[i].supplier_name+'</div></div>')
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
				delay()
			},
			error: (res) => {
				console.log(res)
			}
		})
	}


	function delay(){
		setTimeout(() => {
		$(".pay").click(function(){
			let base_order_id =$(this).attr("id")
			$.ajax({
				type: "post",
				url: commonsUrl + "api/gxsc/pay/goods" + versioninfos,
				data: {
					'base_order_id':base_order_id,
					'filling_type': 3,
					'open_id': getCookie('openid'),
					'ss': getCookie('openid')
				},
				success: function(data) {
					if(data.code == 1) {
						console.log(data);
						data.result.timeStamp = data.result.timeStamp.toString();
						retStr = data.result;
						callpay();
						//调用微信JS api 支付
						function jsApiCall() {
							WeixinJSBridge.invoke(
								'getBrandWCPayRequest',
								retStr,
								function(res) {
									if(res.err_msg == "get_brand_wcpay_request:ok") {
										//支付成功
										location.href = 'myOrderList.php';
									} else {
										//	 alert(res.err_msg);
									}
								}
							);
						}

						function callpay() {
							if(typeof WeixinJSBridge == "undefined") {
								if(document.addEventListener) {
									document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
								} else if(document.attachEvent) {
									document.attachEvent('WeixinJSBridgeReady', jsApiCall);
									document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
								}
							} else {
								jsApiCall();
							}
						}
					} else {
						layer.msg(data.msg);
					}
				}
			})
		})

			$(".phy").click(function(){
				location.href='logistical.php?sub_order_id='+$(this).attr("data-id")
			})

			$(".aff").click(function(){
				$.ajax({
					type: "post",
					dataType: "json",
					url: commonsUrl + 'api/gxsc/v2/ack/receive/goods' + versioninfos,
					data: {
						sub_order_id: $(this).attr("data-id"),
						ss: getCookie('openid')
					},
					success: (res) => {
						console.log(res)
						if(res.code==1){
							layer.msg("确认成功")
						}else{
							layer.msg(res.msg)
						}
					}
				})
			})
	}, 200);
	}
	
</script>