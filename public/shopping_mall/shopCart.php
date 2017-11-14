<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>购物车</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/shopCart.css" />
	</head>
    <script>
        //解决IOS微信webview后退不执行JS的问题
        window.onpageshow = function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        };
    </script>

	<body>
		<!--头部-->
		<!--<div class="shopHeader">
			购物车
		</div>-->

		<!--购物车内容-->
		<div class="shopCartBox">
			<div id="deleIddd" style="background-color: #faf9f9;height: 42px;margin-bottom: 12px;display: none;">
				<div class="deleteBtn delete">删除</div>
			</div>
			<div style="margin-bottom: 12px;"></div>
			<!--购物车列表-->
		</div>
		<div class="popBox" style="display: none;">
			<p style="text-align: center;margin-top: 14px;margin-bottom: 14px;">确认要删除该商品吗？</p>
			<div class="cancels" id="cancelsId">取消</div>
			<div class="confirm" id="confirmId">确定</div>
		</div>
        <div class="submitbox" style="display: none;">
            <p style="text-align: center;margin-top: 14px;margin-bottom: 14px;padding: 0 15px;">返利区不够1280 确认提交吗？</p>
            <div class="cancels" style="text-align: center" id="cancelsubmit">取消</div>
            <div class="confirm" style="text-align: center" id="confirmsubmit">确定</div>
        </div>
		<!--底部-->
		<div class="shopFotter">
			<div class="shopForLeft0">
				<label class="label2"><input class="input1" type="checkbox" style="position: absolute;left: 99999px;"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
			</div>
			<div class="shopForLeft3">全选</div>
			<div class="shopForLeft2">
				<span style="color: #333333;font-size: 13px;">合计：<span style="color:#d47233;font-size: 10px;">￥</span>
				<span style="color: #d47233;font-size: 14px;" class="totalPrice">0.00</span>
				</span>
			</div>
			<div class="shopForLeft1 jiesuanStyle">结算</div>
		</div>
        <input type="hidden" id="returnprice"/>
        <input type="hidden" id="noreturnprice"/>
	</body>

</html>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/config.js"></script>
<script type="text/javascript" src="js/shopCart.js"></script>
<script type="text/javascript" src="js/layer/layer.js"></script>
