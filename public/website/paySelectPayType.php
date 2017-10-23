<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>选择支付方式</title>
		<link rel="stylesheet" href="css/common.css">
        <style>
            .bg{
                background: url("images/bg1.png");
                border-top: 1px solid #b2bbbe;
                padding-bottom: 85px;
            }
            .content{
                padding-top: 25px;
                padding-bottom: 25px;
            }
            .payp{
                color: #585858;
                font-size: 20px;
                line-height: 24px;
                padding-left: 42px;
                background: url("images/warn.png") no-repeat left center;
            }
            .bg span{
                color:#586868;
            }
            .paybox{
                width: 179px;
                height: 52px;
                border: 1px solid #d2d1d1;
                float: left;
                margin-right: 40px;
                cursor: pointer;

            }
            .wxpay{
                background: url("images/pay/wxImg.png") center center no-repeat #fff;
            }
            .alipay{
                background: url("images/pay/alipayImg.png") center center no-repeat #fff;
            }
            .gotoPay{
                margin-top: 30px;
                width: 144px;
                height: 44px;
                text-align: center;
                background: #d0d0d0;
                color:#9b9b9b;
                line-height: 44px;
                font-size: 16px;
            }
        </style>
	</head>
	<body>
		<?php include 'header.php' ?>
        <div class="bg">
            <div class="content">
                <div class="payp">请你尽快支付，以便订单快速处理！</div>
                <div style="margin:15px 0 10px 0;padding-left: 41px;"><span style="font-size: 14px;">订单号：</span><span style="font-size:14px;" class="order_id"></span><span style="margin-left: 60px;">名称：</span><span  class="order_name"></span></div>
                <div style="margin:10px 0 20px 0;padding-left: 41px;"><span style="font-size: 14px;">待支付：</span><span style="font-size:14px;color:#ff0000">￥</span><span style="font-size: 16px;color:#ff0000" class="order_price"></span></div>
            </div>
            <div style="width: 1016px;height: 8px;background: #ccc;margin:0 auto"></div>
            <div style="width: 960px;margin: 0 auto;background: #fff;padding: 80px 20px;position: relative;top:-2px;z-index: 1">
                <div class="paybox wxpay" val="5"></div>
                <div class="paybox alipay" val="4"></div>
                <div class="clearfix"></div>
                <div style="width: 980px;height: 1px;background: #c6c7c7;margin-top:30px;"></div>
                <div class="gotoPay" style="float: left;">
                    <span>继续支付</span>
                </div>
                <div class="typeShow" style="float: left;margin-top: 42px;margin-left: 40px;color:#ff0000;font-size: 13px;">请选择支付方式</div>
                <div class="clearfix"></div>
            </div>
        </div>
		<?php include 'footer.php' ?>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
        <script type="text/javascript" src="js/layer/layer.js"></script>
        <script type="text/javascript" src="js/function.js"></script>
		<script>
            $('.order_id').html($_GET['oid']);
            $('.order_name').html(decodeURI($_GET['name']));
            $('.order_price').html($_GET['price']);
            $('.paybox').click(function(){
                $(this).css('border','1px solid #21c0d5');
                $(this).siblings('.paybox').css('border','1px solid #d2d1d1');
                $('.typeShow').hide();
                $('.gotoPay').css('background','#21c0d5');
                $('.gotoPay').css('cursor','pointer');
                $('.gotoPay span').css('color','#fff');
                val = $(this).attr('val');
                $('.gotoPay').attr('onclick','gotoPay('+val+')');

            });
			function gotoPay(filling_type){
                layer.load(2);
                sessionArr = getCookie('sessionArr');
                if($_GET['type']==1){
                    //服务订单
                    $.ajax({
                        async:false,
                        type:'POST',
                        url:commonUrl + 'api/stj/pay/service'+versioninfo,
                        data:{
                            'ss':JSON.parse(sessionArr).session,
                            'order_id':$_GET['oid'],
                            'filling_type':filling_type
                        },
                        success:function(data) {
                            layer.closeAll();
                            console.log(data);
                            if (data.code == 1) {
                                if(filling_type == 4){
                                    //ali
                                    window.open(data.result,"_blank");
                                }else if(filling_type == 5){
                                    //wx
                                    url = escape(data.result);
                                    window.open("payWxIndex.php?url="+url+"&type=1&oid="+$_GET['oid'],"_blank");
//                                    window.location.href="http://www.baidu.com";

                                }
                            } else if (data.code == "1011") {
                                layer.msg('帐号在其他设备登录，请重新登录');
                                setTimeout("removeCookie('sessionArr');location.href='login.php'", 1000);
                            } else {
                                layer.msg(data.msg);
                            }
                        }
                    });
                }else if($_GET['type']==2){
                    //商品订单
                    $.ajax({
                        async:false,
                        type:'POST',
                        url:commonUrl + 'api/stj/pay/goods'+versioninfo,
                        data:{
                            'ss':JSON.parse(sessionArr).session,
                            'order_id':$_GET['oid'],
                            'filling_type':filling_type
                        },
                        success:function(data) {
                            layer.closeAll();
                            console.log(data);
                            if (data.code == 1) {
                                if(filling_type == 4){
                                    //ali
                                    window.open(data.result,"_blank");
                                }else if(filling_type == 5){
                                    //wx
                                    url = escape(data.result);
                                    window.open("payWxIndex.php?url="+url+"&type=2&oid="+$_GET['oid'],"_blank");
                                }
                            } else if (data.code == "1011") {
                                layer.msg('帐号在其他设备登录，请重新登录');
                                setTimeout("removeCookie('sessionArr');location.href='login.php'", 1000);
                            } else {
                                layer.msg(data.msg);
                            }
                        }
                    });
                }

            }
		</script>
	</body>
</html>
