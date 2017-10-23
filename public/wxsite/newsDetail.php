<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>资讯详情</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/news.css" />
	</head>
	<body>
		<div class="wrapper">
			<!--<div class="banner">
				<img src="image/news.jpg" />
				<p>标题标题标题标题标题标题标题标题标题标题标题标题标题标题</p>
			</div>
			<div class="newsContent">
				每个人都有一个梦，每个梦都有一扇窗，每扇窗都是一掬忘情的流年。奢望是一种无法解的蛊，魂牵梦绕着萦绕心间，在无数个日夜里，夜以继日，温着一句情调的词藻，等你徐行，等思念相逢……
			</div>-->
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script>
			aid=$_GET['id'];
			$.ajax({
				type:"post",
				url:commonUrl+'api/stj/info/newinfo'+versioninfo,
				data:{
					'id':aid
				},
				success:function(data){
					if(data.code=1){
						console.log(data);
						html="";
						html+='<div class="banner">';
						html+='	<img src="'+data.result.image+'" />';
						html+='	<p>'+data.result.title+'</p>';
						html+='</div>';
						html+='<div class="newsContent">'+data.result.content+'</div>';
						$(".wrapper").html(html);
					}
				}
			})
		</script>
	</body>
</html>