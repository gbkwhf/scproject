<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>共享商城</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"><meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <div class="wrapper">
        <div class="module-box" onclick="location.href='member_zone.php'">
            <div class="pic-module">				
                <img src="images/shopping-pic.jpg" width="100%"/>
                <span></span>
            </div>
            <h4>购物商城</h4>
        </div>
        <div class="module-box" onclick="location.href='personal_center.php'">
            <div class="pic-module">				
                <img src="images/personal-center.jpg" width="100%"/>
                <span></span>
            </div>
            <h4>个人中心</h4>
        </div>
    </div>
    <!------------弹出层------------>
    <div class="popBox" style="display: none;">
    	<div class="close"></div>
    	<div class="favourable" onclick="location.href='favourable.php'">
    		<img src="images/tankBg.png"/>
    	</div>
    </div>
    
</body>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script>

    //权限请求

    //获取openid
    $.ajax({
        type:"get",
        url: commonsUrl + "api/gxsc/get/user/openId" +versioninfos,
        data:{
            "code":$_GET['code']
        },success:function(data){
            if(data.code==1){
//                if(getCookie("openid")){
//                    setCookie("is_member",data.result.is_member);
//                    checkBind();
//                }else{
//                    setCookie("openid",data.result.openId);
//                    setCookie("is_member",data.result.is_member);
//                    checkBind();
//                }
                setCookie("openid",data.result.openId);
                setCookie("is_member",data.result.is_member);
                checkBind();
            }else{
                layer.msg(data.msg);
            }
        }
    });

	
	function checkBind(){
		//验证openid是否绑定
		$.ajax({
			type:'post',
			url: commonsUrl + 'api/gxsc/scan/this/phone/bind/openId' +versioninfos,
			data:{'openId':getCookie("openid")},
			success:function(data){
				if(data.code==1){
					console.log(data);
					if(data.result.state==0){ //未绑定
						location.href = 'register.php';
					}else if(data.result.state==1){ //已绑定
						
					}
				}else{
                    layer.msg(data.msg);
                }
            }
        })
    }
//  $(function(){
//  	$(".popBox").show();
//  	$(".close").click(function(){
//  		$(".popBox").hide();
//  	})
//  })
</script>
<style type="text/css">
    .layui-layer.layui-anim.layui-layer-page{
        border-radius: 5px;
    } 
</style>
</html>