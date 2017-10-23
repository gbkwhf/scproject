<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>医疗介绍</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/hospital-introduction.css" />
	</head>
	<body>
		<div class="container">
			<!--<div class="banner">
				<img src="image/hospital-banner.png" />
				<div class="banner-box">
					<p class="banner-txt">陕西省人民医院</p>					
					<div class="banner-text"><span>——</span>&nbsp;&nbsp;三级甲等&nbsp;&nbsp;<span>——</span></div>					
				</div>
			</div>
			<div class="center">
				<div class="tit">
					医院介绍
					<span>HOSPITAL&nbsp;INTRODUCTION</span>
				</div>
				<div class="explain-text">陕西省人民医院是陕西省人民政府创办的大型综合性三级甲等医院，也是西安交通大学医学院第三附属医院，陕西省临床医学研究院，国家药物</div>
			</div>-->
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script>
			aid=$_GET['id'];
			console.log(aid);
			
			dataret=JSON.parse(localStorage.getItem("dataret"));
			console.log(dataret);
			html="";
			for(var i=0;i<dataret.result.length;i++){
				dataid=dataret.result[i].id;
				if(dataid==aid){
					html+='<div class="banner">';
					html+='<div class="bg-color"></div>';
					html+='	<img src="'+dataret.result[i].logo+'" />';
					html+='	<div class="banner-box">';
					html+='		<p class="banner-txt">'+dataret.result[i].name+'</p>	';				
					html+='		<div class="banner-text"><span>——</span>&nbsp;&nbsp;'+dataret.result[i].grade+'&nbsp;&nbsp;<span>——</span></div>';					
					html+='	</div>';
					html+='</div>';
					html+='<div class="center">';
					html+='	<div class="tit">';
					html+='		医院介绍';
					html+='		<span>HOSPITAL&nbsp;INTRODUCTION</span>';
					html+='	</div>';
					html+='	<div class="explain-text">'+dataret.result[i].content+'</div>';
					html+='</div>';
					
					$(".container").html(html);
				}
			}

		</script>
	</body>
</html>