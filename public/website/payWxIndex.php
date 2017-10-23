<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>微信扫码支付</title>
		<link rel="stylesheet" href="css/common.css">
        <style>
            .bg{
                background: url("images/bg1.png");
                border-top: 1px solid #b2bbbe;
                padding: 85px 0;
                text-align: center;
            }
            .wxbox{
                width: 970px;
                border-top:5px solid #b2bbbe;
                margin:20px auto;
            }
            .twocode{
                width: 260px;
                height: 260px;
                margin:40px auto;
            }

        </style>
	</head>
	<body>
		<?php include 'header.php' ?>
        <div class="bg">
            <img src="images/pay/weixinpay.png" alt="" style=""/>
            <div class="wxbox">
                <div class="twocode"></div>
                <div style="padding: 18px 22px;margin: 0 auto;width: 214px;border:1px solid #21c0d5;border-radius: 10px">
                    <img src="images/pay/scan.png" alt="" style="float: left;width: 49px;padding-right: 10px"/>
                    <div style="float: left;text-align:left;line-height:1.7em;color:#21c0d5">请使用微信“扫一扫” <BR>扫描二维码支付</div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
		<?php include 'footer.php' ?>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
        <script type="text/javascript" src="js/layer/layer.js"></script>
        <script type="text/javascript" src="js/function.js"></script>
        <script type="text/javascript" src="js/jquery.qrcode.min.js"></script>
		<script>
            $('.twocode').qrcode({
                width: 260, //宽度
                height:260, //高度
                text: unescape($_GET['url']) //任意内容
            });
            type=$_GET['type'];
            pay = function pay(){
                //轮询订单状态接口
                $.ajax({
                    type:'GET',
                    url:commonUrl + 'api/stj/getorderstate/'+$_GET['oid'],
                    success:function(data) {
                        if(data.code == 1){
                            if(data.result == 0){

                            }else if(data.result == 1){
                                clearInterval(Interval);
                                layer.msg("支付成功！");
                                if(type==1){
                                	setTimeout('location.href="personal_center.php?queue=2"',1000);
                                }else if(type==2){
                                	setTimeout('location.href="personal_center.php?queue=4"',1000);
                                }
                                
                            }
                        }
                    }
                });
            }
            Interval = setInterval('pay()',2000);
		</script>
	</body>
</html>
