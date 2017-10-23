<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>医疗机构</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/medical_institution.css" />
	</head>
	<body>
		<div class="container">
			<ul class="first-classify">
				<li class="active">
					<div class="country">
						<img src="image/china-icon.png" width="22" />
					</div>
					<p>中国大陆</p>					
				</li>
				<li>
					<div class="country">
						<img src="image/zhua-icon.png" width="24" />
					</div>
					<p>大中华区</p>					
				</li>
				<li>
					<div class="country">
						<img src="image/riben-icon.png" width="24" />
					</div>
					<p>日本</p>					
				</li>
				<li>
					<div class="country">
						<img src="image/asia-icon.png" width="25" />
					</div>
					<p>亚洲</p>					
				</li>
				<li>
					<div class="country">
						<img src="image/europe-icon.png" width="27" />
					</div>
					<p>欧洲</p>					
				</li>
				<li>
					<div class="country">
						<img src="image/america-icon.png" width="23" />
					</div>
					<p>美洲</p>					
				</li>
				<li>
					<div class="country">
						<img src="image/australia-icon.png" width="24" />
					</div>
					<p>澳洲</p>					
				</li>
				<li>
					<div class="country">
						<img src="image/other-icon.png" width="20" />
					</div>
					<p>其他</p>					
				</li>
			</ul>
			<div class="second-list">
				<!--<ul class="second-classify" style="display: block;">
					<li class="active">一线城市</li>
					<li>长三角区域</li>
					<li>珠三角区域</li>
					<li>东北区域</li>
				</ul>-->
			</div>			
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script>
			index=0;
			loaddata();
			
		//tab切换
			$('.first-classify li').click(function(){
				
				$(this).addClass("active").siblings().removeClass("active");
				$(".second-classify").hide().eq($('.first-classify li').index(this)).show();
				index = $("ul li").index(this);
				loaddata();
			});
		
			
			function loaddata(){
				country=index+1;
				$.ajax({
					type:"post",
					url:commonUrl + 'api/stj/org_distribution/tow_list'+versioninfo,
					data:{
						'type':country
					},
					success:function(data){
						if(data.code==1){
							console.log(data);
							html='';
							html+='<ul class="second-classify">';						
							for(var i=0;i<data.result.length-1;i++){							
								html+='<li  onclick="secondid('+data.result[i].id+')">'+data.result[i].name+'</li>';
							}
							html+='</ul>';
							$(".second-list").html(html);
							$(".second-classify:eq(0)").css("display","block");	
							$(".second-classify li:eq(0)").addClass("active");					
						}
					}
				});
			}
			function secondid(id){
				location.href="medical_institution_list.php?id="+id;
			}
		</script>
	</body>
</html>