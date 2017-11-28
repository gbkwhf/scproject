<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>记录详情</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    	<meta name="apple-mobile-web-app-capable" content="yes"><meta name="format-detection" content="telephone=no" />
    	<meta name="apple-mobile-web-app-status-bar-style" content="black">
    	<link rel="stylesheet" href="css/common.css">
    	<style>
    		body{
    			background:#edf0f2 ;
    		}
    		.wraper{
    			padding: 10px;
                width:100%;
                box-sizing: border-box;
    		}
    		.header{
    			background: #fff;
    			border:1px solid #e6e6e6;
    			border-radius:5px ;
    			padding:15px 12px;
    			
    		}
    		.receipt_address{
    			color:#000;
    			font-size: 15px;
    			margin-bottom:15px;
    		}
    		.user_name{
    			color:#333;
    			font-size:13px;
    			overflow: hidden;
    			margin-bottom:13px;
    		}
    		.user_name>em{
    			font-style: normal;
    			float:left;
    			
    		}
    		.user_name>i{
    			font-style: normal;
    			float:right;
    		}
    		.address{
    			font-size:14px ;
    			color:#333;
    		}
    		.remarks_info{
    			margin-top:13px;
    			font-size:14px ;
    			color:#333;
    		}
    		.section{
    			width:100%;
    			padding:14px 12px 14px 0;
    			background: #fff;
    			box-sizing: border-box;
    			margin-top:10px;
    			border:1px solid #e6e6e6;
    			border-radius: 5px;
    		}
    		.good_detail{
    			font-size:13px ;
    			color:#333;
    			padding-bottom: 14px;
    			padding-left:14px ;
    		}
    		.divider{
    			display: block;
    			width:50%;
    			height:1px;
    			background: #e6e6e6;
    			
    		}
    		.goods_list{
    			width:100%;
    			padding-left: 14px;
    			padding-top: 14px;
    			box-sizing: border-box;
    		}
    		.goods_list>li{
    			overflow: hidden;
    			margin-bottom:10px;
    		}
    		.pic_box{
    			width:90px;
    			height:90px;
    			padding: 20px 9px;
    			box-sizing: border-box;
    			border:1px solid #E6E6E6 ;
    			margin-right:10px;
    			background:#f6f2f3 ;
    			float: left;
    			
    		}
    		.pic_box>img{
    			width: 100%;
    		}
    		.info_box{
    			padding:6px 0 ;
    			float: left;
    		}
    		.info_box>span{
    			font-style: normal;
			    color: #333;
			    font-size: 14px;
			   
			    display: block;
			    overflow: hidden;
				text-overflow:ellipsis;	
				-webkit-box-orient: vertical;
			    -webkit-line-clamp: 3;
				display: -webkit-box;
				line-height: 17px;
				min-height: 53px;
    		}
    		.name_price{
    			overflow: hidden;
    		    padding-top:4px ;
    		}
    		.name_price>em{
    			font-style: normal;
    			font-size:15px ;
    			float: left;
    			color:#e09565;
    		}
    		.name_price>i{
    			color:#999999;
    			font-style: normal;
    			font-size: 16px;
    			float: right;
    			padding-top: ;
    		}
    		.total{
    			font-size:12px ;
    			color: #666;
				text-align: right;
				padding: 10px 0px 0px 14px;
				line-height: 1.5em;
    		}
    		.total>span{
    			font-size: 16px;
				color: #d57232;
    		}
    		.order_list{
    			border:1px solid #E6E6E6;
    			border-radius:5px ;
    			background: #fff;
    			margin-top:10px;
    			
    		}
    		.order_list>ol{
    			width:100% ;
    		}
    		.order_list>ol>li{
    			overflow: hidden;
    			height:50px;
    			line-height:50px ;
    			border-bottom: 1px solid #E6E6E6;
    		}
    		.order_list>ol>li>span{
    			color:#333;
    			font-size:13px ;
    			float:left;
    			padding-left:14px ;
    		}
    		.order_list>ol>li>em{
    			font-style: normal;
    			font-size:13px ;
    			float: right;
    			padding-right:14px ;
    		}
    		.order_list>ol>li>i{
    			font-style: normal;
    			font-size:13px ;
    			float: right;
    			color:#9f9f9f;
    			padding-right:14px ;
    		}
    	</style>
	</head>
	<body>
		<div class="wraper">
			<div class="header">
				<p class="receipt_address">收货地址</p>
				<div class="user_name">
					<em>张某某</em>
					<i>18829783382</i>
				</div>
				<p class="address">陕西省.西安市.高新区.绿地蓝海<p>
				<p class="remarks_info">备注信息</p>
			</div>
			<div class="section">
				<p class="good_detail">商品信息</p>
				<em class="divider"></em>
				<ul class="goods_list">
					<li>
						<div class="pic_box">
							<img src="images/rices.png" />
						</div>
						<div class="info_box">	
							<span >长城红酒长 长城红酒长长城红酒长长城红酒长长城红酒长长城红酒长长城红酒长长城红酒长长城红酒长长城红酒长</span>
							<div class="name_price">
								<em>￥388.00</em>
								<i>x 1</i>
							</div>
						</div>
					</li>
					<li>
						<div class="pic_box">
							<img src="images/rices.png" />
						</div>
						<div class="info_box">	
							<span >长城红酒  </span>
							<div class="name_price">
								<em>￥388.00</em>
								<i>x 1</i>
							</div>
						</div>
					</li>
				</ul>
				<p class="total">总金额：<span>￥388.00</span></p>
			</div>
			<div class="order_list">
				<ol>
					<li>
						<span>支付方式</span>
						<em>线下支付</em>
					</li>
					<li>
						<span>订单编号</span>
						<i>2152555966685</i>
					</li>
					<li style="border: none;">
						<span>购买时间</span>
						<i>2017-11-27 10:00</i>
					</li>
				</ol>
			</div>
		</div>
		
	</body>
	<script src="js/jquery.min.js"></script>
	<script src="js/layer/layer.js"></script>
	<script src="js/common.js"></script>
	<script src="js/config.js"></script>
	<script type="text/javascript">
		var winW=$(window).width();
		$(".info_box").width(winW-148);
		//手机号中间用省略号代替
		var phone=$(".user_name>i").html();
		var mphone = phone.substr(0,3) + '*****' + phone.substr(8,11);
		$('.user_name>i').html(mphone);
		
	</script>
</html>
