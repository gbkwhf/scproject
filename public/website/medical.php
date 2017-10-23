<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>医疗机构</title>
		<link rel="stylesheet" href="css/common.css">
		<link rel="stylesheet" href="css/swiper.min.css">
		<link rel="stylesheet" type="text/css" href="css/CSSreset.min.css" />	
	    <link rel="stylesheet" href="css/medical.css">
	    
	</head>
	<body>
		<?php include 'header.php' ?>
		<div class="banner">
	    	<div class="content">
	    		<div class="img">
	    			<img src="images/medical/yl-banner-font.png">
	    		</div>
	    	</div>
	    </div>
	    <div class="bg-blue">
	    	<div class="content">
	    		<div>机构分布</div>
	    	</div>
	    </div>
		<!--第二部分机构分布-->
		<div class="org-dis">
			<div class="bg-cover-map">
				<div class="content">
					<ul id="first-classify">
						<!--<li>
							<a>中国大陆<span></span>283<img src="images/medical/right-arrow.png"></a>
							<!--二级目录-->
							<!--<ul class="second-classify">
								<li><a>一线城市<span>176</span><img src="images/medical/black-arrow.png"></a></li>
								<li><a>长三角区域<span>176</span><img src="images/medical/black-arrow.png"></a></li>
							</ul>-->
						<!--</li>
						<li>
							<a>港澳台地区以及全球网络医院列表<img src="images/medical/right-arrow.png"></a>
						</li>-->
					</ul>
					<!--右侧内容展示-->
					<div class="org-list"></div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
		<?php include 'footer.php' ?>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
		<script src="js/layer/layer.js"></script>
	    <script>
		    $('#tab>li:eq(3)').addClass('active');
		    $.ajax({
		    	type:'POST',
		    	url:commonUrl + 'api/stj/org_distribution/org_list'+versioninfo,
		    	success:function(ret){
                    //coding
                    if(ret.code==1){
                        data = JSON.stringify(ret.result.one_level);
                        localStorage.setItem("datas",data);
                        creatEle();
                    }else{
                        layer.msg(ret.msg);
                    }
                },
                dataType:'json'
            });
            three_level(1);
            //ul
            function creatEle(){
            	datas = JSON.parse(localStorage.getItem("datas"));
//          	console.log(datas);
				html='';
		        for(var i=0;i<datas.length;i++){
		        	var name=datas[i].name;
		        	var count=datas[i].count;
		          	html+='<li>';
		          	html+='	<a>'+name+'<span></span><img src="images/medical/right-arrow.png"></a>';
		          	html+='	<ul class="second-classify">';
		          	for(var j=0;j<datas[i].two_level.length;j++){
		          		var name_two=datas[i].two_level[j].name;
		          		var count_two=datas[i].two_level[j].count;
		          		var id=datas[i].two_level[j].id;
		          		html+='	<li onclick="three_level('+id+',this)"><a>'+name_two+'<span></span><img src="images/medical/black-arrow.png"></a></li>';
		          	}
		          	html+='	</ul>';
		          	html+='</li>';
		        }
		        $('#first-classify').html(html);
		        $('#first-classify>li').eq(0).children().children('img').attr('src','images/medical/down-arrow.png');
		        $('#first-classify>li').eq(0).children('.second-classify').css('display','block');
		        $('#first-classify>li').eq(0).children('.second-classify').children().eq(0).children().children('img').attr('src','images/medical/blue-arrow.png');
		        $('#first-classify>li>a').click(function(){
					src=$(this).children('img').attr('src');
					if(src=='images/medical/right-arrow.png'){
						$(this).children('img').attr('src','images/medical/down-arrow.png');
						$(this).next('.second-classify').slideDown(200);
					}else{
						$(this).children('img').attr('src','images/medical/right-arrow.png');
						$(this).next('.second-classify').slideUp(200);
					}
				});
                $('body').height(0);
           }
            
            function three_level(class_id,obj){
            	pre_li=obj;
            	$(pre_li).children().children('img').attr('src','images/medical/blue-arrow.png');
            	$(pre_li).siblings().children().children('img').attr('src','images/medical/black-arrow.png');
				$(pre_li).parent().parent().siblings().children('.second-classify').children().children().children('img').attr('src','images/medical/black-arrow.png');
            	$.ajax({
			    	type:'POST',
			    	url:commonUrl + 'api/stj/org_distribution/therr_list'+versioninfo,
			    	data:{
			    		'class_id':class_id
			    	},
			    	success:function(ret){
//	                    console.log(ret);
	                    if(ret.code==1){
	                    	hos_detail = JSON.stringify(ret.result);
                        	localStorage.setItem("hos_detail",hos_detail);  //存储医院信息(三级目录下)，以便在下个页面使用
	                    	html='';
	                    	for(var i=0;i<ret.result.length;i++){
	                    		var hos_name=ret.result[i].name;
	                    		html+='<div class="leftpart" onclick="hosdetail('+ret.result[i].id+')"><a style="display:inline;cursor:pointer">'+hos_name+'</a></div>';
	                    	}
	                    	html+='<div class="clearfix"></div>';
	                    	$('.org-list').html(html);
	                    }else{
	                    	$('.org-list').html('');
	                        layer.msg(ret.msg);
	                    }
	                },
	                dataType:'json'
	            });
            }
			
			function hosdetail(hosid){
				location.href='hospital_detail.php?hosid='+hosid;
			}
	    </script>
	</body>
</html>
