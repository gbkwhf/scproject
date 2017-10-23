<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>病例详情</title>
		<link rel="stylesheet" href="css/common.css">
		<link rel="stylesheet" href="css/reportDetail.css">
        
            
	</head>
	<body>
		<?php include 'header.php' ?>
		<div class="bg">
	        <div class="container">
				<!--<p class="illname">轻微脑血栓</p>
				<div class="hosp">
					<div class="hosname">就诊医院：<span>西安交通大学第二附属医院</span></div>
					<div class="time">就诊时间：<span>2016-08-09</span></div>
					<div class="clearfix"></div>
				</div>
				<p class="hosp heatit">病情描述</p>
				<textarea id="illdescribe" class="inp"></textarea>
				<p class="hosp heatit">医生结论</p>
				<textarea id="conclusion" class="inp"></textarea>
				<p class="hosp heatit">图片附件</p>
				<div class="pics">
					<div class="picture">
						<img src="../images/personal-center/illimage.jpg">
					</div>
				</div>-->
		   </div>
		</div>
		<?php include 'footer.php' ?>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
        <script type="text/javascript" src="js/layer/layer.js"></script>
        <script type="text/javascript" src="js/function.js"></script>
		<script>
	    	case_id=$_GET['id'];
	    	code=$_GET['code'];
	    	readdata();
	    	layer.load(2);
	    	function readdata(){
	    		$.ajax({
			    	type:'POST',
			    	url:commonUrl + 'api/stj/user_case/case_list'+versioninfo,
			    	data:{
			    		'code':code,
			    		'case_id': case_id
			    	},
			    	success:function(data){
			    		layer.closeAll();
//			    		console.log(data);
	                    if(data.code == 1){
	                    	title=data.result[0].title;
	                    	hospital=data.result[0].hospital;
	                    	time=data.result[0].time;
	                    	user_desc=data.result[0].user_desc;
	                    	doctor_desc=data.result[0].doctor_desc;
	                    	html='';
	                    	html+='<div id="title" class="illname">'+title+'</div>';
	                    	html+='<div class="hosp">';
	                    	html+='	<div class="hosname">就诊医院：'+hospital+'</div>';
	                    	html+='	<div class="time">就诊时间：'+time.slice(0,10)+'</div>';
	                    	html+='	<div class="clearfix"></div>';
	                    	html+='</div>';
	                    	html+='<p class="hosp heatit">病情描述</p>';
	                    	html+='<div id="illdescribe" class="inp">'+user_desc+'</div>';
	                    	html+='<p class="hosp heatit">医生结论</p>';
	                    	html+='<div id="conclusion" class="inp">'+doctor_desc+'</div>';
	                    	html+='<p class="hosp heatit">图片附件</p>';
	                    	if(data.result[0].img.length==0){
	                    		html+='<p style="color:#595757;padding:10px 14px;font-size:18px;">暂无图片</p>';
	                    	}else{
	                    		html+='<div class="pics">';
	                    		for(var i=0;i<data.result[0].img.length;i++){
		                    		html+='<div class="picture uploadbox" id="'+data.result[0].img[i].id+'" org_img="'+data.result[0].img[i].url+'"  style="background-image:url('+data.result[0].img[i].url+');background-size:contain;background-position:center center;background-repeat:no-repeat;position: relative;transition: all 0.3s;-webkit-transition: all 0.3s;margin-bottom:15px;">';
		                            html+='	<input type="" readonly class="filetest" accept="image/*" style="width: 100%;height: 100%;opacity: 0;position: absolute;left: 0px;top: 0px;z-index: 4;"/>';           
		                            html+='</div>';
		                    	}
		                    	html+='</div>';
	                    	}
	                    	
	                    	$('.container').html(html);
	                    	$('.pics .picture:nth-child(4n)').css('margin-right',0);
	                    	
	                  	}else{
	                  		layer.msg(data.msg);
	                  	}
	                }
		        });
	    	}
		</script>
	</body>
</html>
