<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>商品详情</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/commodity_details.css" />
	</head>
	<body>
		<div class="container">
			<!--<div class="banner">
				<img src="image/goods.jpg" />
				<p>标题</p>
			</div>
			<div class="tit-info">
				<p>法国纯天然大米</p>
				<div class="price">
					<span>¥<mark>800.00</mark></span>
					<em>销量：<strong>1.2</strong></em>
				</div>
			</div>
			<p class="grayline"></p>
			<div class="exp-text">
				<p class="title">商品详情</p>
				<span>越光米的历史始于1944年的新澙县农业试验场但 时值二战期间，育种事业被迫中断。1946年杂交 实验在福井县继续，并于1953</span>
			</div>
			<a href="place_order.php" class="submit">立即购买</a>-->
		</div>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
		<script>
		
			session = getCookie('session');
			session = session.substr(1,session.length-2);
			if(!session){
				session="";
			}
		
			gid=$_GET['id'];
			$.ajax({
				type:"post",
				url:commonUrl+'api/stj/goods/goods_info'+versioninfo,
				data:{
					'gid':gid,
					'ss':session
				},
				success:function(data){
					if(data.code==1){
						console.log(data);
						dataStrs=localStorage.setItem("dataStrs",JSON.stringify(data));						
						html="";
						
						html+='<div class="banner">';
						html+='	<img src="'+data.result.image+'" />';
						html+='	<p>'+data.result.name+'</p>';
						html+='</div>';
						html+='<div class="tit-info">';
						html+='	<p>'+data.result.name+'</p>';
						html+='	<div class="price">';
						html+='		<span>¥<mark>'+data.result.price+'</mark></span>';
						html+='		<em>销量：<strong>'+data.result.sales+'</strong></em>';
						html+='	</div>';
						html+='</div>';
						html+='<p class="grayline"></p>';
						html+='<div class="exp-text">';
						html+='	<p class="title">商品详情</p>';
						html+='	<span>'+data.result.content+'</span>';
						html+='</div>';
						html+='<a href="WxpayAPI/apiPay/place_order.php?type=0" class="submit">立即购买</a>';
						
						$(".container").html(html);
						
						winW=$(window).width();
						$(".banner img").height(winW);
					}
				}
			});
		</script>
	</body>
</html>