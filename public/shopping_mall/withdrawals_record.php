<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>提现记录</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"><meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/mui.min.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/withdrawals_record.css">
</head>

<body>
	<div id="refreshContainer" class="mui-scroll-wrapper">
        <div class="mui-scroll">
            <!--列表一定要放到容器内,因为会有一个div append到mui-scroll中,需要在底部才能起作用-->
            <div class="wrapper">
				<!--<div class="withdrawals-record">
					<div class="record-con">				
						<p>-69.57</p>
						<span style="color: #999;">提交中</span>
					</div>
					<div class="record-box">
						<em>2017-10-25   17:55:03</em>
						<a href="javascript:;">确认收款</a>
					</div>				
					<div class="withdrawals-id">提现编号：<span>GEAO 134555</span></div>
				</div>-->
			</div>
        </div>
    </div>
	<!--确认收款-->
	<div class="qrsk">
		<ul class="explain-list">
			<li><em>1、</em>提现成功后会从您的余额中扣除5%手续费。</li>
			<li><em>2、</em>请仔细核对提现金额，确认后不可撤销；</li>
		</ul>
		<div class="btn-box-pop">
			<a href="javascript:void(0);" class="cancel" onclick="cancelPop()">取消</a>
			<a href="javascript:void(0);" class="confirm">确认</a>
		</div>
	</div>
	
</body>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script src="js/mui.min.js"></script>
<script>
	var winH = $(window).height();
	showajax();
	function showajax(){
		if($_GET['identity']==0){   //用户提现记录
			$.ajax({
				type:"post",
				url:commonsUrl + "api/gxsc/get/user/withdraw/deposit/bills/list" + versioninfos,
				data:{'ss':getCookie('openid')},
				success:function(data){
					if(data.code==1){
						console.log(data);
						if(data.result.length==0){
							$('.wrapper').html('<p>暂无记录哦！</p>');
							$('.wrapper p').css({'line-height':winH+'px','text-align': 'center'});
							//禁止下拉刷新
							mui('#refreshContainer').pullRefresh().endPulldownToRefresh(true);
						}else{
							var html='';
							for(var i=0;i<data.result.length;i++){
								html+='<div class="withdrawals-record">'+
								'   <div class="record-con">';
								if(data.result[i].type==2){			
									html+='<p>'+data.result[i].amount+'<span>（手续费）<span></p>';
								}else{
									html+='<p>'+data.result[i].amount+'</p>';
								}
								var amount = data.result[i].amount.substr(1,data.result[i].amount.length); 
								if(data.result[i].state==0){
									html+='<span style="color: #999;">提现中</span>'+
									'	</div>'+
									'	<div class="record-box">'+
									'		<em>'+data.result[i].created_at+'</em>'+
									'		<a href="javascript:;" class="qrsk-btn" bill_id="'+data.result[i].bill_id+'" amount="'+amount+'">确认收款</a>';
								}else if(data.result[i].state==1){
									html+='		<span>提现成功</span>'+
									'	</div>'+
									'	<div class="record-box">'+
									'		<em>'+data.result[i].created_at+'</em>';
								}
								html+='	</div>'+
								'	<div class="withdrawals-id">提现编号：<span>'+data.result[i].bill_id+'</span></div>'+
								'</div>';
							}
							$('.wrapper').html(html);
							setTimeout(function() {
								mui('#refreshContainer').pullRefresh().endPulldownToRefresh(); //refresh completed
							}, 1500);
						}
					}else{
						layer.msg(data.msg);
					}
				}
			});
		}else if($_GET['identity']==1){  //员工替用户提现记录
			$.ajax({
				type:"post",
				url:commonsUrl + "api/gxsc/get/own/operate/bills/list" + versioninfos,
				data:{'ss':getCookie('openid')},
				success:function(data){
					if(data.code==1){
						console.log(data);
						if(data.result.length==0){
							$('.wrapper').html('<p>暂无记录哦！</p>');
							$('.wrapper p').css({'line-height':winH+'px','text-align': 'center'});
							//禁止下拉刷新
							mui('#refreshContainer').pullRefresh().endPulldownToRefresh(true);
						}else{
							var html='';
							for(var i=0;i<data.result.length;i++){
								html+='<div class="withdrawals-record">'+
								'   <div class="record-con">'+				
								'		<p>'+data.result[i].amount+'</p>';
								if(data.result[i].state==0){
									html+='		<span style="color:#999;">提现中</span>';
								}else{
									html+='		<span>提现成功</span>';
								}
								html+='	</div>'+
								'	<div class="record-box">'+
								'		<em>'+data.result[i].created_at+'</em>'+
								'	</div>'+
								'	<div class="withdrawals-id">提现编号：<span>'+data.result[i].bill_id+'</span></div>'+
								'</div>';
							}
							$('.wrapper').html(html);
							setTimeout(function() {
								mui('#refreshContainer').pullRefresh().endPulldownToRefresh(); //refresh completed
							}, 1500);
						}
					}else{
						layer.msg(data.msg);
					}
				}
			});
		}
	}
	
	//确认收款提示
	mui(".wrapper").on('tap', '.qrsk-btn' , function(event){
		$('.qrsk').attr('bill_id',$(this).attr('bill_id'));
		layer.open({
        	type: 1,
        	title:'是否已收到<span style="color: #d57232;">'+$(this).attr('amount')+'</span>元？',
        	closeBtn:false,
        	shadeClose: false,
        	content: $('.qrsk')
       })
	});
	
	//确认收款
	$('.confirm').click(function(){
		var bills_id = $(this).parents('.qrsk').attr('bill_id');
		$.ajax({
			type:"post",
			url: commonsUrl + "api/gxsc/user/ack/get/money" + versioninfos,
			data:{'ss':getCookie('openid'),'bills_id':bills_id},
			success:function(data){
				if(data.code==1){
					console.log(data);
					location.reload();
				}else{
					layer.msg(data.msg);
				}
			}
		});
	})

    mui.init({
        pullRefresh : {
            container:'#refreshContainer',//待刷新区域标识，querySelector能定位的css选择器均可，比如：id、.class等
            auto:false,// 可选,默认false.自动上拉加载一次
            height:50,
            down : {
                callback :function(){
                    showajax();
                    mui('#refreshContainer').pullRefresh().endPullupToRefresh();
                } //必选，刷新函数，根据具体业务来编写，比如通过ajax从服务器获取新数据；
            }
        }        
    });
	
</script>
<style type="text/css">
	.layui-layer.layui-anim.layui-layer-page{
		border-radius: 5px;
	} 
</style>
</html>