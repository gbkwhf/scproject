<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>我的</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/home_list.css" />
	</head>
	<body>
		<div class="wrapper">
			<div class="banner">
				<div class="head-portrait">
					<!--<img src="image/head-phone.jpg" class="head-photos" style="display: none;" />-->
					<!--<img src="image/boy.png" class="boy" style="display: none;" />
					<img src="image/girl.png" class="girl" style="display: none;" />-->
				</div>
				<div class="user-info">
					<!--<p onclick="linknext()">王静怡</p>
					<a href="javascript:;" onclick="linknext()">点击设置</a>-->
				</div>
			</div>
			<div class="container">
				<div class="border-box">
					<div class="form-box judge" onclick="location.href='illness_list.php'">
						<div class="icon">
							<img src="image/wdbl.png" width="13" style="padding-top: 20px;" />
						</div>					
						<p>我的病历</p>
						<img src="image/arrow-right.png" class="arrow" />
					</div>
				</div>				
				<p class="grayline"></p>
				<div class="border-box">
					<div class="form-box judge" onclick="location.href='service_record.php'">
						<div class="icon">
							<img src="image/fwjl.png" width="14" style="padding-top: 22px;" />
						</div>					
						<p>服务记录</p>
						<img src="image/arrow-right.png" class="arrow" />
					</div>
					<div class="form-box judge" onclick="location.href='consult_record.php'">
						<div class="icon">
							<img src="image/zxjl.png" width="14" style="padding-top: 21px;" />
						</div>					
						<p>咨询记录</p>
						<img src="image/arrow-right.png" class="arrow" />
					</div>
				</div>
				<p class="grayline"></p>
				<div class="border-box">
					<div class="form-box judge" onclick="location.href='order_list.php'">
						<div class="icon">
							<img src="image/spdd.png" width="15" style="padding-top: 21.5px;" />
						</div>					
						<p>商品订单</p>
						<img src="image/arrow-right.png" class="arrow" />
					</div>
				</div>
				<p class="grayline"></p>
				<div class="border-box">
					<div class="form-box judge" onclick="location.href='information.php'">
						<div class="icon">
							<img src="image/xx.png" width="15" style="padding-top: 21.5px;" />
						</div>					
						<p>消息</p>
						<img src="image/arrow-right.png" class="arrow" />
					</div>
				</div>
				<p class="grayline"></p>
				<div class="border-box">
					<div class="form-box" onclick="location.href='news.php'">
						<div class="icon">
							<img src="image/jkzx.png" width="14" style="padding-top: 22.5px;" />
						</div>					
						<p>健康资讯</p>
						<img src="image/arrow-right.png" class="arrow" />
					</div>
				</div>
				<p class="grayline"></p>
				<div class="border-box">
					<div class="form-box judge" onclick="location.href='apply_vip.php'">
						<div class="icon">
							<img src="image/sqhy.png" width="15" style="padding-top: 22.5px;" />
						</div>					
						<p>申请会员</p>
						<img src="image/arrow-right.png" class="arrow" />
					</div>
					<div class="form-box" onclick="location.href='vip_introduce.php'">
						<div class="icon">
							<img src="image/sqhz.png" width="14" style="padding-top: 21px;" />
						</div>					
						<p>申请合作</p>
						<img src="image/arrow-right.png" class="arrow" />
					</div>
				</div>
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
					<li onclick="location.href='medical_service_list.php'">
						<div class="pictures-box">
							<img src="image/ylfw-icon.png" style="display: none;" />
							<img src="image/ylfw-icons.png" />
						</div>
						<p>医疗服务</p>
					</li>
					<li class="active" onclick="location.href='home_list.php'">
						<div class="pictures-box">
							<img src="image/mine-icon.png" />
							<img src="image/mine-icons.png" style="display: none;" />
						</div>
						<p>我的</p>
					</li>
				</ul>
			</div>
		</div>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
		<script type="text/javascript" src="js/layer/layer.js"></script>
	    <script>
			
			$(".icon-box li").click(function(){
				
				$(this).siblings("li.active").children(".pictures-box").children("img:first").css("display","none");
				$(this).siblings("li.active").children(".pictures-box").children("img:last").css("display","block");
				$(this).siblings("li.active").removeClass("active");
				$(this).addClass("active");
				$(this).children(".pictures-box").children("img:first").css("display","block");
				$(this).children(".pictures-box").children("img:last").css("display","none");
			});
			//个人信息
				session = getCookie('session');
				session = session.substr(1,session.length-2);
				console.log(session);
				if(!session){
					pichtml="";
					infohtml="";
					pichtml+='	<img src="image/smile.png" class="head-photo" onclick="login()" />';
					$(".head-portrait").html(pichtml);
					infohtml+='		<a href="javascript:;" onclick="login()">去登录</a>';
					$(".user-info").html(infohtml);
					
				}else{
					$.ajax({
						type:"post",
						url:commonUrl+'api/stj/user/avatar'+versioninfo,
						data:{
							'ss':session
						},
						success:function(data){
							if(data.code==1){
								console.log(data);
								html="";
								if(data.result==""){
									html+='	<img src="image/smile.png" class="head-photo" onclick="linknext()" />';
									$(".head-portrait").html(html);
								}else{
									html+='	<img src="'+data.result+'" class="head-photo" onclick="linknext()" />';
									$(".head-portrait").html(html);
								}
							}else if(data.code==1011){
								layer.msg('该用户登陆数据已过期，请重新登陆');
								removeCookie('session');
		                    	setTimeout("location.href='sign_in.php'",1000);
							}else{
								layer.msg(data.msg);
							}
						}
					});
					
					$.ajax({
						type:"post",
						url:commonUrl+'api/stj/user/profile'+versioninfo,
						data:{
							'ss':session
						},
						success:function(data){
							if(data.code==1){
								console.log(data);
								personal=localStorage.setItem("personal",JSON.stringify(data));
								username=data.result[0].name;
								html="";
								if(username==null){		
									html+='<p onclick="linknext()">未设置</p>';					
									html+='<a href="javascript:;" onclick="linknext()">点击设置</a>';							
								}else{
									html+='<p onclick="linknext()">'+username+'</p>';
									html+='<a href="javascript:;" onclick="linknext()">点击设置</a>';
								}
								$(".user-info").html(html);
								
							}else if(data.code==1011){
								layer.msg('该用户登陆数据已过期，请重新登陆');
		                    	setTimeout("location.href='sign_in.php'",1000);
							}else{
								layer.msg(data.msg);
							}
						}
					});
					
				}				
			
			
			$(".judge").click(function(){
				session = getCookie('session');
				session = session.substr(1,session.length-2);
				console.log(session);
				if(!session){
					location.href='sign_in.php'
				}
			})
			
			function linknext(){
				location.href='personal_information.php'
			}
			
			function login(){
				location.href='sign_in.php'
			}
		</script>
		<style>
	        .layui-layer{
	            left:0;
	        }
	        .ui-loader{
	            display: none;
	        }
	    </style>
	</body>
</html>