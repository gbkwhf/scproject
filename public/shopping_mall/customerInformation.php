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
		<link rel="stylesheet" type="text/css" href="css/customerInformation.css"/>
	</head>

	<body>
		<!-------------基本信息----------->
		<div class="basicInforBox">
			<div class="inforTitle">基本信息</div>
			<div class="phoneBox">
				<div class="phone">电话：</div>
				<div class="inBox"><input type="tel" class="phoneNum" maxlength="11" onkeyup="value=value.replace(/[^0-9.]/g,'') "/></div>
			</div>
			<div class="inforNameBox">
				<div class="user_name">姓名：</div>
				<div class="inName"><input type="text" class="names"/></div>
			</div>
			<div class="addressBox">
				<div class="address_word">地址：</div>
				<div class="us_address"><input type="text" class="userAddress"/></div>
			</div>
		</div>
		<!-------------选择礼品----------->
		<div class="giftBox">
			<div class="selectGift">选择礼品</div>
			<div class="shopSelectBox1">
				<div class="shop_img"><img src="images/gift1_03.png"/></div>
				<div class="giftName gifStyle" checkId="1">现金红包</div>
			</div>
			<div class="shopSelectBox2">
				<div class="shop_img"><img src="images/techan_03.png"/></div>
				<div class="giftName" checkId="2">时令土特产</div>
			</div>
			<div class="shopSelectBox1">
				<div class="shop_img"><img src="images/flour_04.png"/></div>
				<div class="giftName" checkId="3">爱菊面粉</div>
			</div>
			<div class="shopSelectBox2">
				<div class="shop_img"><img src="images/rice_03.png"/></div>
				<div class="giftName" checkId="4">爱菊大米</div>
			</div>
		</div>
		<!-------------员工确认----------->
		<div class="staffBox" style="clear: both;">
			<div class="staffConfirm" style="margin-top: 30px;">员工确认</div>
			<div class="phoneBox">
				<div class="staffPhone">员工手机号：</div>
				<div class="inBox"><input type="tel" class="staffPhoneNum" maxlength="11" onkeyup="value=value.replace(/[^0-9.]/g,'') "/></div>
			</div>
		</div>
		<div class="confirm_sub">确定</div>
	</body>

</html>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript">
	$(function(){
		$('.giftName').click(function(){
			$(this).addClass('gifStyle').parent().siblings().find(".giftName").removeClass("gifStyle");
		})
		
		$(".shop_img").click(function(){
			$(this).parent().find(".giftName").addClass("gifStyle").parent().siblings().find(".giftName").removeClass("gifStyle");
		})
	})
</script>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script type="text/javascript">
	$(function(){
		$('.confirm_sub').click(function() {
			//获取input框输入的值
			inputForNum = $('.phoneNum').val();//获取用户手机号
			inputForName = $('.names').val();//用户名
			inputAddress = $('.userAddress').val();//地址
			giftId=$(".gifStyle").attr('checkId'); //礼品id
			inputStaffNum = $('.staffPhoneNum').val();//员工手机号
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
			}else {
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
							if (data.result.receive_time==0) {
								location.href="enrollSuccess.php?user_id=" + data.result.user_id;
							} else{
								layer.msg(data.result.tips);
							}
							
						} else {
							layer.msg(data.msg);
							setTimeout(function(){
								location.href="enrollFail.php";
							},1000)
						}

					}
				});
			}
		})
	})
</script>