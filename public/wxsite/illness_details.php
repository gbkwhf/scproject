<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>病情详情</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/illness_details.css" />
	</head>
	<body>
		<div class="container">
			<div class="sub-tit">
				<!--<p class="sub-tit">轻微脑血栓</p>-->
			</div>
			<p class="grayline"></p>
			<div class="anchor">
				<a href="javascript:scroll('illness');" class="active">病历</a>
				<a href="javascript:scroll('describe');">描述</a>
				<a href="javascript:scroll('conclusion');">结论</a>
				<a href="javascript:scroll('picture');">图片</a>
			</div>
			<div id="illness">
				<!--<p class="sub-title">个人病历</p>
				<div class="border-box">
					<p>病情诊断</p>
					<span>轻微脑血栓</span>
				</div>
				<div class="border-box">
					<p>就诊日期</p>
					<span>2012-05-10</span>
				</div>
				<div class="border-box">
					<p>就诊医院</p>
					<span>西安陆军总院</span>
				</div>-->
			</div>
			<p class="grayline"></p>
			<div id="describe">
				<!--<p class="sub-title">病情描述</p>
				<p class="describe">脑血栓形成是脑梗死最常见的类型。是脑动脉主干 或皮质支动脉粥样硬化导致血管增厚、管腔狭窄闭 塞和血栓形成，引起脑局部血流减少或</p>-->
			</div>
			<p class="grayline"></p>
			<div id="conclusion">
				<!--<p class="sub-title">医生结论</p>
				<p class="describe">脑血栓形成是脑梗死最常见的类型。是脑动脉主干 或皮质支动脉粥样硬化导致血管增厚、管腔狭窄闭 塞和血栓形成，引起脑局部血流减少或</p>-->
			</div>
			<p class="grayline"></p>
			<div id="picture">
				<!--<p class="sub-title">图片附件</p>
				<div class="picbox">
					<img src="image/banner.png" />
					<img src="image/banner.png" />
					<img src="image/banner.png" />
				</div>-->
			</div>
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script src="js/hovertreescroll.js"></script>
		<script>
		
		session = getCookie('session');
		session=session.substr(1,session.length-2);
		
		case_id=$_GET['id'];
		function scroll(id) {  
			$("#" + id).HoverTreeScroll(1000);  
		}
		$(".anchor a").click(function(){
			$(this).siblings("a").removeClass("active");
			$(this).addClass("active");
		})
		
		$.ajax({
			type:"post",
			url:commonUrl + 'api/stj/user_case/case_list'+versioninfo,
			data:{
				'ss':session,
				'case_id':case_id
			},
			success:function(data){
				if(data.code==1){
					console.log(data);
					zdhtml="";
					zdhtml+='<p>'+data.result.title+'</p>';
					$(".sub-tit").html(zdhtml);
					html="";
					html+='<p class="sub-title">个人病历</p>';
					html+='<div class="border-box">';
					html+='	<p>病情诊断</p>';
					html+='	<span>'+data.result.title+'</span>';
					html+='</div>';
					html+='<div class="border-box">';
					html+='	<p>就诊日期</p>';
					html+='	<span>'+data.result.time.substr(0,10)+'</span>';
					html+='</div>';
					html+='<div class="border-box">';
					html+='	<p>就诊医院</p>';
					html+='	<span>'+data.result.hospital+'</span>';
					html+='</div>';
					$("#illness").html(html);
					mshtml="";
					mshtml+='<p class="sub-title">病情描述</p>';
					mshtml+='<p class="describe">'+data.result.user_desc+'</p>';
					$("#describe").html(mshtml);
					jlhtml="";
					jlhtml+='<p class="sub-title">医生结论</p>';
					jlhtml+='<p class="describe">'+data.result.doctor_desc+'</p>';
					$("#conclusion").html(jlhtml);
					fjhtml="";
					fjhtml+='<p class="sub-title">图片附件</p>';
					fjhtml+='<div class="upload-box">';
					
					for(var i=0;i<data.result.img.length;i++){
						fjhtml+='<div class="picbox">';
						fjhtml+='	<img src="'+data.result.img[i].url+'" class="dqpic" />';
						fjhtml+='</div>';
					}						
					
					fjhtml+='</div>';
					
					$("#picture").html(fjhtml);
					picT=$("#picture .upload-box .picbox").text();
					
					if(picT==""){
						$("#picture .upload-box").append('<p class="nones">暂无</p>');
					}
				}else if(data.code==1011){
					layer.msg('该用户登陆数据已过期，请重新登陆');
                	setTimeout("location.href='sign_in.php'",1000);
				}else{
					layer.msg(data.msg)
				}
			}
		});
		</script>
		<style>
	        .layui-layer{
	            left:0;
	        }
	        .ui-loader{
	            display: none;
	        }
	    </style>
	</body>
</html>
	