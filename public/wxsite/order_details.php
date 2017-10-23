<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>订单详情</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name = "format-detection" content = "telephone=no">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/order_details.css" />
	</head>
	<body>
		<div class="container">
			<!--<div class="form">
				<div class="form-box">
					<div class="pic-box">
						<img src="image/user-icon.png" width="18px" />
					</div>
					<p class="cont-txt">收货人：王静怡</p>
				</div>
				<div class="form-box">
					<div class="pic-box">
						<img src="image/phone.png" width="13px" />
					</div>
					<p class="cont-txt">联系电话：18356977299</p>
				</div>
				<div class="form-boxs">
					<div class="pic-box">
						<img src="image/address-icon.png" width="15px" />
					</div>
					<p class="cont-txt">详细地址：陕西省西安市高新区丈八北路绿地蓝海大厦10F</p>
				</div>
			</div>
			<div class="goods-info">
				<div class="goods-show">
					<img src="image/order-pic.png" width="123" />
					<div class="explain">
						<p>法国纯天然大米法国纯天然大米法国纯天然大米法国纯天然大米</p>
						<span>¥<mark>800</mark>.00</span>
					</div>
				</div>
				<ul class="goods-details">
					<li><p>订单编号：</p>创建时间</li>
					<li><p>交易账号：</p>交易账号</li>
					<li><p>创建时间：</p>交易账号</li>
					<li><p>付款时间：</p>交易账号</li>
					<li><p>快递公司：</p>交易账号</li>
					<li><p>物流单号：</p>交易账号</li>
				</ul>
			</div>-->
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script>
		
			retdata=JSON.parse(localStorage.getItem("retdata"));
			console.log(retdata);
			
			orderid=$_GET['id'];
		
			for(var i=0;i<retdata.result.length;i++){
				if(retdata.result[i].id==orderid){
					html="";
					html+='<div class="form">';
					html+='	<div class="form-box">';
					html+='		<div class="pic-box">';
					html+='			<img src="image/user-icon.png" width="18px" />';
					html+='		</div>';
					html+='		<p class="cont-txt"><span>收货人：</span>'+retdata.result[i].receive_name+'</p>';
					html+='	</div>';
					html+='	<div class="form-box">';
					html+='		<div class="pic-box">';
					html+='			<img src="image/phone.png" width="13px" />';
					html+='		</div>';
					html+='		<p class="cont-txt"><span>手机号码：</span>'+retdata.result[i].receive_phone+'</p>';
					html+='	</div>';
					html+='	<div class="form-boxs">';
					html+='		<div class="pic-box">';
					html+='			<img src="image/address-icon.png" width="15px" />';
					html+='		</div>';
					html+='		<p class="cont-txt"><span>详细地址：</span>'+retdata.result[i]. receive_address+'</p>';
					html+='	</div>';
					html+='</div>';
					html+='<div class="goods-info">';
					html+='	<div class="goods-show">';
					html+='		<img src="'+retdata.result[i].image+'" width="123" height="123" />';
					html+='		<div class="explain">';
					html+='			<p>'+retdata.result[i].name+'</p>';
					html+='			<div class="pricebox"><span>¥<mark>'+retdata.result[i].price+'</mark></span><strong>x<em>'+retdata.result[i].num+'</em></strong></div>';
					html+='		</div>';
					html+='	</div>';
					if(retdata.result[i].state==0){
						html+='<p class="addprice">合计：<span class="apcolor">¥<mark>'+retdata.result[i].amount+'</mark></span></p>';
					}else{
						html+='<p class="addprice">合计：<span class="apcolors">¥<mark>'+retdata.result[i].amount+'</mark></span></p>';
					}			
					html+='<p class="grayline"></p>';
					html+='	<ul class="goods-details">';
					html+='		<li><p>订单编号：</p>'+retdata.result[i].id+'</li>';
//					html+='		<li><p>交易账号：</p>'+retdata.result[i].receive_name+'</li>';
					html+='		<li><p>创建时间：</p>'+retdata.result[i].created_at+'</li>';
					if(retdata.result[i].state==1){
						html+='		<li><p>付款时间：</p>'+retdata.result[i].payment_at+'</li>';
					}else if(retdata.result[i].state==2){
						html+='		<li><p>付款时间：</p>'+retdata.result[i].payment_at+'</li>';
						html+='		<li><p>快递公司：</p>'+retdata.result[i].express+'</li>';
						html+='		<li><p>物流单号：</p>'+retdata.result[i].tracking_number+'</li>';
					}
					html+='	</ul>';
					html+='</div>';
					$(".container").html(html);
										
					winW=$(window).width();
					$(".explain").width(winW-155);
					
					titH=$(".explain p").height();
					priceH=$(".explain .pricebox").height();
					$(".explain .pricebox").css("margin-top",125-titH-priceH);
				}
			}
			
			
			
		</script>
	</body>
</html>