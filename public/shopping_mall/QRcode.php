<!DOCTYPE html>
<html>

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="css/common.css">
		<meta charset="UTF-8">
		<title>退回押金</title>
	</head>
	<style type="text/css">
		.box{
			width: 100%;
			overflow: hidden;
		}
		.box>div{
			width: 92%;
			margin: 32.5px auto;
			border-radius: 5px;
			padding-top: 29px;
			padding-bottom: 60px;
		}
		.box>div>h1{
			margin:15px auto
		}
		.box>div>p{
			font-size:22px
		}
		.box>div>span{
			color:#c94d10
		}
		.box>div>button{
			width:100%;
			height:40px;
			border:none;
			color:#FFF;
			background:#4d6dfc;
			border-radius: 5px;
			margin-top:300px
		}
	</style>
	<body>
		<div class="box">
			<div>
				<p>开通邀请时的押金</p>
				<h1>299元</h1>
				<span>提示 : 退回押金后既不能享受邀请好友且享受返利</span>

				<button>确认退回押金</button>
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

	$.ajax({
        type:"post",
        url: commonsUrl + "api/gxsc/user/profile" +versioninfos,
        data:{
           'ss':getCookie('openid')
        },
        success:function(data){
            if(data.code==1){
              console.log(data);
			  $(".head img").attr("src",data.result.thumbnail_image_url)
			  $(".head span").text(data.result.name)
			  $(".QRcode").qrcode({
				render : "canvas",    //设置渲染方式，有table和canvas，使用canvas方式渲染性能相对来说比较好
				text : commonsUrl+"shopping_mall/register.php?user_id="+data.result.user_id,    //扫描二维码后显示的内容,可以直接填一个网址，扫描二维码后自动跳向该链接
				width : "230",            // //二维码的宽度
				height : "230",              //二维码的高度
				background : "#ffffff",       //二维码的后景色
				foreground : "#000000",        //二维码的前景色
				src: data.result.thumbnail_image_url     //   二维码中间的图片
			})
            }else{
                layer.msg(data.msg);
            }
        }
    });

</script>