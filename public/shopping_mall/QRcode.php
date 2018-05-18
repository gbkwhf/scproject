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
<script src="js/layer/layer.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/config.js"></script>
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
			$("h1").text(data.result.deposit+"元")	
			$("button").click(function(){
					$.ajax({
					type:"post",
					url: commonsUrl + "api/gxsc/userapplyreturn" +versioninfos,
					data:{
					'ss':getCookie('openid')
					},
					success:function(data){
						console.log(data)
						if(data.code==1){
							location.href='deposit.php'
						}else{
							layer.msg(data.msg);
						}
					}
				});
			})
        }
    });
</script>