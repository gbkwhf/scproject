<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>二维码邀请</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    	<meta name="apple-mobile-web-app-capable" content="yes"><meta name="format-detection" content="telephone=no" />
   	 	<meta name="apple-mobile-web-app-status-bar-style" content="black">
    	<link rel="stylesheet" href="css/common.css">
	</head>
	<style type="text/css">
		body{
			overflow: auto;
		}
		.imgs{
			width: 100%;
			height: 100%;
			position: absolute;
		}
		.user_box{
			position: relative;
			width: 78%;
			height: 370px;
			left: 10%;
			top: 90px;
			border-radius: 8px;
			background-color: #fff;
		}
		.userImg{
			width: 70px;
			height: 70px;
			margin: 0 auto;
		}
		.userImg img{
			width: 68px;
			height: 69px;
			margin: 0 auto;
			margin-top: -30px;
			border-radius: 50%;
			
		}
		.invite_friends img{
			width: 100%;
			height: 119px;
		}
		.QRcodes{
			height: 126px;
			width: 126px;
			margin: 0 auto;
		}
		.QRcodes img{
			height: 126px;
			width: 126px;
		}
		.contents{
			text-align: center;
			color: #a0a0a0;
			padding-top: 5px;
		}
		canvas{
			height: 126px;
			width: 126px;
			margin: 0 auto;
		}
	</style>
	<body>
		<canvas class="inviteBox">
			<img src="images/inviteBj.png" width="100%" class="imgs"/>
			<div class="user_box">
				<div class="userImg"><img src="images/head-portrait.png"/></div>
				<div class="invite_friends"><img src="images/invite_friends.png"/></div>
				<div class="QRcodes"></div>
				<div class="contents">长按二维码识别</div>
			</div>
			
		</canvas>
	</body>
</html>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script src="./js/utf.js"></script>
<script src="./js/jquery.qrcode.js"></script>
<script type="text/javascript">
	var wHeight=$(window).height();
	$('.inviteBox').height(wHeight);
	 var user_id = $_GET['user_id'];
    var thumbnail_image_url=$_GET['thumbnail_image_url'];
    console.log(thumbnail_image_url);
   if (thumbnail_image_url != "") {
        $('.userImg img').attr('src', thumbnail_image_url);
    }
    $(".QRcodes").qrcode({
        render: "canvas",    //设置渲染方式，有table和canvas，使用canvas方式渲染性能相对来说比较好
        text: commonsUrl + "api/gxsc/invite/others/register?user_id=" + user_id,    //扫描二维码后显示的内容,可以直接填一个网址，扫描二维码后自动跳向该链接
        width: "200",               //二维码的宽度
        height: "200",              //二维码的高度
        background: "#ffffff",       //二维码的后景色
        foreground: "#000000",        //二维码的前景色
        src: './images/scLogo.png'    //二维码中间的图片
    })
</script>
