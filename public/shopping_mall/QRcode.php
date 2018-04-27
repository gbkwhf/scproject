<!DOCTYPE html>
<html>

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="css/common.css">
		<meta charset="UTF-8">
		<title>二维码名片</title>
	</head>
	<style type="text/css">
		.box{
			background: rgba(0,0,0,0.5);
			width: 100%;
			overflow: hidden;
		}
		.box>div{
			width: 92%;
			margin: 32.5px auto;
			background: #fff;
			border-radius: 5px;
			padding-top: 29px;
			padding-bottom: 60px;
		}
		.box>div>p{
			text-align: center;
			color: #999;
		}
		.head{
			margin-left: 30px;
			display: flex;
			align-items: center;
		}
		.head img{
			width: 55px;
			height: 55px;
			margin-right: 13px;
		}
		.QRcode{
			width: 66%;
			margin: 10px auto;
			display: block;
		}
	</style>
	<body>
		<div class="box">
			<div>
				<div class="head">
					<img src="images/userImg1.png"/>
					<span>盛夏</span>
				</div>
				<div class="QRcode"> 
					<!-- <img src="images/code.png"/> -->
				</div>
				<p>
					扫一扫二维码图案，接受我的邀请<br />进入双创共享商城
				</p>
			</div>
		</div>
	</body>

</html>
<script src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/config.js"></script>
<script src="https://cdn.bootcss.com/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
<script type="text/javascript">
	let height=$(document).height()
	$(".box").css("height",height)

	$(".QRcode").qrcode({
			render : "canvas",    //设置渲染方式，有table和canvas，使用canvas方式渲染性能相对来说比较好
            text : commonsUrl+"shopping_mall/index.php",    //扫描二维码后显示的内容,可以直接填一个网址，扫描二维码后自动跳向该链接
			width : "230",            // //二维码的宽度
			height : "230",              //二维码的高度
			background : "#ffffff",       //二维码的后景色
			foreground : "#000000",        //二维码的前景色
			 // src: 'images/shopImage.png'           二维码中间的图片
	})
</script>