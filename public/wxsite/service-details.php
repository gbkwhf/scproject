<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>服务详情</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/service-details.css" />
	</head>
	<body>
		<div class="container">
			<!--<div class="border-box">
				<p>服务类型</p>
				<div class="right-box">
					<span>夏天</span>
				</div>
			</div>
			<div class="border-box">
				<p>服务时间</p>
				<div class="right-box">
					<span>夏天</span>
				</div>
			</div>
			<div class="border-box">
				<p>申请时间</p>
				<div class="right-box">
					<span>夏天</span>
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
					<span>夏天</span>
				</div>
			</div>
			<div class="border-box">
				<p>反馈时间</p>
				<div class="right-box">
					<span>夏天</span>
				</div>
			</div>
			<div class="border-box">
				<p>备注</p>
				<div class="right-box">
					<span>夏天</span>
				</div>
			</div>-->
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script>
		
			session = getCookie('session');
			session=session.substr(1,session.length-2);
		
			order_id=$_GET['id'];
			state=$_GET['state'];
			console.log(order_id);
			console.log(session);
			$.ajax({
				type:"post",
				url:commonUrl+'api/stj/service_order/order_info'+versioninfo,
				data:{
					'order_id':order_id,
					'ss':session
				},
				success:function(data){
					if(data.code==1){
						console.log(data);
						html="";
						if(data.result.service_type=="预约挂号"||data.result.service_type=="住院治疗"){
							html+='<div class="border-box">';
							html+='	<p>服务类型</p>';
							html+='	<div class="right-box">';
							html+='		<span>'+data.result.service_type+'</span>';
							html+='	</div>';
							html+='</div>';
							html+='<div class="border-box">';
							html+='	<p>服务时间</p>';
							html+='	<div class="right-box">';
							html+='		<span>'+data.result.intend_time.substr(0,10)+'</span>';
							html+='	</div>';
							html+='</div>';
						}else{
							html+='<div class="border-box">';
							html+='	<p>服务类型</p>';
							html+='	<div class="right-box">';
							html+='		<span>'+data.result.service_type+'</span>';
							html+='	</div>';
							html+='</div>';
						}
						
						html+='<div class="border-box">';
						html+='	<p>申请时间</p>';
						html+='	<div class="right-box">';
						html+='		<span>'+data.result.created_at+'</span>';
						html+='	</div>';
						html+='</div>';
						html+='<div class="describe">';
						html+='	<p>描述</p>';
						if(data.result.service_content==""||data.result.service_content==null){
							html+='<span style="border:1px solid #ccc">无</span>';
						}else{
							html+='	<span style="border:1px solid #ccc">'+data.result.service_content+'</span>';	
						}
								
						html+='</div>';
						if(state!=0){
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
							html+='	<p>反馈内容</p>';
							html+='	<div class="right-box">';
							html+='		<span>'+data.result.manage_content+'</span>';
							html+='	</div>';
							html+='</div>';
						}
						
						$(".container").html(html);
					}else if(data.code==1011){
						layer.msg('该用户登陆数据已过期，请重新登陆');
	                	setTimeout("location.href='sign_in.php'",1000);
					}else{
						layer.msg(data.msg)
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