<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>余额</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
	    <meta name="apple-mobile-web-app-capable" content="yes"><meta name="format-detection" content="telephone=no" />
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <link rel="stylesheet" href="css/common.css">
	    <link rel="stylesheet" type="text/css" href="css/remainder.css"/>
	</head>
	<body>
		<div class="remainderBox">
			<div class="balanceList" onclick="location.href='fineBalance.php'">余额明细</div>
			<div class="account_ban">
				<div class="accountName">账户余额（积分）</div>
				<div class="remainder">0</div>
			</div>
			<div class="recharge">充值</div>
			<div class="withdrawCash">提现</div>
		</div>
	</body>
</html>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script type="text/javascript">
	//获取用户基本信息
  		$.ajax({
  			type:"post",
  			url:commonsUrl+"api/gxsc/user/profile"+versioninfos,
  			data:{'ss':getCookie('openid')},
  			success:function(data){
  				if(data.code==1){
  					console.log(data);
  					$('.remainder').html(data.result.balance);//账户余额
  					
  				}else if(data.code==1011){
  					layer.msg('身份已失效，请重新绑定');
  					setTimeout(function(){location.href='register.php';},1000);
  				}else{
  					layer.msg(data.msg)
  				}
  			}
  		});
</script>