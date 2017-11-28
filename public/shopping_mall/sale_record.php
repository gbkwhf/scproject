<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>销售记录</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"><meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/mui.min.css" />
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/my_order.css">
    <style type="text/css">
    	.mui-pull-bottom-pocket{
    		display: none !important;
    	}
    </style>
</head>

<body>
	<div id="refreshContainer" class="mui-scroll-wrapper">
       <div class="mui-scroll">
       		<div class="wrapper">
       			<!--列表一定要放到容器内,因为会有一个div append到mui-scroll中,需要在底部才能起作用-->
				<!--<div class="order-module">
					<h4>2017.10.25</h4>
					<i class="half-line"></i>
					<ul class="commodity-list">
						<li>
							<div class="picbox">						
								<img src="images/rice.png" width="100%"/>
							</div>
							<div class="commodity-info">
								<em>长城 (Gmsdfgsdnm) 红酒 华夏葡 萄 (711) 张裕解百纳</em>
								<div class="price-info">
									<p>¥3888.00</p>
									<span>x 1</span>
								</div>
							</div>
						</li>
					</ul>
					<i class="half-line" style="float: right;"></i>
					<div class="summary">
						<div class="user-info">
							<p>张某某</p>
							<span>187*****623</span>
						</div>
						<div class="order-info">
							<span>共11件</span>
							<p>实付款：<em>¥3888.00</em></p>
						</div>
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
	
	var winW = $(window).width();
	var winH = $(window).height();
	
	page=1;
    showajax(page);
    layer.ready(function(){ layer.load(2); })
    
    function showajax(page){
    	//员工为用户下单记录
		$.ajax({
			type:"post",
			url:commonsUrl+"api/gxsc/get/employeeorder"+versioninfos,
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
						$('.wrapper').html('<p>暂无记录哦！</p>');
						$('.wrapper p').css({'line-height':winH+'px','text-align': 'center','color':'#8f8f94'});
					}else{
						for(var i=0;i<data.result.length;i++){
							html+='<div class="order-module" onclick="location.href=\'record_details.php?base_order_id='+data.result[i].id+'\'">'+
							'	<h4>'+data.result[i].pay_time.substr(0,10)+'</h4>'+
							'	<i class="half-line"></i>'+
							'	<ul class="commodity-list">';
							for(var j=0;j<data.result[i].goods_list.length;j++){
								html+='<li>'+
								'	<div class="picbox">'+						
								'		<img src="'+data.result[i].goods_list[j].image+'" width="100%"/>'+
								'	</div>'+
								'	<div class="commodity-info">'+
								'		<em>'+data.result[i].goods_list[j].name+'</em>'+
								'		<div class="price-info">'+
								'			<span>x '+data.result[i].goods_list[j].num+'</span>'+
								'		</div>'+
								'	</div>'+
								'</li>';
							}
							html+='	</ul>'+
							'	<i class="half-line" style="float: right;"></i>'+
							'	<div class="summary">'+
							'		<div class="user-info">'+
							'			<p>'+data.result[i].name+'</p>'+
							'			<span>'+data.result[i].mobile.substr(0,3)+'*****'+data.result[i].mobile.substr(data.result[i].mobile.length-3,data.result[i].mobile.length)+'</span>'+
							'		</div>'+
							'		<div class="order-info">'+
							'			<span>共'+data.result[i].goods_total+'件</span>'+
							'			<p>实付款：<em>¥'+data.result[i].amount+'</em></p>'+
							'		</div>'+
							'	</div>'+
							'	<div class="state-box">'+
							'		<p>已完成</p>'+
							'	</div>'+
							'</div>';
						}
						$('.wrapper').append(html);
						$('.commodity-info').width(winW-143);
						
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