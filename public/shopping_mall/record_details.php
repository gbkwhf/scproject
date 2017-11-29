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
    			/*padding:14px 12px 14px 0;*/
    			padding:14px 0;
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
    			padding: 14px 14px 0px;
    			box-sizing: border-box;
    		}
    		.goods_list>li{
    			overflow: hidden;
    			margin-bottom:10px;
    		}
    		.pic_box{
    			width:89px;
    			height:89px;
    			box-sizing: border-box;
    			border:1px solid #e6e6e6 ;
    			margin-right:10px;
    			background:#f6f2f3 ;
    			float: left;
			    display: -webkit-box;
			    display: -ms-flexbox;
			    display: -webkit-flex;
			    display: flex;
			    -webkit-display: -webkit-flex;
			    display: flex;
			    -webkit-align-items: center;
			    align-items: center;
			    -webkit-justify-content: center;
			    justify-content: center;
    		}
    		.pic_box>img{
    			width: 100%;
    			max-height: 89px;
    			min-height: 65px;
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
				padding: 10px 14px 0px;
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
				<p class="receipt_address">收货信息</p>
				<div class="user_name">
					<em></em>
					<i></i>
				</div>
				<p class="address"><p>
				<p class="remarks_info">备注信息：</p>
			</div>
			<div class="section">
				<p class="good_detail">商品信息</p>
				<em class="divider"></em>
				<ul class="goods_list">
					<!--<li>
						<div class="pic_box">
							<img src="images/rices.png" />
						</div>
						<div class="info_box">	
							<span >长城红酒长 </span>
							<div class="name_price">
								<em>￥388.00</em>
								<i>x 1</i>
							</div>
						</div>
					</li>-->
				</ul>
				<em class="divider" style="float: right;"></em>
				<p class="total">总金额：<span></span></p>
			</div>
			<div class="order_list">
				<ol>
					<li class="pay_mode">
						<span>支付方式</span>
						<em></em>
					</li>
					<li class="order_num">
						<span>订单编号</span>
						<i></i>
					</li>
					<li style="border: none;" class="buy_time">
						<span>购买时间</span>
						<i></i>
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

		$.ajax({
			type:"post",
			url:commonsUrl + "api/gxsc/get/commodity/base_order/info" + versioninfos,
			data:{'ss':getCookie('openid'),'base_order_id':$_GET['base_order_id']},
			success:function(data){
				if(data.code==1){
					console.log(data);
					$('.user_name em').html(data.result.name);
					$('.user_name i').html(data.result.mobile.substr(0,3)+'*****'+data.result.mobile.substr(data.result.mobile.length-3,data.result.mobile.length));
					$('.address').html(data.result.address);
					$('.remarks_info').append(data.result.user_remark);
					html='';
					for(var i=0;i<data.result.info.length;i++){
						for(var j=0;j<data.result.info[i].goods_list.length;j++){
							html+='<li>'+
							'	<div class="pic_box">'+
							'		<img src="'+data.result.info[i].goods_list[j].image+'" />'+
							'	</div>'+
							'	<div class="info_box">	'+
							'		<span >'+data.result.info[i].goods_list[j].goods_name+' </span>'+
							'		<div class="name_price">'+
							'			<em>¥'+data.result.info[i].goods_list[j].goods_price+'</em>'+
							'			<i>x '+data.result.info[i].goods_list[j].num+'</i>'+
							'		</div>'+
							'	</div>'+
							'</li>';
						}
					}
					$('.goods_list').html(html);
					$(".info_box").width(winW-149);
					$('.order_num i').html(data.result.base_order_id);
					$('.total span').html('¥'+data.result.price);
					if(data.result.pay_type==1){
						$('.pay_mode em').html('微信');						
					}else{
						$('.pay_mode em').html('线下支付');
					}
					$('.buy_time i').html(data.result.create_time);
					
				}else{
					layer.msg(data.msg);
				}
			}
		});
		
	</script>
</html>
