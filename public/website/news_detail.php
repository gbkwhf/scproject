<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>新闻详情</title>
		<link rel="stylesheet" href="css/common.css">
	    <link rel="stylesheet" href="css/news_detail.css">
	</head>
	<body>
		<?php include 'header.php' ?>
		<div class="wrapper">
			<div class="banner"></div>
			<div class="content">
				<!--<p class="now">首页&nbsp;&gt;&nbsp;新闻中心&nbsp;&gt;&nbsp;</p>
				<div class="title">
					<p class="bigtit">“猝死”屡见不鲜 揭秘导致猝死的原因</p>
					<p class="subtit">闽西新闻网 http://www.mxrb.cn2013-05-27 10:05  来源：北方网</p>
				</div>
				<div class="artext"></div>-->
			</div>
		</div>
		<?php include 'footer.php' ?>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
		<script type="text/javascript" src="js/layer/layer.js"></script>
		<script>
			$('#tab>li:first-child').addClass('active');
			artid = $_GET['artid'];
			$.ajax({
		    	type:'POST',
		    	url:commonUrl + 'api/stj/info/newinfo'+versioninfo,
		    	data:{
		    		'id':artid
		    	},
		    	success:function(data){
//		    		console.log(data);
                    if(data.code == 1){
                    	html='';
                    	html+='<p class="now">首页&nbsp;&gt;&nbsp;新闻中心&nbsp;&gt;&nbsp;'+data.result.title+'</p>';
                    	html+='<div class="title">';
                    	html+='	<p class="bigtit">'+data.result.title+'</p>';
                    	html+='	<p class="subtit">'+data.result.created_at+'</p>';
                    	html+='</div>';
                    	html+='<div class="artext">'+data.result.content+'</div>';
                    	$('.wrapper .content').html(html);
                  	}else{
                  		layer.msg(data.msg);
                  	}
                }
           	});
		</script>
	</body>
</html>
