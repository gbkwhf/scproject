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
						<p style="font-weight:400">¥69.57</p>
						<span style="color: #999;">提交中</span>
					</div>
					<div class="record-box">
						<em>2017-10-25   17:55:03</em>
					</div>				
				</div>-->
			</div>
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
	
	page=1;
    showajax(page);
    
    
    function showajax(page){
    	layer.ready(function(){ layer.load(2); })
    	//员工为用户下单记录
		$.ajax({
			type:"post",
			url:commonsUrl+"api/gxsc/get/cashbacklist"+versioninfos,
			data:{
				'ss':getCookie('openid'),
				'page':page
			},
			success:function(data){
				layer.closeAll();
				if(data.code==1){
					console.log(data);
					html='';
					if(data.result.length==0 && page == 1){
						layer.closeAll();
						$('.wrapper').html('<p>暂无返现记录哦！</p>');
						$('.wrapper p').css({'line-height':winH+'px','text-align': 'center','color':'#8f8f94'});
					}else{
						for(var i=0;i<data.result.length;i++){
							html+='<div class="withdrawals-record">'+
							'	<div class="record-con">'+				
							'		<p style="font-weight:400">¥'+data.result[i].amount+'</p>'+
							'		<span style="color: #999;">'+data.result[i].pay_describe+'</span>'+
							'	</div>'+
							'	<div class="record-box">'+
							'		<em>'+data.result[i].created_at+'</em>'+
							'	</div>'+				
							'</div>';
						}
						$('.wrapper').append(html);
						
						if(data.result.length>0){
                            mui('#refreshContainer').pullRefresh().endPullupToRefresh(false);
                        }else{
                        	
                            layer.msg("已经到底了");
                            mui('#refreshContainer').pullRefresh().endPullupToRefresh(true);
                        }
					}
					
					
				}else{
					layer.msg(data.msg);
				}
			}
		});
    }
	mui.init({
        pullRefresh : {
            container:'#refreshContainer',//待刷新区域标识，querySelector能定位的css选择器均可，比如：id、.class等
            auto:true,// 可选,默认false.自动上拉加载一次
            height:50,
            up : {
                callback :function(){
                    page++;
                    showajax(page);
                    mui('#refreshContainer').pullRefresh().endPullupToRefresh();
                } //必选，刷新函数，根据具体业务来编写，比如通过ajax从服务器获取新数据；
            },
            down : {
            	callback :function(){
                    page=1;
                    showajax(page);
                    mui('#refreshContainer').pullRefresh().endPulldownToRefresh();
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