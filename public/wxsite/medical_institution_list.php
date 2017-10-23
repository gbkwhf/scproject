<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>医疗机构</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/medical_institution_list.css" />
	</head>
	<body>
		<div class="container">
			<ul>
				<!--<li>
					<img src="image/banner.png" />
					<p>DSfrgxgfgds</p>
				</li>
				<li>
					<img src="image/banner.png" />
					<p>DSfrgxgfgds</p>
				</li>
				<li>
					<img src="image/banner.png" />
					<p>DSfrgxgfgds</p>
				</li>-->
			</ul>
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script src="js/layer/layer.js"></script>
		<script>
			secondid=$_GET['id'];
			$.ajax({
			    	type:'POST',
			    	url:commonUrl + 'api/stj/org_distribution/therr_list'+versioninfo,
			    	data:{
			    		'class_id':secondid
			    	},
			    	success:function(ret){
	                    if(ret.code==1){
	                    	console.log(ret);
	                    	dataret=localStorage.setItem("dataret",JSON.stringify(ret));
	                    	html='';
	                    	for(var i=0;i<ret.result.length;i++){
	                    		html+='<li class="leftpart" onclick="shownext('+ret.result[i].id+')">';
	                    		html+='<img src="'+ret.result[i].logo+'" />';
	                    		html+='<p>'+ret.result[i].name+'</p>';
	                    		html+='</li>';
	                    	}
	                    	$('.container ul').html(html);
	                    }else{
	                        layer.msg(ret.msg);
	                    }
	                },
	                dataType:'json'
	            });
	            
	        function shownext(aid){
	        	location.href='hospital-introduction.php?id='+aid;
	        }
		</script>
	</body>
</html>
	