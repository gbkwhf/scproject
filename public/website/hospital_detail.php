<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>医院详情</title>
		<link rel="stylesheet" href="css/common.css">
	    <link rel="stylesheet" href="css/hospital_detail.css">
	</head>
	<body>
		<?php include 'header.php' ?>
		<div class="wrapper">
			<div class="content">
				<!--<div class="banner"></div>
				<div class="title">
					<p class="bigtit">陕西省人民医院</p>
					<p class="subtit"><img src="images/medical/line.png">三级甲等医院 <img src="images/medical/line.png"></p>
					<span class="hospro">医院简介</span>
				</div>
				<div class="artext">陕西省人民医院陕西省人民医院陕西省人民医院陕西省人民医院陕西省人民医院陕西省人民医院</div>-->
			</div>
		</div>
		<?php include 'footer.php' ?>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
		<script type="text/javascript" src="js/layer/layer.js"></script>
		<script>
			$('#tab>li:eq(3)').addClass('active');
			hosid = $_GET['hosid'];
			hos_detail = JSON.parse(localStorage.getItem("hos_detail"));
//			console.log(hos_detail);
			for(var i=0;i<hos_detail.length;i++){
				if(hos_detail[i].id==hosid){
					html='';
					if(hos_detail[i].logo){
						logo=hos_detail[i].logo;
						html+='<div class="banner" style="background-image:url('+logo+')"></div>';
					}
					html+='<div class="title">';
					html+='	<p class="bigtit">'+hos_detail[i].name+'</p>';
					html+='	<p class="subtit"><img src="images/medical/line.png">'+hos_detail[i].grade+' <img src="images/medical/line.png"></p>';
					html+='	<span class="hospro">医院简介</span>';
					html+='</div>';
					html+='<div class="artext">'+hos_detail[i].content+'</div>';
					$('.wrapper .content').html(html);
				}
			}
		</script>
	</body>
</html>
