<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>增值服务</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>		
		<link rel="stylesheet" type="text/css" href="css/consult_record.css" />
	</head>
	<body>
		<div class="container">
			<ul class="health-list">
				<!--<li>
					<span class="title">健康体检</span>
					<span class="arrow"><img src="image/arrow-right.png"></span>
				</li>
				<li>
					<span class="title">心理测评</span>
					<span class="arrow"><img src="image/arrow-right.png"></span>
				</li>-->
			</ul>
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script>
		
						
			$.ajax({
				type:"post",
				url:commonUrl+'api/stj/service_order/order_list'+versioninfo,
				data:{
					'type':4
				},
				success:function(data){
					if(data.code==1){
						console.log(data);
						if(data.result.list.length!=0){
							datas=localStorage.setItem("datas",JSON.stringify(data));
							html="";
							for(var i=0;i<data.result.list.length;i++){
								
								html+='<li onclick="sid('+data.result.list[i].id+','+i+')">';
								html+='	<span class="title">'+data.result.list[i].title+'</span>';
								html+='	<span class="arrow"><img src="image/arrow-right.png"></span>';
								html+='</li>';
								
							}
							$(".container ul").html(html);
						}else{
							html="";
							html+='<p class="none">暂无服务</p>';
							$(".container").html(html);
							
							winH=$(window).height();
							$(".none").css("top",winH/2-62);
						}
						
					}
				}
			})
			function sid(sid,tit){
				location.href="WxpayAPI/apiPay/health_management_details.php?id="+sid+"&name="+tit;
			}
		</script>
	</body>
</html>