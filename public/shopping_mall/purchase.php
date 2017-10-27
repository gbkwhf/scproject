<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>结算</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"><meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" type="text/css" href="css/swiper.min.css"/>
    <link rel="stylesheet" href="css/purchase.css">
</head>

<body>

	<div class="wrapper">
		<header onclick="location.href='receiving_address.php'">
			<img src="images/edit.png" width="14px" class="edit-icon" />
			<p class="adress">收货地址：陕西省西安市高新区丈八一路绿地蓝海大厦东1002D</p>
			<div class="user-name" ><img src="images/user-icon.png" width="13px"/><p>张先生</p></div>
			<div class="user-phone" ><img src="images/telephone.png" width="15px"/><p>13619894965</p></div>
			<img src="images/car.png" width="103px" class="illustration" />								
		</header>
		<div class="js-details">
			<div class="tit-box">
				<img src="images/left-line.png" width="55"/>
				<p>结算详情</p>
				<img src="images/right-line.png" width="55"/>
			</div>
			<div class="commodity-align">
				<div class="swiper-container">
			        <div class="swiper-wrapper">
			            <div class="swiper-slide">
			            	<img src="images/zyyp.png" width="100%"/>
			            	<p>¥388.00</p>
			            	<span>*7</span>
			            </div>
			            <div class="swiper-slide"><img src="images/zyyp.png" width="100%"/></div>
			            <div class="swiper-slide"><img src="images/zyyp.png" width="100%"/></div>
			            <div class="swiper-slide"><img src="images/zyyp.png" width="100%"/></div>
			            <div class="swiper-slide"><img src="images/zyyp.png" width="100%"/></div>
			            <div class="swiper-slide"><img src="images/zyyp.png" width="100%"/></div>
			        </div>
			    </div>
			    <div class="commodity-exp">
			    	<p>共10件</p>
			    	<img src="images/right-arrow.png" width="8"/>
			    </div>
			</div>	
		</div>
		<div class="explain-buy">
			<h4>购买发货须知：</h4>
			<ul class="xz-list">
				<li><em>1.</em>店员生成二维码让顾客扫码支付，货店内用户取货</li>
				<li><em>2.</em>用户生成订单二维码店员扫码帮助创建订单货店内取货</li>
				<li><em>3.</em>食品类货品退货请咨询客服</li>
			</ul>
		</div>
		<footer>
			<p class="actual-payment">实付款：<span>¥3688.00</span></p>
			<div class="buy-operation">
				<!--<p onclick="scancode()">进店扫码<span>到门店付款</span></p>-->
				<p onclick="createOrder()"><span style="padding: 10px 0px 11px ;">替用户创建订单</span></p>
				<i></i>
				<em onclick="submit()">提交</em>
			</div>
		</footer>
	</div>
	<!--扫码付款-->
	<div id="codemask">
		<div class="qccode"></div>
	</div>
	<!--线下收款-->
	<div class="xxsk">
		<h4>线下收款</h4>
		<p>员工输入会员手机号直接完成付款</p>
		<input type="text" placeholder="请输入会员手机号" maxlength="11" onkeyup="value=value.replace(/[^0-9.]/g,'') "  />
		<div class="btn-box-pop">
			<a href="javascript:void(0);" class="cancel" onclick="cancelPop()">取消</a>
			<a href="javascript:void(0);" class="confirm">确认</a>
		</div>
	</div>
</body>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/swiper.min.js"></script>
<script src="js/jquery.qrcode.min.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script>
	/*Initialize Swiper*/
	var swiper = new Swiper('.swiper-container', {
        slidesPerView: 3,
        paginationClickable: true,
        spaceBetween: 30,
        freeMode: true
    });
	
	//用户微信扫码付款
	function scancode(){
//		生成二维码
        $('.qccode').qrcode({
            width: 130, //宽度
            height:130, //高度
            text:'YSBTDDID_'  //任意内容
        });
        
        layer.open({
        	type: 1,
        	title:false,
        	closeBtn:false,
        	shadeClose: false,
        	content: $('#codemask')
        })
	}
	
	//替用户创建订单
	function createOrder(){
		layer.open({
			type:1,
			closeBtn:false,
			shadeClose:false,
			title:false,
			content:$('.xxsk')
		})
	}
	
//	提交订单
	function submit(){
		
	}
	
	
</script>
<style type="text/css">
	/*body .layui-layer{border-radius:10px;}*/
	.layui-layer.layui-anim.layui-layer-page{
		border-radius: 5px;
	} 
</style>
</html>