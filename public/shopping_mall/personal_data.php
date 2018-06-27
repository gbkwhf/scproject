<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>个人信息</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"><meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
    <style>
        html{
        	padding-top:10px;
        }    	
        body{
        	background: #f3f5f7;       	
        	padding-bottom:30px;        	
        }
    	.container{   		
    		margin: 0 auto;		
    	}
    	.container>ul{
    		height:56px;
    		line-height:56px ;
    		padding:0 20px;
    		border-top:1px solid #e6e6e6;
    		border-top-left-radius:7px 25px ;
    		border-top-right-radius:7px 25px;
    		background:#fff;			
    	}
    	.container>ul>li{
    		width: 100%;
    		height:56px;
    		line-height:56px ;
			border-bottom: 1px solid #e6e6e6;
			display: flex;
			justify-content: space-between;
			align-items: center;
    	}
    	.container>ul>li>p{
    		font-size: 14px;
    		float:left;
    		color:#333;
    		font-weight: 600;
    	}
    	.container>ul>li>p:nth-child(2){
    	display: flex;
    	align-items: center;	
    	}
    	.container>ul>li>span{
    		float:right;
    		color:#999;
    		font-size:14px;	
    		text-align: right;	
    	}
    </style>
</head>

<body>
	<div class="container">		
		<ul>
			<li onclick="location.href='update_data.php'">
				<p>用户名</p>
				<span></span>
			</li>
			<li>
				<p>手机号</p>
				<span></span>
			</li>	
			<li class="code">
				<p>邀请码</p>
				<span>2345</span>
			</li>
			<li>
				<p>级别</p>
				<span class="level">666级</span>
			</li>
			<li>
				<p>状态</p>
				<span class="Open">开通</span>
			</li>

			<li class="send"> 
				<p>退回押金</p>
				<p>
					<img style="width: 7.5px;height: 13px;" src="images/right-arrow.png" alt="">
				</p>
			</li>	
				
		</ul>
	</div>
</body>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script>
	var windowH=$(window).height();
    $(".container>ul").height(windowH-40);
	var windowW= $(window).width();
	var pW=$(".container>ul>li>p").width();
	$(".container>ul>li>span").width(windowW-pW-71);
	$(".container>ul>li>span").css({
		"overflow":"hidden",
		"text-overflow":"ellipsis",
		"white-space": "nowrap"
		
	});
	$.ajax({
        type:"post",
        url: commonsUrl + "api/gxsc/user/profile" +versioninfos,
        data:{
           'ss':getCookie('openid')	
        },
        success:function(data){
            if(data.code==1){
              console.log(data);
              $(".container>ul>li").eq(0).children('span').html(data.result.name);
              $(".container>ul>li").eq(1).children('span').html(data.result.mobile);
			  $(".code span").text(data.result.user_id)
			  $(".level").text('LV'+data.result.user_lv)
			  $(".Open").text(data.result.invite_role==0?"未开通":"开通")
              setCookie("username",data.result.name);
			  if(data.result.invite_role==0){
				$(".code").hide()
				$(".send").hide()
			  }
            }else{
                layer.msg(data.msg);
            }

			$(".send").click(function(){
				if(data.result.deposit=="0.00"){
					location.href="deposit.php"
				}else{
					location.href='QRcode.php?user_lv=' + data.result.user_lv
				}
			})
        }
    });
		
	
</script>

</html>