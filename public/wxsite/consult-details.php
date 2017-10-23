<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>咨询详情</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/service-details.css" />
	</head>
	<body>
		<div class="container">
			<!--<div class="border-box">
				<p>联系人</p>
				<div class="right-box">
					<span>王静怡</span>
				</div>
			</div>
			<div class="border-box">
				<p>联系电话</p>
				<div class="right-box">
					<span>18356977299</span>
				</div>
			</div>
			<div class="border-box">
				<p>咨询类型</p>
				<div class="right-box">
					<span>2016.02.06 12:30:20</span>
				</div>
			</div>
			<div class="describe">
				<p>描述</p>
				<span></span>				
			</div>
			<p class="gray-line"></p>
			<div class="border-box">
				<p>反馈人员</p>
				<div class="right-box">
					<span>张小龙</span>
				</div>
			</div>
			<div class="border-box">
				<p>反馈时间</p>
				<div class="right-box">
					<span>2016.02.06 12:30:20</span>
				</div>
			</div>
			<div class="border-box">
				<p>反馈内容</p>
				<div class="right-box">
					<span></span>
				</div>
			</div>-->
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script>
		
			session = getCookie('session');
			session=session.substr(1,session.length-2);
		
			order_id=$_GET['id'];
			console.log(order_id);
			console.log(session);
			$.ajax({
				type:"post",
				url:commonUrl+'api/stj/question/info'+versioninfo,
				data:{
					'id':order_id,
					'ss':session
				},
				success:function(data){
					if(data.code==1){
						console.log(data);
						html="";
						html+='<div class="border-box">';
						html+='	<p>联系人</p>';
						html+='	<div class="right-box">';
						html+='		<span>'+data.result.name+'</span>';
						html+='	</div>';
						html+='</div>';
						html+='<div class="border-box">';
						html+='	<p>手机号码</p>';
						html+='	<div class="right-box">';
						html+='		<span>'+data.result.mobile+'</span>';
						html+='	</div>';
						html+='</div>';
						html+='<div class="border-box">';
						html+='	<p>咨询类型</p>';
						html+='	<div class="right-box">';
						html+='		<span>'+data.result.type+'</span>';
						html+='	</div>';
						html+='</div>';
						html+='<div class="border-box">';
						html+='	<p>咨询时间</p>';
						html+='	<div class="right-box">';
						html+='		<span>'+data.result.created_at+'</span>';
						html+='	</div>';
						html+='</div>';
						html+='<div class="describe">';
						html+='	<p>描述</p>';
						html+='	<span style="border:1px solid #ccc;">'+data.result.content+'</span>';			
						html+='</div>';
						if(data.result.state!="未处理"){
							html+='<p class="gray-line"></p>';
							html+='<div class="border-box">';
							html+='	<p>反馈人员</p>';
							html+='	<div class="right-box">';
							html+='		<span>'+data.result.manage_name+'</span>';
							html+='	</div>';
							html+='</div>';
							html+='<div class="border-box">';
							html+='	<p>反馈时间</p>';
							html+='	<div class="right-box">';
							html+='		<span>'+data.result.manage_time+'</span>';
							html+='	</div>';
							html+='</div>';
							html+='<div class="border-box">';
							html+='	<p style="width:60px">反馈内容</p>';
							html+='	<div class="right-box fkcont">';
							if(data.result.manage_content==""){
								html+='		<span>暂无</span>';
							}else{
								html+='		<span>'+data.result.manage_content+'</span>';
							}
							
							html+='	</div>';
							html+='</div>';
						}
						$(".container").html(html);
						
						winW=$(window).width();
						$(".fkcont").width(winW-90);
					}else if(data.code==1011){
						layer.msg('该用户登陆数据已过期，请重新登陆');
	                	setTimeout("location.href='sign_in.php'",1000);
					}else{
						layer.msg(data.msg);
					}
				}
			});
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