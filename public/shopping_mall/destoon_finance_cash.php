<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>申请提现</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes"><meta name="format-detection" content="telephone=no" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
        <link rel="stylesheet" type="text/css" href="css/common.css"/>
        <link rel="stylesheet" type="text/css" href="css/destoon_finance_cash.css"/>
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
		<div class="applyBox">
			<div class="desWhere">
				提现到微信
				<a href="withdrawals_record.php?identity=1">替用户提现记录</a>
			</div>
			<div class="desNav">
				<div class="desMoney">提现手机号</div>
				<div class="inMoney">+86<input type="text" maxlength="11" class="applyPhone" onkeyup="value=value.replace(/[^0-9.]/g,'') "/></div>
				<div class="tel_number"></div>
				<div class="desMoney" style="padding-top: 30px;">提现金额</div>
				<div class="inMoney">¥<input type="text" class="applyMoney" onkeyup="this.value=this.value.replace(/\D/g,'')" /></div>
				<div class="balance">
					<!--可提现金额：¥<span class="balanceMoney"></span>-->
				</div>
				<a href="javascript:;" class="desBtn">提现</a>
			</div>
		</div>
		<div class="inquiry">
			<ul class="explain-list">
				<li><em>1、</em>需线下向用户支付<span style="color: #d57232;"></span>元；</li>
				<li><em>2、</em>提现成功后会从用户余额扣除8%手续费。</li>
				<li><em>3、</em>请仔细核对提现金额和会员手机号，确认后不可撤销；</li>
			</ul>
			<div class="btn-box-pop">
				<a href="javascript:void(0);" class="cancel" onclick="cancelPop()">取消</a>
				<a href="javascript:void(0);" class="confirm">确认</a>
			</div>
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/layer/layer.js"></script>
		<script src="js/common.js"></script>
		<script src="js/config.js"></script>
		<script>

			$('.applyPhone').bind('input propertychange', function() {
			
		        if($(this).val().length==11){
		            if(!testTel($(this).val())){
		            	$(".tel_number").html("请输入正确的电话号码");
			            setTimeout(function(){
							$(".tel_number").html('');
							$('.applyPhone').val('');
						},2000);
		            }else{
		            	//获取用户可提现额度
						$.ajax({
							type:"post",
							url: commonsUrl + "api/gxsc/get/balance/by/mobile" + versioninfos,
							data:{'ss':getCookie('openid'),'mobile':$(this).val()},
							success:function(data){
								if(data.code==1){
									console.log(data);
									$('.balance').html('可提现金额：¥<span class="balanceMoney">'+(data.result.balance-data.result.balance*0.08).toFixed(2)+'</span>');
								}else{
									layer.msg(data.msg);
								}
							}
						});

		            }
		        	
		        }
	   		});

			
			//提现
			$('.desBtn').click(function(){
				var applyMoney = $('.applyMoney').val();
				var applyPhone = $('.applyPhone').val();
				if(applyMoney==""||applyPhone==""){
					layer.msg('完善信息后提交哦');
				}else{
					if(testTel(applyPhone)){
						var maxMoney = $('.balanceMoney').html();
						if(parseFloat(applyMoney)>parseFloat(maxMoney)){
							layer.msg('金额超过可转出额度');
						}else{
							$('.explain-list>li>span').text(applyMoney);
							layer.open({
								type:1,
								title:'确认提现到微信？',
								shadeClose:true,
								closeBtn:false,
								content:$('.inquiry')
							})
						}
					}else{
						layer.msg('请输入正确的手机号');
					}
				}
			})
			
			//确认提现
			$('.confirm').click(function(){
				$.ajax({
					
					type:'post',
					url:commonsUrl + 'api/gxsc/replace/user/withdraw/deposit' + versioninfos,
					data:{
						'mobile':$('.applyPhone').val(),
						'money':$('.applyMoney').val(),
						'ss':getCookie('openid')
					},
					success:function(data){
						if(data.code==1){
							console.log(data);
							location.href='withdrawals_record.php?identity=1';
						}else{
		  					layer.msg(data.msg)
		  				}
					}
				})
			})
		</script>
	</body>
</html>
