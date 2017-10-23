<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>医疗服务</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>		
		<link rel="stylesheet" type="text/css" href="css/consult_record.css" />
	</head>
	<body>
		<div class="container">
			<div class="banner" onclick="location.href='medical_institution.php'">
				<img src="image/hospital-banner.png" class="banner-bg" />
				<div class="ban-cont">
					<p>全球医疗机构</p>
					<div class="into">
						<em>——</em>
						<span>点击进入</span>
						<em>——</em>
					</div>					
				</div>
			</div>
			<ul class="health-list" style="padding-bottom: 74px;">
				<!--<li>
					<span class="title">健康体检</span>
					<span class="arrow"><img src="image/arrow-right.png"></span>
				</li>
				<li>
					<span class="title">心理测评</span>
					<span class="arrow"><img src="image/arrow-right.png"></span>
				</li>-->
			</ul>
			<ul class="icon-box">
				<li onclick="location.href='index.php'">
					<div class="pictures-box">
						<img src="image/stj-icon.png" style="display: none;" />
						<img src="image/stj-icons.png" />
					</div>
					<p>萃怡家</p>
				</li>
				<li onclick="location.href='health_management_list.php'">
					<div class="pictures-box">
						<img src="image/jkgl-icon.png" style="display: none;" />
						<img src="image/jkgl-icons.png" />
					</div>
					<p>健康管理</p>
				</li>
				<li class="active" onclick="location.href='medical_service_list.php'">
					<div class="pictures-box">
						<img src="image/ylfw-icon.png" />
						<img src="image/ylfw-icons.png" style="display: none;" />
					</div>
					<p>医疗服务</p>
				</li>
				<li onclick="location.href='home_list.php'">
					<div class="pictures-box">
						<img src="image/mine-icon.png" style="display: none;" />
						<img src="image/mine-icons.png" />
					</div>
					<p>我的</p>
				</li>
			</ul>
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script>

				$.ajax({
					type:"post",
					url:commonUrl+'api/stj/service_order/order_list'+versioninfo,
					data:{
						'type':7
					},
					success:function(data){
						if(data.code==1){
							console.log(data);
							datas=localStorage.setItem("datas",JSON.stringify(data));
							html="";
							for(var i=0;i<data.result.list.length;i++){
								if(data.result.list[i].open_type==2){
									html+='<li onclick="yyid()">';
									html+='	<span class="title">'+data.result.list[i].title+'</span>';
									html+='	<span class="arrow"><img src="image/arrow-right.png"></span>';
									html+='</li>';
								}else if(data.result.list[i].open_type==3){
									html+='<li onclick="yyids()">';
									html+='	<span class="title">'+data.result.list[i].title+'</span>';
									html+='	<span class="arrow"><img src="image/arrow-right.png"></span>';
									html+='</li>';
								}else{
									html+='<li onclick="sid('+data.result.list[i].id+','+i+')">';
									html+='	<span class="title">'+data.result.list[i].title+'</span>';
									html+='	<span class="arrow"><img src="image/arrow-right.png"></span>';
									html+='</li>';
								}							
							}
							$(".container .health-list").html(html);
						}
					}
				})
			function sid(sid,tit){
				location.href="WxpayAPI/apiPay/health_management_details.php?id="+sid+"&name="+tit;
			}
			function yyid(){
				location.href="register.php";				
			}
			function yyids(){
				location.href="remedy.php";				
			}
		</script>
	</body>
</html>