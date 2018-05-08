<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>购物车</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="css/common.css">
		<link rel="stylesheet" type="text/css" href="css/newShop_cart.css" />
		<link rel="stylesheet" type="text/css" href="css/commfooter.css"/>
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
		<!---->
		<div class="shopBox1">
			
			
			
		</div>
		<!--全选-->
		<div class="bottomBox">
			<!--左边的选择部分-->
			<div class="checkLeft1">
				<label class="label1">
					<input  type="checkbox"  class="input1 oneParent"   />全选
				</label>
			</div>
			<!--中间的商铺名称-->
			<div class="storeName1">合计:<span class="totalPrice"></span>元</div>
			<!--右边的返回内容-->
			<div class="storeBack1">结算</div>
		</div>
		
		<div class="popBox" style="display: none;">
			<p style="text-align: center;margin-top: 14px;margin-bottom: 14px;">确认要删除该商品吗？</p>
			<div class="cancels" id="cancelsId">取消</div>
			<div class="confirm" id="confirmkk">确定</div>
		</div>
        <div class="submitbox" style="display: none;">
            <p style="text-align: left;margin-top: 14px;margin-bottom: 14px;padding: 11px 15px;">返利区不够1280 确认提交吗？</p>
            <div class="cancels" style="text-align: center" id="cancelsubmit">取消</div>
            <div class="confirm" style="text-align: center" id="confirmsubmit">确定</div>
        </div>
        <input type="hidden" id="returnprice"/>
        <input type="hidden" id="noreturnprice"/>
		<!--<div id="commId"></div>-->
		<!---------底部----->
		<div class="shopBottom">
			<div class="memberIndex" onclick="location.href='memberPages.php'">
				<dl>
					<dt><img src="images/in1.jpg"/></dt>
					<dd style="color: #333333;">首页</dd>
				</dl>
			</div>
			<div class="shopCar">
				<dl>
					<dt>
						<img src="images/che2.jpg"/>
						<span>0</span>
					</dt>
					<dd style="color: #e63636;">购物车</dd>
				</dl>
			</div>
			<div class="personal" onclick="location.href='personal_center.php'">
				<dl>
					<dt><img src="images/my.png"/></dt>
					<dd style="color: #333333;">我的</dd>
				</dl>
			</div>
		</div>
	</body>

</html>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/layer/layer.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/config.js"></script>
<script type="text/javascript" src="js/newShop_cart.js"></script>


