<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>产业医生</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/doctor_details.css" />
	</head>
	<body>
		<div class="container">
			<figure>
				<!--<figcaption>详细介绍</figcaption>-->
				<div class="cont-box">
					<!--<p>康管理是以预防和控制疾病发生与发展，降低医疗 费用，提高生命质量为目的，针对个体及群体进行 健康管理教育，提高自我管理意识和水</p>-->
				</div>			
			</figure>
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script>
			winW=$(window).width();
			$("#remarks").width(winW-75);
			$.ajax({
				type:"post",
				url:commonUrl+'api/stj/service_order/order_list'+versioninfo,
				data:{
					'type':3
				},success:function(data){
					if(data.code==1){
						console.log(data);
						html="";
						html+='<p>'+data.result.list[0].content+'</p>';
						$(".cont-box").html(html);
					}
				}
			});
		</script>
	</body>
</html>