<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>商品详情</title>
		<link rel="stylesheet" href="css/common.css">
	    <link rel="stylesheet" href="css/product_detail.css">
	</head>
	<body>
		<?php include 'header.php' ?>
		<div class="wrapper">
			<div class="content">
				<div class="proDetail">
					<!--<div class="proImg">
						<img src="images/product/proimage.jpg">
					</div>
					<div class="proNorm">
						<p class="proName"></p>
						<p class="pro price"><span>售价</span></p>
						<!--<div class="norm">
							<span class="size">尺码</span>
							<div class="sizeCont">
								<span class="select">20斤</span>
								<span>10斤</span>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="norm">
							<span class="size">颜色</span>
							<div class="sizeCont">
								<span class="select">白色</span>
								<span>蓝色</span>
							</div>
							<div class="clearfix"></div>
						</div>-->
						<!--<div class="num">
							<span class="numtxt">数量</span>
							<span id="cut">-</span>
							<span class="number"></span>
							<span id="plus">+</span>
							<span class="stor"></span>
							<div class="clearfix"></div>
						</div>
						<div class="Btn">
							<div id="buynow">立即购买</div>
							<!--<div id="addcar">加入购物车</div>-->
						<!--</div>
					</div>
					<div class="clearfix"></div>-->
				</div>
				<div class="proMore">
					<p>商品详情</p>
				</div>
			</div>
		</div>
		<?php include 'footer.php' ?>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
		<script type="text/javascript" src="js/layer/layer.js"></script>
		<script>
			sessionArr = getCookie('sessionArr');
//			$('.sizeCont span').click(function(){
//				$(this).addClass('select');
//				$(this).siblings().removeClass('select');
//			});
			if(!sessionArr){
				ss = '';
			}else{
				ss = JSON.parse(sessionArr).session;
			}
			id=$_GET['id'];
			layer.load(2);
			$.ajax({
		    	type:'POST',
		    	url:commonUrl + 'api/stj/goods/goods_info'+versioninfo,
		    	data:{
		    		'gid':id,
		    		'ss':ss
		    	},
		    	success:function(data){
		    		layer.closeAll();
//		    		console.log(data);
                    if(data.code == 1){
						img=data.result.image;   //商品图
						name=data.result.name;   //商品名称
						price=data.result.price;   //商品价格
						reserve=data.result.num;	//商品库存
						content=data.result.content;
						html='';
						html+='<div class="proImg">';
						html+='	<img src="'+img+'">';
						html+='</div>';
						html+='<div class="proNorm">';
						html+='	<p class="proName">'+name+'</p>';
						html+='	<p class="pro price"><span>售价</span>¥'+price+'</p>';
						html+='	<div class="num">';
						html+='		<span class="numtxt">数量</span>';
						html+='		<span id="cut">-</span>';
						if(reserve==0){
							html+='		<input class="number" value="0" id="count" readonly/>';
						}else{
							html+='		<input class="number" value="1" id="count" readonly/>';
						}
						html+='		<span id="plus">+</span>';
						html+='		<span class="stor">库存'+reserve+'件</span>';
						html+='		<div class="clearfix"></div>';
						html+='	</div>';
						html+='	<div class="Btn"><div id="buynow" onclick="goorder()">立即购买</div></div>';
						html+='</div>';
						html+='<div class="clearfix"></div>';
						$('.proDetail').html(html);
						$('.proMore').html('<p style="font-size: 18px;color: #3f3f3f;border-top:2px solid #dcdcdc;padding:30px 0;">商品详情</p>'+content);
						$('#plus').click(function(){
							counts=parseInt($(this).prev().val());
							if(counts<reserve){
								counts++;
								$(this).prev().val(counts);
							}
						});
						$('#cut').click(function(){
							counts=parseInt($(this).next().val());
							if(counts!=0){
								counts--;
								$(this).next().val(counts);
							}
						});
                  	}else{
                  		layer.msg(data.msg);
                  	}
                }
            });
            function goorder(){
            	var count=$('#count').val();
//          	console.log(count);
            	location.href='order.php?id='+id+'&count='+count;
            }
		</script>
	</body>
</html>
