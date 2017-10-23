<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>个人中心</title>
		<link rel="stylesheet" href="css/common.css">	
	    <link rel="stylesheet" href="css/personal_center.css">
	    	
	</head>
	<body>
		<?php include 'header.php' ?>
		<div class="container">
			<div class="banner"></div>
	    	<div class="content">
	    		<div class="chooseTab left">
	    			<ul>
	    				<li class="first choosen">
	    					<a><i class="one"></i><span>我的病历</span></a>
	    				</li>
	    				<li>
	    					<a><i class="two"></i><span>服务记录</span></a>
	    				</li>
	    				<li>
	    					<a><i class="three"></i><span>咨询记录</span></a>
	    				</li>
	    				<li>
	    					<a><i class="four"></i><span>商品订单</span></a>
	    				</li>
	    				<li>
	    					<a><i class="five"></i><span>消息</span></a>
	    				</li>
	    				<li>
	    					<a><i class="six"></i><span>个人资料</span></a>
	    				</li>
	    				<li>
	    					<a><i class="seven"></i><span>申请会员</span></a>
	    				</li>
	    			</ul>
	    		</div>
	    		<div class="chooseShow left">
	    			<iframe src="personal/1.html" id="ifrm" name="ifrmname" frameborder="0" width="100%" scrolling="no"></iframe>
	    		</div>
	    		<div class="clearfix"></div>
	    	</div>
	    	<!--授权码弹窗，子页面触发，父页面调用，默认不显示-->
	    	<div id="layer-content">
	    		<p class="p1">授权码</p>
	    		<div class="center">
	    			<img src="images/personal-center/right.png">
	    			<p>已经为您生成授权码</p>
	    			<div id="shokeCode"><span></span><img src="images/personal-center/refresh.png" onclick="generate()"></div>	
	    		</div>
	    		<p class="p2">您的所有病历已生成为授权码<br />您可以将它分享给您的主治医师</p>
	    		<div class="share">快去分享给专家吧</div>
	    	</div>
	    </div>
		
		<?php include 'footer.php' ?>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
		<script type="text/javascript" src="js/layer/layer.js"></script>
	    <script>
	    	queue=$_GET['queue'];
	    	switch(queue){
	    		case '2':
	    			$('.chooseTab li').eq(1).addClass('choosen');
	    			$('.chooseTab li').eq(1).siblings().removeClass('choosen');
	    			$('.chooseShow iframe').attr('src','personal/2.html'); 
	    			break;
	    		case '4':
	    			$('.chooseTab li').eq(3).addClass('choosen');
	    			$('.chooseTab li').eq(3).siblings().removeClass('choosen');
	    			$('.chooseShow iframe').attr('src','personal/4.html');  
	    			break;
	    	}
	    	sessionArr = getCookie('sessionArr');
    		$('.chooseTab li').click(function(){
		    	$(this).addClass('choosen');
		    	$(this).siblings().removeClass('choosen');
		    	index=$(this).index();
				$('.chooseShow iframe').attr('src','personal/'+(index+1)+'.html');
		    });
		    
			function tips(){
				layer.open({
				  	type: 1,
				  	title: false,
				  	closeBtn: 1,
				  	shadeClose: true,
				  	skin: 'yourclass',
				  	area: ['640px','449px'],
				  	content: $('#layer-content')
				});
				generate();
			}
			//生成授权码
			function generate(){
				sessionArr = getCookie('sessionArr');
            	if(!sessionArr){
		    		layer.msg('该用户登陆数据已过期，请重新登陆',{time:1000},function(){
		    			location.href='login.php';
		    		});
		    	}else{
					$.ajax({
				    	type:'POST',
				    	url:commonUrl + 'api/stj/auth_code'+versioninfo,
				    	data:{
				    		'ss':JSON.parse(sessionArr).session,
				    		'type':1
				    	},
				    	success:function(data){
		//			    		console.log(data);
		                    if(data.code == 1){
		                    	code=data.result.code;
		                    	$('#shokeCode>span').html(code);
		                  	}else if(data.code=='1011'){
		                  		layer.msg('该用户登陆数据已过期，请重新登陆');
		                  		setTimeout("removeCookie('sessionArr');location.href='login.php'",1000);
		                  	}else{
		                  		layer.msg(data.msg);
		                  	}
		                }
		            });
		       	}
			}
			function tologin(){
				location.href='../login.php';
			}
			
			function reloadFrame(){
				document.getElementById('ifrm').contentWindow.location.reload(true);
			}
			function removec(){
				removeCookie('sessionArr');
			}
	    </script>
	</body>
</html>
