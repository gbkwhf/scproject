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
    	}
    	.container>ul>li>p{
    		font-size: 14px;
    		float:left;
    		color:#333;
    		font-weight: 600;
    		margin-right:15px;	
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
              setCookie("username",data.result.name);
            }else{
                layer.msg(data.msg);
            }
        }
    });
		
	
</script>

</html>