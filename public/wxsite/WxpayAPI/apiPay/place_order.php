<?php 
	ini_set('date.timezone','Asia/Shanghai');
	error_reporting(E_ERROR);
	require_once "../lib/WxPay.Api.php";
	require_once "WxPay.JsApiPay.php";
	require_once 'log.php';
	
	//初始化日志
	$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
	$log = Log::Init($logHandler, 15);
	
	//打印输出数组信息
	function printf_info($data)
	{
	    foreach($data as $key=>$value){
	        echo "<font color='#00ff55;'>$key</font> : $value <br/>";
	    }
	}
	
	//①、获取用户openid
	$tools = new JsApiPay();
	$openId = $tools->GetOpenid();

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>提交订单</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="../../css/common.css"/>
		<link rel="stylesheet" type="text/css" href="../../css/place_order.css" />
		

	</head>
	<body>
		
		<div class="container">
			<div class="content">
				<div class="order-info">
					<!--<p class="title">法国纯天然大米</p>
					<div class="goods-show">
						<img src="image/order-pic.png" width="123" />
						<div class="explain">
							<p>法国纯天然大米法国纯天然大米法国纯天然大米法国纯天然大米</p>
							<span>¥<mark>800</mark>.00</span>
						</div>
					</div>
					<div class="form-box borbot number-box">
						<p>数量</p>
						<span>
							<strong class="add">+</strong>
							<em class="numbers">1</em>					
							<strong class="subtract">-</strong>
						</span>
					</div>
					<p class="grayline"></p>
					<div class="form-box borbot">
						<p>需付款</p>
						<span>¥<mark>200.00</mark></span>
					</div>-->
				</div>			
				<p class="grayline"></p>
				<div class="cont-box">
					<div class="form-boxs">
						<label for="user">收货人</label>
						<input type="text" placeholder="请填写姓名" name="user" id="user" maxlength="6" />
					</div>
					<div class="form-boxs">
						<label for="phone">手机号码</label>
						<input type="text" placeholder="请填写联系方式" name="phone" id="phone" maxlength="11" onkeyup="this.value=this.value.replace(/\D/g,'')" />
					</div>
					<div class="form-boxs">
						<label for="remark" class="bz">详细地址</label>
						<input type="text" name="remark" id="remark" placeholder="请填写收货地址" maxlength="30" />
					</div>
				</div>
			</div>
			<a class="submit" >立即购买</a>
		</div>
		
		<script src="../../js/jquery.min.js"></script>
		<script src="../../js/common.js"></script>
		<script src="../../js/layer/layer.js"></script>
		<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
		<script>
			//alert(11);
			session = getCookie('session');
			session=session.substr(1,session.length-2);
			
			dataStrs=JSON.parse(localStorage.getItem("dataStrs"));
//			console.log(dataStrs);
			goodsid=dataStrs.result.id;
						
			numbers=1;
			html="";
			
			html+='<p class="title">'+dataStrs.result.name+'</p>';
			html+='<div class="goods-show">';
			html+='	<img src="'+dataStrs.result.image+'" width="123" height="123" />';
			html+='	<div class="explain">';
			html+='		<p>'+dataStrs.result.name+'</p>';
			html+='		<span>¥<mark>'+dataStrs.result.price+'</mark></span>';
			html+='	</div>';
			html+='</div>';
			html+='<div class="form-box borbot number-box">';
			html+='	<p>数量</p>';
			html+='	<span>';
			html+='		<strong class="add">+</strong>';
			html+='		<em class="numbers">1</em>';			
			html+='		<strong class="subtract">-</strong>';
			html+='	</span>';
			html+='</div>';
			html+='<p class="grayline"></p>';			
			html+='<div class="form-box borbot">';
			html+='	<p>需付款</p>';
			html+='	<span>¥<mark>'+dataStrs.result.price+'</mark></span>';
			html+='</div>';
			$(".order-info").html(html);
			
			$(".number-box span").click(function(){
				var numbers=$(".number-box .numbers").text();
				$(".borbot mark").text(eval((dataStrs.result.price)*numbers).toFixed(2));	
			})
			winW=$(window).width();
			$(".explain").width(winW-155);
			
			titH=$(".explain p").height();
			priceH=$(".explain span").height();
			$(".explain span").css("margin-top",125-titH-priceH);
			$("#remark").width(winW-100);
			
			$(".form-boxs:last").css("border","none");
			
			$(".add").click(function(){
				ii=parseInt($(".numbers").text());
				num=ii+1;
				$(".numbers").text(num);
			})
			
			winH=$(window).height();
			contH=$(".content").height();
			if(winH-contH>58){
				$(".submit").addClass("fixed");
			}
			
			
			$(".subtract").click(function(){
				ii=parseInt($(".numbers").text());
				if(ii>1){
					num=ii-1;
					$(".numbers").text(num);
				}else{
					num=ii;
					$(".numbers").text(num);
				}
				
			});
			$(".submit").click(function(){
				
				if(!session){
					//校验是否有session
					location.href="../../sign_in.php";
				}
				
				var shuser=$("#user").val();
				var phone=$("#phone").val();
				var phonelength=phone.length;
				var remark=$("#remark").val();
				var numbers=$(".numbers").text();
				console.log(numbers);
				
				var myreg = /^1[034578][0-9]{9}$/; //正则判断手机号是否有效
				
				if(shuser==""||phone==""||remark==""){
					layer.msg("请填写完整");
				}else if(phonelength<11||!myreg.test($("#phone").val())){
					layer.msg("请输入11位有效手机号")
				}else{
					$.ajax({
						type:"post",
						url:commonUrl+'api/stj/goods/order_sub'+versioninfo,
						data:{
							'goods_id':goodsid,
							'num':numbers,
							'receive_address':remark,
							'receive_name':shuser,
							'receive_phone':phone,
							'ss':session
						},
						success:function(ret){
							if(ret.code==1){
								console.log(ret);
								if(ret.result.pay==1){
									layer.msg("提交成功，去支付");
									order_id=ret.result.order_id;
									console.log(order_id);                           		

									$.ajax({
										type:"post",
										url:commonUrl+'api/stj/pay/goods'+versioninfo,
										data:{
											'open_id':'<?php echo $openId?>',
											'ss':session,
											'order_id':order_id,
											'filling_type':3
										},
										success:function(data){
											if(data.code==1){
												data.result.timeStamp=JSON.stringify(data.result.timeStamp);
												retStr =data.result;
                                                callpay();
                                                //调用微信JS api 支付
												function jsApiCall(){
													WeixinJSBridge.invoke(
														'getBrandWCPayRequest',
														retStr,
														function(res){
											                if(res.err_msg == "get_brand_wcpay_request:ok" ) {
											                		location.replace('../../order_list.php');
											                }else{
//											                    alert(res.err_msg);
											                }
											            }
													);
												}
												
												
												function callpay(){
													if (typeof WeixinJSBridge == "undefined"){
													    if( document.addEventListener ){
													        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
													    }else if (document.attachEvent){
													        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
													        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
													    }
													}else{
													    jsApiCall();
													}
												}
											}
										}
									});										
								}else{
									orderlist=function(){
										location.href="../../order_list.php"
									}
									setInterval(orderlist,2000)
								}
								
							}else if(data.code==1011){
								layer.msg('该用户登陆数据已过期，请重新登陆');
		                    	setTimeout("location.href='../../sign_in.php'",1000);
							}else{
								layer.msg(ret.msg);
							}
						}
					});
				}
			})
			
			
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