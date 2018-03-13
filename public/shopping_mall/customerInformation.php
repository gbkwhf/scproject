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
				<div class="shop_names">店&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名：</div>
				<div class="shop_inName"><input type="text" class="shopNames" readonly="readonly" /></div>
			</div>
			<div class="phoneBox">
				<div class="eIn">员工姓名：</div>
				<div class="ename"><input type="text" class="employ_name" readonly="readonly" /></div>
			</div>

		</div>
		<div class="confirm_sub">确定</div>
	</body>
</html>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script type="text/javascript" src="js/customerInformation.js"></script>