<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>顾客信息登记</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="css/common.css">
		<link rel="stylesheet" type="text/css" href="css/customerInformation.css" />
	</head>

	<body>
		<!-------------基本信息----------->
		<div class="basicInforBox">
			<div class="inforTitle">基本信息</div>
			<div class="phoneBox">
				<div class="phone">电话：</div>
				<div class="inBox"><input type="tel" class="phoneNum" maxlength="11" onkeyup="value=value.replace(/[^0-9.]/g,'') " /></div>
			</div>
			<div class="inforNameBox">
				<div class="user_name">姓名：</div>
				<div class="inName"><input type="text" class="names" /></div>
			</div>
			<div class="addressBox">
				<div class="address_word">地址：</div>
				<div class="us_address"><input type="text" class="userAddress" /></div>
			</div>
		</div>
		<!-------------选择礼品----------->
		<div class="giftBox">
			<div class="selectGift">选择礼品</div>
			<div class="shopSelectBox1">
				<div class="shop_img"><img src="images/gift1_03.png" /></div>
				<div class="giftName gifStyle" checkId="1">现金红包</div>
			</div>
			<div class="shopSelectBox2">
				<div class="shop_img"><img src="images/techan_03.png" /></div>
				<div class="giftName" checkId="2">时令土特产</div>
			</div>
			<div class="shopSelectBox1">
				<div class="shop_img"><img src="images/flour_04.png" /></div>
				<div class="giftName" checkId="3">爱菊面粉</div>
			</div>
			<div class="shopSelectBox2">
				<div class="shop_img"><img src="images/rice_03.png" /></div>
				<div class="giftName" checkId="4">爱菊大米</div>
			</div>
			<div class="shopSelectBox1">
				<div class="shop_img"><img src="images/egg_03.png" /></div>
				<div class="giftName" checkId="5">鸡蛋十枚</div>
			</div>
			<div class="shopSelectBox2">
				<div class="shop_img"><img src="images/xiangyou_03.png" /></div>
				<div class="giftName" checkId="6">香油一瓶</div>
			</div>
		</div>
		<!-------------员工确认----------->
		<div class="staffBox" style="clear: both;overflow: auto;">
			<div class="staffConfirm" style="margin-top: 20px;">员工确认</div>
			<div class="phoneBox">
				<div class="staffPhone">联系电话：</div>
				<div class="enInobx"><input type="tel" class="staffPhoneNum" maxlength="11" onkeyup="value=value.replace(/[^0-9.]/g,'') " /></div>
			</div>
			<div class="phoneBox">
				<div class="shop_names">店&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名：</div>
				<div class="shop_inName"><input type="text" class="shopNames" readonly="readonly"/></div>
			</div>
			<div class="phoneBox">
				<div class="eIn">员工姓名：</div>
				<div class="ename"><input type="text" class="employ_name" readonly="readonly"/></div>
			</div>

		</div>
		<div class="confirm_sub">确定</div>
	</body>

</html>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript">
	$(function() {
		$('.giftName').click(function() {
			$(this).addClass('gifStyle').parent().siblings().find(".giftName").removeClass("gifStyle");
		})

		$(".shop_img").click(function() {
			$(this).parent().find(".giftName").addClass("gifStyle").parent().siblings().find(".giftName").removeClass("gifStyle");
		})
	})
</script>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script type="text/javascript">
	$(function() {
		$('.phoneNum').blur(function() {
			var userPhoneNum = $('.phoneNum').val(); //获取用户手机号
			$.ajax({ //检查用户手机号码
				type: "post", //请求方式
				dataType: 'json',
				url: commonsUrl + 'api/gxsc/checkmemberreceive' + versioninfos, //请求接口
				data: {
					"user_mobile": userPhoneNum //用户id
				},
				success: function(data) {
					if(data.code == 1) { //请求成功
						if(data.result.tips) {
							layer.msg(data.result.tips);
						}
						//判断当用户未领取的时候直接跳转到领取页面						
						if(data.result.receive_time == 0) {
							layer.msg(data.result.tips);
							location.href = "enrollSuccess.php?user_id=" + data.result.user_id + "&git=" + data.result.tips;
						} else {
							$(".staffPhoneNum").blur(function() {
								var em_PhoneNum = $('.staffPhoneNum').val(); //员工手机号
								$.ajax({//检查员工信息
									type: "post",
									dataType: 'json',
									url: commonsUrl + 'api/gxsc/checkemployeeinfo' + versioninfos,
									data: {
										"user_mobile": em_PhoneNum //员工手机号
									},
									success: function(data) {
										console.log(data)
										if(data.code == 1) { //请求成功
											var con = data.result; //
											var agency_name = con.agency_name; //店名
											var user_name = con.user_name; //员工姓名
											//------------进行赋值---------------
											$('.shopNames').val(agency_name); //店名
											$('.employ_name').val(user_name); //员工姓名
											$('.confirm_sub').click(function() {
												//获取input框输入的值
												inputForNum = $('.phoneNum').val(); //获取用户手机号
												inputForName = $('.names').val(); //用户名
												inputAddress = $('.userAddress').val(); //地址
												giftId = $(".gifStyle").attr('checkId'); //礼品id
												inputStaffNum = $('.staffPhoneNum').val(); //员工手机号
												console.log(giftId);
												console.log(inputForNum + 'ddddd');
												console.log(inputForName + 'ddddd');
												console.log(inputAddress + 'ddddd');
												console.log(inputStaffNum + 'ddddd');
												if(!testTel(inputForNum) || inputForNum == '' || inputForNum == undefined || inputForNum == null) { //对输入的值进行判断
													layer.msg("请输入正确的手机号码");
												} else if(inputForName == "" || inputForName == undefined) {
													layer.msg("姓名不能为空");
												} else if(inputAddress == "" || inputAddress == undefined) {
													layer.msg("地址不能为空");
												} else if(!testTel(inputStaffNum) || inputStaffNum == '' || inputStaffNum == undefined || inputStaffNum == null) {
													layer.msg("请输入正确的手机号码");
												} else {
													var formData = new FormData(); //创建一个空的formData对象用来保存变量参数
													formData.append("user_mobile", inputForNum);
													formData.append("name", inputForName);
													formData.append("address", inputAddress);
													formData.append("employee_mobile", inputStaffNum);
													formData.append("gift", giftId);
													layer.load(2);
													$.ajax({ //邀请新用户进店
														type: "post", //请求方式
														dataType: 'json',
														url: commonsUrl + 'api/gxsc/invitemember' + versioninfos, //请求接口
														data: formData, //请求参数（这里将参数都保存在formData对象中）
														processData: false, //因为data值是FormData对象，不需要对数据做处理。
														contentType: false, //默认为true,不设置Content-type请求头
														success: function(data) {
															console.log(data)
															layer.closeAll();
															if(data.code == 1) { //请求成功
																if(data.result.receive_time == 0) {
																	location.href = "enrollSuccess.php?user_id=" + data.result.user_id + "&git=" + data.result.tips;
																} else {
																	layer.msg(data.result.tips);
																}
															} else {
																layer.msg(data.msg);
																setTimeout(function() {
																	location.href = "enrollFail.php";
																}, 1000)
															}

														}
													});
												}
											})

										} else {
											layer.msg(data.msg);
										}

									}
								});
							})

						}
					} else {
						layer.msg(data.msg);
					}

				}
			});
		})
	})
</script>
