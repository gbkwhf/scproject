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
		<title>详情</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="../../css/common.css"/>		
		<link rel="stylesheet" type="text/css" href="../../css/health_management_details.css" />
	</head>
	<body>
		<div class="containers">
			<div class="content">
				<div class="box">
					<!--<p class="title">健康体检</p>
					<p class="grayline"></p>
					<div class="cont-exp">
						<p class="titles">详细介绍</p>
						<div class="explain">健康体检是用医学手段和方法进行身体检查，这里 包括临床各科室的基本检查，包括超声、心电、放 射等医疗设备检查，还包括围绕人体的</div>
					</div>
					<p class="grayline"></p>
					<div class="form-box borbot">
						<label for="select">选择服务</label>
						<input type="text" placeholder="请选择" name="select" id="select" readonly="readonly" />
					</div>
					<p class="grayline"></p>
					<div class="price">
						<div class="form-box borbot">
							<p>需付款</p>
							<span>¥<mark>200.00</mark></span>
						</div>
					</div>-->				
				</div>
				<p class="grayline"></p>
				<div class="cont-box">
					<div class="form-box">
						<label for="user">联系人</label>
						<input type="text" placeholder="请填写姓名" name="user" id="user" maxlength="6" />
					</div>
					<div class="form-box">
						<label for="phone">手机号码</label>
						<input type="text" placeholder="请填写联系方式" name="phone" id="phone" maxlength="11" onkeyup="this.value=this.value.replace(/\D/g,'')" />
					</div>
					<div class="form-box">
						<label for="remark" class="bz">备注：</label>
						<input type="text" placeholder="(选填)对本次交易的说明或自己的要求" name="remark" id="remark" />
					</div>
				</div>
			</div>			
			<a href="javascript:;" class="submit fixed" >提交</a>
			<!--弹窗-->
			<ul class="popup" style="display: none;width: 180px;">
				<!--<li>套餐</li>-->
			</ul>
		</div>
		<script src="../../js/jquery.min.js"></script>
		<script src="../../js/common.js"></script>
		<script src="../../js/layer/layer.js"></script>
		<script>
			session = getCookie('session');
			session=session.substr(1,session.length-2);
			console.log(session);
			if(!session){
				session=""
			}
			console.log(session);
			winW=$(window).width();
			labW=$(".form-box label").width();
			$(".form-box input").width(winW-labW-45);
			bzW=$(".bz").width();
			$("#remark").width(winW-bzW-35);
			
			
			
			
			datas=JSON.parse(localStorage.getItem("datas"));
//			console.log(datas);
			
			sid=$_GET['id'];
			name=$_GET['name'];
//			console.log(sid);
			$.ajax({
				type:"post",
				url:commonUrl+'api/stj/service_order/option_list'+versioninfo,
				data:{
					'sid':sid,
					'ss':session
				},
				success:function(data){
					if(data.code==1){
//						console.log(data);
						dataStr=localStorage.setItem("dataStr",JSON.stringify(data));
						tit=datas.result.list[name].title;
						html="";
						html+='<p class="title">'+tit+'</p>';
						html+='<p class="grayline"></p>';
						html+='<div class="cont-exp">';
						html+='	<p class="titles">详细介绍</p>';
						html+='	<div class="explain">'+data.result.content+'</div>';
						html+='</div>';
						html+='<p class="grayline"></p>';
						html+='<div class="form-box borbot ser" onclick="tk()">';
						html+='	<label for="select">选择服务</label>';
						html+='	<input type="text" placeholder="请选择" name="select" id="select" readonly="readonly"  />';
						html+='</div>';
						html+='<p class="grayline"></p>';
						html+='<div class="price"></div>';
						pophtml="";
						for(var i=0;i<data.result.oplist.length;i++){
							pophtml+='<li onclick="listi('+data.result.oplist[i].id+','+i+')">'+data.result.oplist[i].title+'</li>';
						}
						$(".popup").html(pophtml);					
						$(".box").html(html);
								}
							}
						})
			function tk(){
				layer.open({
					type: 1,
					closeBtn: 0, //不显示关闭按钮
					title: false,
					shadeClose: true, //开启遮罩关闭
					content: $(".popup")
				});														
			}
			
			function listi(fwid,num){
				dataStr=JSON.parse(localStorage.getItem("dataStr"));
//				console.log(dataStr);
				listhtml="";
				listhtml+='<div class="form-box borbot">';
				listhtml+='	<p>需付款</p>';
				listhtml+='	<span>¥<mark>'+dataStr.result.oplist[num].price+'</mark></span>';
				listhtml+='</div>';
				$(".price").html(listhtml);
				var text=dataStr.result.oplist[num].title;
				$("#select").val(text);
				layer.closeAll();
			}
			
			$(".submit").click(function(){
				
				var sel=$("#select").val();
				var user=$("#user").val();
				var phone=$("#phone").val();
				var phonelength=phone.length;
				var remark=$("#remark").val();
				dataStr=JSON.parse(localStorage.getItem("dataStr"));
				console.log(dataStr);			

				var myreg = /^1[034578][0-9]{9}$/; //正则判断手机号是否有效
	
				if(sel==""||user==""||phone==""){
					layer.msg("请填写完整");
				}else if(phonelength<11||!myreg.test($("#phone").val())){
                	layer.msg("请输入11位有效手机号")
                }else{
                	
                	if(session==""){
						//校验是否有session
						location.href="../../sign_in.php";
					}
                	console.log(session);
					for(var i=0;i<dataStr.result.oplist.length;i++){
						datatit=dataStr.result.oplist[i].title;
						if(sel==datatit){
							optionid=dataStr.result.oplist[i].id;
						}
					}
					$.ajax({
						type:"post",
						url:commonUrl+'api/stj/service_order/sub_order'+versioninfo,
						data:{
							'open_type':1,
							'mobile':phone,
							'name':user,
							'option_id':optionid,
							'ss':session,
							'service_content':remark
						},
						success:function(data){
							if(data.code==1){
								console.log(data);

								if(data.result.pay==1){
									layer.msg("提交成功，去支付");
									order_id=data.result.order_id;
									console.log(order_id);                           		

									$.ajax({
										type:"post",
										url:commonUrl+'api/stj/pay/service'+versioninfo,
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
											                	
											                		location.replace('../../service_record.php');
											                    
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
										location.href="../../service_record.php"
									}
									setInterval(orderlist,2000)
								}
								
								
							}else if(data.code==1011){
								layer.msg('该用户登陆数据已过期，请重新登陆');
		                    	setTimeout("location.href='../../sign_in.php'",1000);
							}else{
								layer.msg(data.msg);								
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