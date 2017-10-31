<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>健康商城分类列表</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css"/>
    <link rel="stylesheet" href="css/classification.css">
</head>
<body>
<div class="content"> 	
 	<ul class="left_nav">
        <!--<li class="curr_tab">营养保健</li>
        <li> 家庭常备</li>
        <li> 健康调理</li>       
        <li  class="last_li"> 健康器械</li>-->
    </ul>     
    <div class="right_content">
        <ul>
            <!--<li>
                <img src="images/rice.png" alt="">
                <em>澳大利亚大米</em>
                <i>¥18.00</i>
            </li>                    -->
        </ul>
    </div>
</div>
</body>
	<script src="js/common.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="js/config.js"></script>
	<script src="js/jquery.min.js"></script>
	<script>
	    
			
		var winH = $(window).height();
		$('.content').height(winH-15);
					
		var first_id = $_GET['first_id'];
		$.ajax({
	    	type:"get",
	    	url: commonsUrl + "api/gxsc/get/commodity/secondary/classification/"+ first_id + versioninfos,
	    	data:{
	    		"first_id":first_id
	    	},
	    	success:function(data){
	    		if(data.code==1){
	    			console.log(data);
	    			html='';
	    			for(var i=0;i<data.result.length;i++){
	    				html+='<li second_id="'+data.result[i].second_id+'">'+data.result[i].second_name+'</li>'
	    			}
	    			$('.left_nav').html(html);
	    			$('.left_nav li:first').attr('class','curr_tab');
	    			$('.left_nav li:last').attr('class','last_li');
	    			
	    			levelList(data.result[0].second_id);
	    			
	    			var len=$(".left_nav>li").length;
    
				    $(".left_nav>li").click(function(){
				        var index=$(this).index();
				        if(index!=0){
				         	$(".left_nav").css("border-top","1px solid #e6e6e6");
				         	$(".left_nav>li").eq(len-1).css("border-bottom","1px solid #e6e6e6");
				        }else{
				          	$(".left_nav").css("border-top","1px solid #f3f5f7");		
				        }
				        if(index==len-1){
				         	$(".left_nav>li").eq(len-1).css("border-bottom","none");
				        }
				        $(this).addClass("curr_tab").siblings("li").removeClass("curr_tab");
				        
				        levelList($(this).attr("second_id"));
				    });
	    		}
	    	}
	    });
	    
		//专区切换
	  	function levelList(second_id){
	  		$.ajax({
				type:"get",
				url: commonsUrl + "api/gxsc/get/commodity/lists/"+ second_id + versioninfos,
				data:{
		    		"second_id":second_id
		    	},
		    	success:function(data){
		    		if(data.code==1){
		    			console.log(data);
		    			html='';
		    			if(data.result.length==0){
		    				$('.right_content ul').html('暂无商品,敬请期待!');
		    				$('.right_content ul').css({'line-height':winH-33+'px','text-align':'center','color':'#333'});
		    			}else{
			    			for(var i=0;i<data.result.length;i++){
			    				html+='<li goods_id="'+data.result[i].goods_id+'" onclick="location.href=\'shopDetails.php?goods_id='+data.result[i].goods_id+'\'">'+
					                '<img src="'+data.result[i].image+'" alt="">'+
					                '<em>'+data.result[i].goods_name+'</em>'+
					                '<i>¥'+data.result[i].price+'</i>'+
					            '</li>';
			    			}
			    			$('.right_content ul').html(html);
						}
		    		}
		    	}
			});
	  	}
	 </script>
</html>
