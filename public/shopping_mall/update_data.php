<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>更改名称</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"><meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
    <style>
        body{
        	background: #f3f5f7;
        }
        .nickname{
        	height:54px;
        	line-height:54px ;
        	margin-top: 11px;
        	background: #fff;
        	padding: 0px 26px;
        	border-top:1px solid #e6e6e6;
        	border-bottom: 1px solid #e6e6e6;
        }
        
        .nickname>li>input{
        	border:none ;
        	outline: none;
        	color:#333333;
        	height:30px;
        	line-height: 30px;
        	font-size: 15px;
        	width: 100%;
        }
        .save_nickname{
        	background: #59c7ae;
        	color:#fff;
        	font-size: 15px;
        	display: block;
        	width:80%;
        	margin: 0 auto;
        	text-align: center;
        	border-radius:20px;
        	height:45px;
        	line-height: 45px;
        	margin-top:70px;
        }
    </style>
</head>

<body>	
	<ul class="nickname">
		<li>
			<input type="text"  class="name" onkeyup="this.value=this.value.replace(/\s/g, '')" autofocus="autofocus" val="" />
		</li>
	</ul>
	<a href="javascript:void(0)" class="save_nickname">保存</a>
</body>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script>
    var name=getCookie("username");
    $(".name").val(name);
   
    $(".save_nickname").click(function(){
    	var name=$(".name").val();
    	if(name==''){
    		layer.msg("请输入要修改的名称");
    	}else{
	    	$.ajax({
	        type:"post",
	        url:commonsUrl + 'api/gxsc/user/update/updateProfile' + versioninfos,
	        data:{
	        	"name":name,
	            'ss':getCookie('openid')
	        },
	        success:function(data){
	            if(data.code==1){
	              console.log(data);
	              layer.msg("保存成功");
	              setInterval(function(){
	              	 location.href="personal_center.php"
	              },1000)
	            }else{
	                layer.msg(data.msg);
	            }
	        }
	      
	    	});
      }
    	
    });
	 
		
	
</script>

</html>