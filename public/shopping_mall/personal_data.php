<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>个人资料</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"><meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
    <style>
        body{
        	background: #f3f5f7;
        }
    	.container{
    		width:100%;
    			
    	}
    	.container>ul{
    		height:56px;
    		line-height:56px ;
    		padding:0 28px ;
    		margin-top:10px;
    		border-top:1px solid #e6e6e6;
    		border-top-left-radius:8px ;
    		border-top-right-radius:8px;
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
		<ul onclick="location.href='update_data.php'">
			<li>
				<p>用户名称</p>
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
              $(".container>ul>li>span").html(data.result.name);
              setCookie("username",data.result.name);
            }else{
                layer.msg(data.msg);
            }
        }
    });
		
	
</script>

</html>