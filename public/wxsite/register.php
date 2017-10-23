<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>预约挂号</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>		
		<link rel="stylesheet" type="text/css" href="css/mobiscroll/mobiscroll.core-2.5.2.css" />
		<link rel="stylesheet" type="text/css" href="css/mobiscroll/mobiscroll.animation-2.5.2.css" />
		<link rel="stylesheet" type="text/css" href="css/mobiscroll/mobiscroll.android-ics-2.5.2.css" />
		<link rel="stylesheet" type="text/css" href="css/area.css" />
		<link rel="stylesheet" type="text/css" href="css/register.css" />
	</head>
	<body>
		<div class="container">
			<div class="content">
				<div class="form-box">
					<div class="unit ghtype">
						<div class="pic">
							<img src="image/types-icon.png" width="15" style="margin-top: 20.5px;" />
						</div>					
						<label for="type">类别：</label>
						<input type="text" placeholder="请选择" name="type" id="type" readonly="readonly" />
					</div>
					<div class="unit city">
						<div class="pic">
							<img src="image/province-icon.png" width="18" />
						</div>
						<p>省市：</p>
						<div id="province" onclick="ssq()">请选择</div>
					</div>
					<!--<div class="unit">
						<div class="pic">
							<img src="image/city-icon.png" width="18" />
						</div>
						<p>市区：</p>
						<div id="city"></div>
					</div>-->
					<div class="unit">
						<div class="pic">
							<img src="image/address-icon.png" width="15" style="margin-top: 18px;" />
						</div>
						<label for="hospital">医院：</label>
						<input type="text" placeholder="请选择" name="hospital" id="hospital" readonly="readonly" />
					</div>
					<div class="unit">
						<div class="pic">
							<img src="image/department-icon.png" width="16" />
						</div>
						<label for="department">科室：</label>
						<input type="text" placeholder="请选择" name="department" id="department" readonly="readonly" />
					</div>
				</div>
				<p class="grayline"></p>
				<div class="form-box">
					<div class="units">
						<label for="user">联系人：</label>
						<input type="text" placeholder="请填写姓名" name="user" id="user" maxlength="6" />
					</div>
					<div class="units">
						<label for="phone">手机号码：</label>
						<input type="text" placeholder="请填写联系方式" name="phone" id="phone" maxlength="11" onkeyup="this.value=this.value.replace(/\D/g,'')" />
					</div>
					<div class="units">
						<label for="time">就诊时间：</label>
						<input type="text" placeholder="请选择" readonly="readonly" id="time" name="time" />
					</div>
					<textarea placeholder="备注：(选填)对本次交易的说明或自己的要求" id="remark" rows="3"></textarea>
				</div>
				<p class="grayline"></p>
			</div>			
			<a href="javascript:;" class="submit">提交挂号</a>
		</div>
		<!--类别弹窗-->
		<ul class="popup" style="display: none;width: 210px;">
		</ul>
		<!--医院弹窗-->
		<ul class="pop" style="display: none;width: 210px;">
		</ul>
		<!--科室弹窗-->
		<ul class="popups" style="display: none;width: 210px;">
		</ul>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script src="js/layer/layer.js"></script>		
		<script src="js/mobiscroll/mobiscroll.core-2.5.2.js"></script>
		<script src="js/mobiscroll/mobiscroll.core-2.5.2-zh.js"></script>
		<script src="js/mobiscroll/mobiscroll.datetime-2.5.1.js"></script>
		<script src="js/mobiscroll/mobiscroll.datetime-2.5.1-zh.js"></script>
		<script src="js/mobiscroll/mobiscroll.android-ics-2.5.2.js"></script>
		<script src="js/area.js"></script>
		<script>
		
			session = getCookie('session');
			session=session.substr(1,session.length-2);
			if(!session){
				session=""
			}
			
			function ssq(){
				$("#province").css("color","#4d4d4d")
			}
			
			winH=$(window).height();
			contH=$(".content").height();
			if(winH-contH>59){
				$(".submit").addClass("fixed");
			}
			
			//地区联动
			$(".city").click(function(){
				$('.container').hide();
		        var pcid = $('.city').children('#province').attr('id');
		        var container = $('.container').attr('class');
		        setCookie('pcVal',pcid,7);
		        setCookie('container',container,7);
		        getProvinceBuy();
		    });
				
			//医院类别
			$.ajax({
				type:"post",
				url:commonUrl + 'api/stj/service_order/hospital_class'+versioninfo,
				data:{
					'ss':session,
					'type':2
				},
				success:function(data){	
					console.log(data);
					if(data.code==1){
//						console.log(data);
						html="";
						for(var i=0;i<data.result.length;i++){
							html+='<li tyid="'+data.result[i].id+'" >'+data.result[i].name+'</li>';
						}
						$(".popup").html(html);
						
						//弹窗
						$(".ghtype").click(function(){
							layer.open({
								type: 1,
								closeBtn: 0, //不显示关闭按钮
								title: false,
								shadeClose: true, //开启遮罩关闭
								content: $(".popup")
							})
						});
						
						$(".popup li").click(function(){
							var type=$(this).text();
							var tyid=$(this).attr("tyid");
							hostype=$("#type").val(type);
							$("#type").attr("tyid",tyid);
							layer.closeAll();
						});
					}else{
						layer.msg(data.msg);
					}
				}
			});
			//医院
	
			$("#hospital").click(function(){
				cityV=$("#province").text();
				hostype=$("#type").val();								
				if(hostype==""){
					layer.msg("请先选择类别")
				}else if(cityV=="请选择"){
						layer.msg("请先选择省市")
				}else{
						cityId=$("#province").attr('vl');						
						tyid=$("#type").attr("tyid");
						$.ajax({							
							type:"post",
							url: commonUrl+'api/stj/org_distribution/therr_list'+versioninfo,							
							data:{
								'city':cityId,
								'class_type':tyid,
								'type':1								
							},
							success:function(ret){
								console.log(ret);
								if(ret.code==1){
									console.log(ret);
									if(ret.result.length==0){
										layer.msg("该城市下没有医院")
									}else{
										html="";
										for(var i=0;i<ret.result.length;i++){
											html+='<li hosid="'+ret.result[i].id+'" >'+ret.result[i].name+'</li>';
										}
										$(".pop").html(html);
										
										//弹窗
										layer.open({
											type: 1,
											closeBtn: 0, //不显示关闭按钮
											title: false,
											shadeClose: true, //开启遮罩关闭
											content: $(".pop")
										})
										
										$(".pop li").click(function(){
											var hospe=$(this).text();
											var hosid=$(this).attr("hosid");
											hostype=$("#hospital").val(hospe);
											$("#hospital").attr("hosid",hosid);
											layer.closeAll();
										});
									}
									
									
								}else{
									layer.msg(ret.msg);
								}
							}
						});
					}
			})
			
			//科室
				$.ajax({
					type:"post",
					url: commonUrl+'api/stj/getrecollection'+versioninfo,
					success:function(ret){
//						console.log(ret);
						if(ret.code==1){
							console.log(ret);
							html="";
							for(var i=0;i<ret.result.length;i++){
								html+='<li getid="'+ret.result[i].id+'" >'+ret.result[i].name+'</li>';
							}
							$(".popups").html(html);
							
							//弹窗
							$("#department").click(function(){
								layer.open({
									type: 1,
									closeBtn: 0, //不显示关闭按钮
									title: false,
									shadeClose: true, //开启遮罩关闭
									content: $(".popups")
								})
							});
							
							$(".popups li").click(function(){
								var getpe=$(this).text();
								var getid=$(this).attr("getid");
								$("#department").val(getpe);
								$("#department").attr("getid",getid);
								layer.closeAll();
							});
							
						}else{
						layer.msg(ret.msg);
					}
					}
				});
			
			//日期
			$(function() {
			
				var currYear = (new Date()).getFullYear();
				var opt = {};
				opt.date = {
					preset: 'date'
				};
				//opt.datetime = { preset : 'datetime', minDate: new Date(2012,3,10,9,22), maxDate: new Date(2014,7,30,15,44), stepMinute: 5  };
				opt.datetime = {
					preset: 'datetime'
				};
				opt.time = {
					preset: 'time'
				};
				opt.default = {
					theme: 'android-ics light', //皮肤样式
					display: 'modal', //显示方式 
					mode: 'scroller', //日期选择模式
					lang: 'zh',
//					startYear: currYear - 10, //开始年份
					endYear: currYear + 50 ,//结束年份
					minDate: new Date()	//从当前年，当前月，当前日开始
				};   
				var optDateTime = $.extend(opt['datetime'], opt['default']);
				var optTime = $.extend(opt['time'], opt['default']);
				$("#time").mobiscroll(optDateTime).datetime(optDateTime);
				
			});
			
			$(".submit").click(function(){
				
				var department_id = $("#department").attr("getid");
				var hospital_class = $("#type").attr("tyid");
				var hospital_id = $("#hospital").attr("hosid");
				
				var department_v=$("#department").val();
				var hospital_t=$("#type").val();
				var hospital_v=$("#hospital").val();			
				var mobile = $("#phone").val();
				var mobilelength=mobile.length;
				var name = $("#user").val();;
				var time = $("#time").val();
				var service_content=$("#remark").val();
//				console.log(service_content);

				var myreg = /^1[034578][0-9]{9}$/; //正则判断手机号是否有效

				if(department_v==""||hospital_t==""||hospital_v==""||mobile==""||name==""||time==""){
					layer.msg("请填写完整")
				}else if(mobilelength<11||!myreg.test($("#phone").val())){
                	layer.msg("请输入11位有效手机号")
                }else{
                	
                	if(session==""){
						//校验是否有session
						location.href="sign_in.php";
					}
                	
					$.ajax({ 
						type:"post",
						url:commonUrl+'api/stj/service_order/sub_order'+versioninfo,
						data:{
							'department_id':department_id,
							'hospital_class':hospital_class,
							'hospital_id':hospital_id,
							'mobile':mobile,
							'name':name,
							'open_type':2,
							'service_content':service_content,
							'ss':session,
							'time':time
						},
						success:function(data){
							if(data.code==1){
								console.log(data);
								layer.msg("提交成功");
								orderlist=function(){
									location.href="service_record.php"
								}
								setInterval(orderlist,2000)
									
							}else if(data.code==1011){
								layer.msg('该用户登陆数据已过期，请重新登陆');
			                    setTimeout("location.href='sign_in.php'",1000);
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