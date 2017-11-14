<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>聆医馆</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/lyg_index.css" />
	</head>

	<body>
		<div class="wrapper">
			<div class="shopping-box dmzy">
				<img src="images/dmzy-font.png" width="101" class="tit-font" />
				<h4>养脊柱·保健康</h4>
				<div class="price-box">
					<p>¥：<span>200元/次</span></p>
				</div>
				<em>督脉正阳理疗能明显提高人体代谢能力，激发人体自愈功能，增强人体免疫能力。</em>
			</div>
			<div class="baffle">
				<span></span>
				<div class="circular">
					<img src="images/circular.png" width="17" />
				</div>
				<span></span>
			</div>
			<div class="shopping-box zyc">
				<img src="images/zyc-font.png" width="101" class="tit-font" />
				<h4 style="height: 10px;"></h4>
				<div class="price-box">
					<p>¥：<span>60元/次</span></p>
				</div>
				<em>正阳仓理疗能改善微循环，促进血液循环和新陈代谢，温阳补气，提高免疫，袪寒除湿，排毒养颜</em>
			</div>
		</div>
	</body>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/common.js"></script>
	<script type="text/javascript" src="js/config.js"></script>
	<script type="text/javascript" src="js/layer/layer.js"></script>
	<script type="text/javascript">
		var first_id = $_GET['first_id'];
		//获取商品二级分类
		$.ajax({
			type:"get",
			url: commonsUrl + "api/gxsc/get/commodity/secondary/classification/" + first_id +versioninfos,
			data:{"first_id":first_id},
			success:function(data){
				if(data.code==1){
					console.log(data);
					for(var i=0;i<data.result.length;i++){
						if(data.result[i].second_name=="督脉正阳"){
							$('.dmzy').attr('second_id',data.result[i].second_id);
							shoppingList(data.result[i].second_id);		
						}else if(data.result[i].second_name=="正阳仓"){
							$('.zyc').attr('second_id',data.result[i].second_id);
							shoppingList(data.result[i].second_id);	
						}
					}	
					
				}else{
					layer.msg(data.msg);
				}
			}
		});
		
		function shoppingList(second_id){
			//根据二级分类id获取商品列表
			$.ajax({
				type:"get",
				url:commonsUrl + "api/gxsc/get/commodity/lists/" + second_id +versioninfos,
				data:{"second_id":second_id},
				success:function(data){
					if(data.code==1){
						console.log(data);
						if(data.result[0].goods_name=="督脉正阳"){
							$('.dmzy').attr('goods_id',data.result[0].goods_id);			
						}else if(data.result[0].goods_name=="正阳仓"){
							$('.zyc').attr('goods_id',data.result[0].goods_id);
						}				
					}else{
						layer.msg(data.msg);
					}
				}
			});
		}
		
		$('.shopping-box').click(function(){
			var goods_id = $(this).attr('goods_id');
			location.href='shopDetails.php?goods_id='+goods_id;
		})
	</script>

</html>