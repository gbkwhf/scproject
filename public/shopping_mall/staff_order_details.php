<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>订单详情</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"><meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/staff_order_details.css">
</head>
<body>
	<div class="wrapper">
		<div class="order-details">
			<!--<h4>订单号：15515205445214</h4>
			<div class="main">
				<div class="user-info">
					<p>王洪强<span>18344555589</span></p>
					<em><img src="images/address-icon.png" width="10" />陕西省莲湖区丈八一路绿地蓝海大厦10 f1002出门 左转</em>
				</div>
				<ul class="order-list">
					<li>
						<div class="picbox">						
							<img src="images/rice.png" width="100%"/>
						</div>
						<div class="commodity-info">
							<em>长城 (Gmsdfgsdnm) 红酒 华夏葡 萄 (711) 张裕解百纳</em>
							<div class="price-info">
								<p>¥388.00</p>
								<span>x 1</span>
							</div>
						</div>
					</li>
					<li>
						<div class="picbox">						
							<img src="images/rice.png" width="100%"/>
						</div>
						<div class="commodity-info">
							<em>长城 (Gmsdfgsdnm) 红酒 华夏葡 萄 (711) 张裕解百纳</em>
							<div class="price-info">
								<p>¥388.00</p>
								<span>x 1</span>
							</div>
						</div>
					</li>
				</ul>
				<p class="total">需线下收款：<span>¥388.00</span></p>
			</div>
			<ul class="orderinfo-list">
				<li>下单时间：<span>2015-10-25  13:50:06</span></li>
				<li>支付方式：<span>线下支付</span></li>
			</ul>-->
		</div>
		<a href="javascript:;" class="submit">确认收款，提交订单</a>
	</div>
</body>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script>
	
	var winW = $(window).width();
	
	//获取openid
	$.ajax({
		type:"get",
		url: commonsUrl + "api/gxsc/get/user/openId" +versioninfos,
		data:{},
		success:function(data){
			if(data.code==1){
				alert(data.result.openId);
				console.log(data);
				setCookie("openid",data.result.openId);
				if(data.result.is_member==0){
					layer.msg('抱歉，您不是员工哦');
					setTimeout(closewin(),1000)
				}
				setCookie("is_member",data.result.is_member);
				//获取订单详情
				$.ajax({
					type:"post",
					url:commonsUrl+"api/gxsc/get/commodity/base_order/info"+versioninfos,
					data:{
						'ss':getCookie('openid'),
						'base_order_id':$_GET['base_order_id']
					},
					success:function(data){
						if(data.code==1){
							console.log(data);
							html='';
							html+='<h4>订单号：'+data.result.base_order_id+'</h4>'+
							'<div class="main">'+
							'	<div class="user-info">'+
							'		<p>'+data.result.name+'<span>'+data.result.mobile+'</span></p>'+
							'		<em><img src="images/address-icon.png" width="10" />'+data.result.address+'</em>'+
							'	</div>'+
							'	<ul class="order-list">';
							console.log(data.result.info);
							for(var i=0;i<data.result.info.length;i++){
								for(var j=0;j<data.result.info[i].goods_list.length;j++){
									html+='		<li>'+
									'			<div class="picbox">'+						
									'				<img src="'+data.result.info[i].goods_list[j].image+'" width="100%"/>'+
									'			</div>'+
									'			<div class="commodity-info">'+
									'				<em>'+data.result.info[i].goods_list[j].goods_name+'</em>'+
									'				<div class="price-info">'+
									'					<p>¥'+data.result.info[i].goods_list[j].goods_price+'</p>'+
									'					<span>x '+data.result.info[i].goods_list[j].num+'</span>'+
									'				</div>'+
									'			</div>'+
									'		</li>';
								}
							}
							html+='	</ul>'+
							'	<p class="total">需线下收款：<span>¥'+data.result.price+'</span></p>'+
							'</div>'+
							'<ul class="orderinfo-list">'+
							'	<li>下单时间：<span>'+data.result.create_time+'</span></li>'+
							'	<li>支付方式：<span>线下支付</span></li>'+
							'</ul>';
							$('.order-details').html(html);
							
							$('.commodity-info').width(winW-169);
							
						}else{
							layer.msg(data.msg);
						}
					}
				});
				
			}else{
                layer.msg(data.msg);
            }
		}
	});
	
	$('.submit').click(function(){
		$.ajax({
			type:"post",
			url:commonsUrl+"api/gxsc/employee/ack/complete/user/commodity/order"+versioninfos,
			data:{
				'ss':getCookie('openid'),
				'base_order_id':$_GET['base_order_id']				
			},
			success:function(data){
				if(data.code==1){
					console.log(data);
					layer.msg('提交成功');
					
					setTimeout(closewin(),1000)
					
				}else{
					layer.msg(data.msg);
				}
			}
			
		});
	})
	
</script>
<style type="text/css">
	.layui-layer.layui-anim.layui-layer-page{
		border-radius: 5px;
	} 
</style>
</html>