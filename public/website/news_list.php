<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>健康资讯</title>
		<link rel="stylesheet" href="css/jquery.page.css">
		<link rel="stylesheet" href="css/common.css">
	    <link rel="stylesheet" href="css/news_list.css">
	</head>
	<body>
		<?php include 'header.php' ?>
		<div class="wrapper">
			<div class="banner"></div>
			<div class="content">
				<p class="totaltit">健康资讯<span>HEALTH INFORMATION</span></p>
				<ul class="newsList"></ul>
				<!--显示页码-->
				<div id="page" style="visibility: hidden;"></div>
			</div>
		</div>
		<?php include 'footer.php' ?>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
		<script type="text/javascript" src="js/layer/layer.js"></script>
		<script type="text/javascript" src="js/jquery.page.js"></script>
		<script>
			$('#tab>li:first-child').addClass('active');
			news_page=0;
	    	layer.load(2);
	    	//加载首页数据
		    $.ajax({
		    	type:'POST',
		    	url:commonUrl + 'api/stj/list/newlist'+versioninfo,
		    	async:false,
		    	success:function(data){
		    		layer.closeAll();
//		    		console.log(data);
                    if(data.code == 1){
                    	if(data.result.list.length!=0){
                    		news_page=Math.ceil(data.result.count/10);
                    		$('#page').css('visibility','visible');
                    		html='';
							for(var i=0;i<data.result.list.length;i++){
								html+='<li>';
								html+='	<img src="'+data.result.list[i].image+'">';
								html+='	<div class="articont">';
								html+='		<p class="title">'+data.result.list[i].title+'</p>';
								html+='		<div class="profile">'+data.result.list[i].content+'</div>';
								html+='		<div class="more" onclick="todetail('+data.result.list[i].id+')"><img src="images/index/more.png"></div>';
								html+='	</div>';
								html+='</li>';
							}
							$('.newsList').html(html);
							//新闻内容字数超出限定的字符数，以省略号显示
						    $('.profile').each(function(){
						    	var str=$(this).text();
						    	var s=cutString(str,50);
						    	$(this).text(s);
						    });
						    $('.title').each(function(){
						    	var str=$(this).text();
						    	var s=cutString(str,52);
						    	$(this).text(s);
						    });
                    	}else{
                    		layer.msg('暂无数据');
                    	}
                  	}else{
                  		layer.msg(data.msg);
                  	}
                }
            });
			$("#page").Page({
		        totalPages: news_page,
		        liNums: 7,
		        activeClass: 'activP', 
		        callBack : function(page){
//		          	console.log(page);
		          	ajaxrequest(page);
		        }
      		});
      		function ajaxrequest(num){
      			layer.load(2);
				$.ajax({
			    	type:'POST',
			    	url:commonUrl + 'api/stj/list/newlist'+versioninfo,
			    	data:{
			    		'page':num
			    	},
			    	success:function(data){
			    		layer.closeAll();
//			    		console.log(data);
	                    if(data.code == 1){
	                    	shows(data);
	                  	}else{
	                  		layer.msg(data.msg);
	                  	}
	                }
	           	});
			}
			function shows(obj){
				html='';
				for(var i=0;i<obj.result.list.length;i++){
					html+='<li>';
					html+='	<img src="'+obj.result.list[i].image+'">';
					html+='	<div class="articont">';
					html+='		<p class="title">'+obj.result.list[i].title+'</p>';
					html+='		<div class="profile">'+obj.result.list[i].content+'</div>';
					html+='		<div class="more" onclick="todetail('+obj.result.list[i].id+')"><img src="images/index/more.png"></div>';
					html+='	</div>';
					html+='</li>';
				}
				$('.newsList').html(html);
				//新闻内容字数超出限定的字符数，以省略号显示
			    $('.profile').each(function(){
			    	var str=$(this).text();
			    	var s=cutString(str,50);
			    	$(this).text(s);
			    });
			    $('.title').each(function(){
			    	var str=$(this).text();
			    	var s=cutString(str,52);
			    	$(this).text(s);
			    });
		    	
			}
			//跳转至新闻详情页
			function todetail(artid){
				location.href='news_detail.php?artid='+artid;
			}
			function cutString(str, len) {
				//length属性读出来的汉字长度为1
				if(str.length*2 <= len) {
				  return str;
				}
				var strlen = 0;
				var s = "";
				for(var i = 0;i < str.length; i++) {
					s = s + str.charAt(i);
				if (str.charCodeAt(i) > 128) {
					strlen = strlen + 2;
				    if(strlen >= len){
				    return s.substring(0,s.length-1) + "......";
					}
				} else {
				   strlen = strlen + 1;
				   if(strlen >= len){
				    return s.substring(0,s.length-2) + "......";
				   }
				  }
				}
				return s;
			}
		</script>
	</body>
</html>
