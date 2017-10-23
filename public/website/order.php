<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>商品订单</title>
		<link rel="stylesheet" href="css/common.css">
	    <link rel="stylesheet" href="css/order.css">
	    
	</head>
	<body>
		<?php include 'header.php' ?>
		<div class="container">
			<div class="content3" id="proshow">
				<!--<table class="medRecordList" cellpadding="0" cellspacing="0">
					<tr>
		                <td>商品名称</td>
		                <td class="second">单价</td>
		                <td class="third">小计</td>
		           	</tr>
				</table>
				<ul class="order-list">
					<li>
						<div class="proImge"><img src="../images/personal-center/product.jpg"></div>
						<div class="proInfo">
							<p class="proNames">茉莉香米泰国茉莉香茉莉香米泰国茉莉香米泰国茉莉香米泰国茉莉香</p>
							<div>
								<div class="price">¥ 1800.00</div>
								<div class="num">
									<span class="numtxt">数量</span>
									<span id="cut">-</span>
									<span class="number"></span>
									<span id="plus">+</span>
									<div class="clearfix"></div>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<div class="time">1800.00</div>
						<div class="state">1800.00</div>
					</li>
				</ul>
				<div class="total">需付款：<span>¥ 1800.00</span></div>
				<div class="recInfo">
					<div class="receiver">
					姓名：<input type="text" value="" class="short"/>
					</div>
					<div class="receiver phone">
					手机号码：<input type="text" value="" class="short"/>
					</div>
					<div class="clearfix"></div>
					<div class="addr">
						<div class="ad">地址：</div>
						<textarea class="ad det"></textarea>
						<div class="clearfix"></div>
					</div>
				</div>
				<div id="btn">立即付款</div>-->
			</div>
	   	</div>
	   	<?php include 'footer.php' ?>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
		<script type="text/javascript" src="js/layer/layer.js"></script>
	    <script>
	    	sessionArr = getCookie('sessionArr');
	    	if(!sessionArr){
				ss = '';
			}else{
				ss = JSON.parse(sessionArr).session;
			}
	    	id=$_GET['id'];
	    	count=$_GET['count'];
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
						html+='<table class="medRecordList" cellpadding="0" cellspacing="0">';
						html+='	<tr>';
		                html+='		<td>商品名称</td>';
		                html+='		<td class="second">数量</td>';
		                html+='		<td class="third">单价</td>';
		           		html+='	</tr>';
						html+='</table>';
						html+='<ul class="order-list">';
						html+='	<li>';
						html+='		<div class="proImge"><img src="'+img+'"></div>';
						html+='		<div class="proInfo">';
						html+='			<p class="proNames">'+name+'</p>';	
						html+='		</div>';
						html+='		<div class="time">';
						html+='				<div class="num">';		
						html+='					<span class="numtxt"></span>';			
						html+='					<span id="cut">-</span>';			
						html+='					<input class="number" value="'+count+'" id="count" readonly/>';			
						html+='					<span id="plus">+</span>';			
						html+='					<div class="clearfix"></div>';			
						html+='				</div>';		
						html+='		</div>';
						html+='		<div class="state" id="money">¥ '+price+'</div>';
						html+='	</li>';
						html+='</ul>';
						html+='<div class="total">需付款：<span id="pay">¥ '+(count*price).toFixed(2)+'</span></div>';
						html+='<div class="recInfo">';
						html+='	<div class="receiver">姓名：<input id="name" placeholder="收货人姓名" type="text" value="" class="short" maxlength="6" onkeyup="filter()"/></div>';
						html+='	<div class="receiver phone">手机号码：<input id="phone" placeholder="收货人手机号码" type="text" value="" class="short" maxlength="11" /></div>';
						html+='	<div class="clearfix"></div>';
						html+='	<div class="addr">';
						html+='		<div class="ad">地址：</div>';
						html+='		<textarea id="address" class="ad det" style="resize:none" placeholder="收货地址"></textarea>';
						html+='		<div class="clearfix"></div>';
						html+='	</div>';
						html+='</div>';
						html+='<div id="btn">立即付款</div>';
						$('#proshow').html(html);
						$('#plus').click(function(){
							counts=parseInt($(this).prev().val());
							if(counts<reserve){
								counts++;
								$(this).prev().val(counts);
								money=(counts*price).toFixed(2);
								$('#pay').html('¥ '+money);
							}else{
								layer.msg('库存没那么多哦');
							}
						});
						$("#phone").keyup(function(){
							phoneval=$(this).val();
							$(this).val(phoneval.replace(/\D/g,''));
						})
						$('#cut').click(function(){
							counts=parseInt($(this).next().val());
							if(counts!=1){
								counts--;
								$(this).next().val(counts);
								money=(counts*price).toFixed(2);
								$('#pay').html('¥ '+money);
							}
						});
						//点击按钮提交
						$('#btn').click(function(){
							var user_name=$('#name').val();
							var phone=$('#phone').val();
							var addr=$('#address').val();
							var checkAddr = testAddr(addr);
	            			var checkTel = testTel(phone);
							var checkName = testName(user_name);
	            			
							if(checkName&&checkTel&&checkAddr){
								final_count=parseInt($('#count').val());  //产品数量
								if(ss==''){
									layer.msg('您处于未登录状态，快去登录吧',{time:1000},function(){
										location.href="login.php";
									});
								}else{
									$.ajax({
								    	type:'POST',
								    	url:commonUrl + 'api/stj/goods/order_sub'+versioninfo,
								    	data:{
								    		'ss': ss,
								    		'goods_id': id,
								    		'num': final_count,
								    		'receive_address': addr,
								    		'receive_name': user_name,
								    		'receive_phone': phone
								    	},
								    	success:function(data){
								    		console.log(data);
						                    if(data.code == 1){
						                    	order_id=data.result.order_id;
						                    	pay=data.result.pay;
						                    	if(pay==1){
						                    		layer.msg('提交成功，请尽快进行支付');
			                                    	setTimeout('location.href="paySelectPayType.php?oid='+data.result.order_id+'&name='+name+'&price='+(count*price).toFixed(2)+'&type=2"',1000);
						                    	}else{
						                    		layer.msg('支付成功');
						                    		setTimeout('location.href="personal_center.php?queue=4"',1000);
						                    	}
			                                    //type=1 服务， type =2 商品
						                  	}else if(data.code=="1011"){
						                  		layer.msg('帐号在其他设备登录，请重新登录');
						                  		setTimeout("removeCookie('sessionArr');location.href='login.php'", 1000);
						                  	}else{
						                  		layer.msg(data.msg);
						                  	}
						                }
						            });
								}
							}
						});
						function testName(val){
				            if(!val){
				                layer.tips("请填写收货人姓名","#name",{tips:[2,'#21c0d5']});
				                return false;
				            }else{
				                return true;
				            }
				
				        }
				        function testTel(val){
				        	var reg = /^1[034578][0-9]{9}$/;
				            if(!val){
				                layer.tips("请填写收货人电话","#phone",{tips:[2,'#21c0d5']});
				                return false;
				            }else if(!reg.test(val)){
				            	index= layer.tips("请填写正确手机号","#phone",{tips:[2,'#21c0d5']});
				                $('#phone').focus();
				                return false;
				            }else{
				                return true;
				            }
				
				        }
				        function testAddr(val){
				            if(!val){
				                layer.tips("请填写收货地址","#address",{tips:[2,'#21c0d5']});
				                return false;
				            }else{
				                return true;
				            }
				
				        }
				        //正则判断用户名
						function filter(){
							var name=$("#name").val();
							name=name.replace(/([^0-9a-zA-Z\u4e00-\u9fa5]+)$/,'');//英文字母、数字、中文
							$("#name").val(name);
						}
                  	}else{
                  		layer.msg(data.msg);
                  	}
                }
            });
	    </script>
	</body>
</html>
