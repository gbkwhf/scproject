<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>联系我们</title>
		<link rel="stylesheet" href="css/common.css">
		<link rel="stylesheet" href="css/swiper.min.css">
	    <link rel="stylesheet" href="css/contact_us.css">
	    
	</head>
	<body>
		<?php include 'header.php' ?>
		<div class="banner">
	    	<div class="content">
	    		<div class="img">
	    			<img src="images/contact/banner-font.png">
	    		</div>
	    	</div>
	    </div>
		<!--第二部分-->
		<div class="contact_us">
			<div class="content">
				<p class="title">我要咨询<span>CONSULTING</span></p>
				<div class="form">
					<div class="userinfo name">
						<img src="images/contact/user-icon.png">
						<input type="text" placeholder="姓&nbsp;&nbsp;名" id="user_name"/>
					</div>
					<div class="userinfo phone">
						<img src="images/contact/phone-icon.png">
						<input type="text" placeholder="电&nbsp;&nbsp;话" id="user_phone" maxlength="11" onkeyup="this.value=this.value.replace(/\D/g,'')"/>
					</div>
					<div class="select" id="choose">
						<div class="select-text" type="">请选择</div>
						<div class="selist">
							<!--<p>医疗机构</p>
						  	<p class="p2">医疗专家</p>
						  	<p class="lastone">合作企业</p>-->
						</div>
					</div>
					<div class="clearfix"></div>
					<textarea placeholder="请描述您的问题" id="user_ly"></textarea>
					<div id="submit">提&nbsp;&nbsp;交</div>
				</div>
				<!--地图-->
				<div id="map"></div>
				<div class="contact-way">
					<p>电   话：+86-21-58797988<br>地   址：上海市黄浦区茂名南路58号花园饭店607室</p>
				</div>
			</div>
		</div>
		
		<?php include 'footer.php' ?>
		<script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=2b6d335cc3a855af8918ea19d34e98e1"></script> 
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
		<script src="js/layer/layer.js"></script>
	    <script>
	    	sessionArr = getCookie('sessionArr');
	    	if(!sessionArr){
	    		ss='';
	    	}else{
	    		ss=JSON.parse(sessionArr).session;
	    	}
	    	
		    $('#tab>li:eq(5)').addClass('active');
		    
		    layer.load(2);
		    $.ajax({
		    	type:'POST',
		    	url:commonUrl + 'api/stj/getquestiontype'+versioninfo,
		    	data:{
		    		'type':2  //咨询
		    	},
		    	dataType: 'json',
		    	success:function(data){
		    		layer.closeAll();
//		    		console.log(data);
                    if(data.code == 1){
                    	html='';
                    	for(var i=0;i<data.result.length;i++){
 							html+='<p typeid="'+data.result[i].id+'">'+data.result[i].name+'</p>';  		
                    	}
                    	$('.selist').html(html);
                    	$('.selist p').click(function(){
							var select_val = $(this).text();
							var typeid = $(this).attr('typeid');
							$(this).parent().prev('.select-text').html(select_val);
							$(this).parent().prev('.select-text').attr('type',typeid);
						});
                  	}else{
                  		layer.msg(data.msg);
                  	}
                }
           	});
			$('#choose').click(function(){
				if($(this).hasClass('select2')){
					$(this).removeClass('select2');
					$(this).children('.selist').slideUp(500);
				}else{
					$(this).addClass('select2');
					$(this).children('.selist').slideDown(500);
				}
			});
        	
			$('#user_name').focus(function(){
				$(this).prev().attr('src','images/contact/user-icon-blue.png');
			});
			$('#user_phone').focus(function(){
				$(this).prev().attr('src','images/contact/phone-icon-blue.png');
			});
			$('#user_name').blur(function(){
				$(this).prev().attr('src','images/contact/user-icon.png');
			});
			$('#user_phone').blur(function(){
				$(this).prev().attr('src','images/contact/phone-icon.png');
			});
		    //加载地图，调用浏览器定位服务
		    var map = new AMap.Map('map', {
		        resizeEnable: true,
		        zoom:16,
		        center: [121.459762, 31.21992]
		    });
		    var marker = new AMap.Marker({
                map: map,
                position: [121.459762, 31.21992]
	                            
	        });
	         //鼠标点击marker弹出自定义的信息窗体
	        AMap.event.addListener(marker, 'click', function() {
	            infoWindow.open(map, marker.getPosition());
	        });
	        var infoWindow = new AMap.InfoWindow({
		        content: '上海市黄浦区茂名南路58号花园饭店607室',
		        offset: new AMap.Pixel(0, -31)
		    });
		    //标尺，放大缩小
		    AMap.plugin(['AMap.ToolBar','AMap.Scale'],function(){
		        AMap.plugin(['AMap.ToolBar','AMap.Scale'],function(){
		            var toolBar = new AMap.ToolBar();
		            var scale = new AMap.Scale();
		            map.addControl(toolBar);
		            map.addControl(scale);
		        });
		    });
		    
		    //提交信息
		    $('#submit').click(function(){
		    	//姓名电话留言取值
			    var user_name=$('#user_name').val();
			    var user_phone=$('#user_phone').val();
			    var type_id=$('.select-text').attr('type');
//			    console.log(type_id);
			    var content=$('#user_ly').val();
                if(user_name == ''||user_phone==''||content==''){
                    layer.msg("请填写完整！");
                }else if(!checkPhone(user_phone)){
                    layer.msg("请输入正确的手机号码！");
                }else{
                	layer.load(2);
                	$.ajax({
				    	type:'POST',
				    	url:commonUrl + 'api/stj/question/newquestion'+versioninfo,
				    	data:{
				    		'name':user_name,
	                		'mobile':user_phone,
	                		'content':content,
	                		'ss':ss,
	                		'type':type_id
				    	},
				    	success:function(ret){
		                    //coding
		                    layer.closeAll();
		                    if(ret.code==1){
		                        layer.msg('提交成功', {time: 2000}, function(){
									location.reload();
								}); 
		                    }else{
		                        layer.msg(ret.msg);
		                    }
		                },
		                dataType:'json'
		            });
                }
		    });
		    
	    </script>
	</body>
</html>
