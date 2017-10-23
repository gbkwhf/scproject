<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>健康商城</title>
		<link rel="stylesheet" href="css/common.css">
	    <link rel="stylesheet" href="css/health_mall.css">
	    <link rel="stylesheet" href="css/jquery.page.css">
	    <style>
	    	#page{
	    		position: absolute;
	    		bottom:0;
	    	}
	    </style>
	</head>
	<body>
		<?php include 'header.php' ?>
		<div class="wrapper">
			<div class="content">
				<div class="goods_show"></div>
				<div class="clearfix"></div>
				<div id="page" style="visibility: hidden;"></div>
			</div>
		</div>
		<?php include 'footer.php' ?>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
		<script type="text/javascript" src="js/layer/layer.js"></script>
		<script type="text/javascript" src="js/jquery.page.js"></script>
		<script>
			sessionArr = getCookie('sessionArr');
			if(!sessionArr){
				ss = '';
			}else{
				ss = JSON.parse(sessionArr).session;
			}
			layer.load(2);
			$.ajax({
		    	type:'POST',
		    	url:commonUrl + 'api/stj/goods/goods_list'+versioninfo,
		    	async:false,
		    	data:{
		    		'home':1,
		    		'ss':ss
		    	},
		    	success:function(data){
		    		layer.closeAll();
		    		console.log(data);
                  	if(data.code == 1){
						if(data.result.list.length!=0){
							goods_page=Math.ceil(data.result.count/12);
							$('#page').css('visibility','visible');
							html='';
							for(var i=0;i<data.result.list.length;i++){
								html+='<div class="product" id="'+data.result.list[i].id+'" onclick="godetail(this)">';
								html+='	<img src="'+data.result.list[i].image+'">';
								html+='	<p>'+data.result.list[i].name+'</p>';
								html+='	<div>';
								html+='		<span class="price">¥ '+data.result.list[i].price+'</span>';
								html+='		<span class="sales">销量 '+data.result.list[i].sales+'</span>';
								html+='		<div class="clearfix"></div>';
								html+='	</div>';
								html+='</div>';
							}
							$('.goods_show').html(html);
						}else{
							layer.msg('暂无商品');
						}
                	}else{
                		layer.msg(data.msg);
                	}
              	}
         	});
         	$("#page").Page({
		        totalPages: goods_page,
		        liNums: 7,
		        activeClass: 'activP', 
		        callBack : function(page){
		          	console.log(page);
		          	ajaxrequest(page);
		        }
      		});
      		pageW=$('#page').width();
      		$('#page').css('left',(1000-pageW)/2 + 'px');
      		function ajaxrequest(num){
				$.ajax({
			    	type:'POST',
			    	url:commonUrl + 'api/stj/goods/goods_list'+versioninfo,
			    	data:{
			    		'page':num,
			    		'home':1,
			    		'ss':ss
			    	},
			    	success:function(data){
			    		console.log(data);
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
					html+='<div class="product" id="'+obj.result.list[i].id+'" onclick="godetail(this)">';
					html+='	<img src="'+obj.result.list[i].image+'">';
					html+='	<p>'+obj.result.list[i].name+'</p>';
					html+='	<div>';
					html+='		<span class="price">¥ '+obj.result.list[i].price+'</span>';
					html+='		<span class="sales">销量 '+obj.result.list[i].sales+'</span>';
					html+='		<div class="clearfix"></div>';
					html+='	</div>';
					html+='</div>';
				}
				$('.goods_show').html(html);
			}
			function godetail(obj){
				pro=obj;
				id=$(pro).attr('id');
//				console.log(id);
				window.location.href='product_detail.php?id='+id;
			}
		</script>
	</body>
</html>
