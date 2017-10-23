<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>商品列表</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/commodity_list.css" />
	</head>
	<body>
		<div id="wrap">
			<div id="scroller">
				<div id="pullDown">
					<div class="pull-box">
						<span class="pullDownIcon"></span>
						<span class="pullDownLabel">下拉刷新...</span>
					</div>						
				</div>
				<ul class="goodslists">
					<!--<li>
						<img src="image/goods.png" width="86" height="86" />
						<div class="goodsinfo">
							<h4>泰国茉莉香米</h4>
							<div class="goods-info">
								<p>¥<mark>1800.00</mark></p>
								<em>销量：<strong>1.2万</strong></em>
							</div>
						</div>					
					</li>-->
				</ul>
				<div id="pullUp">
					<div class="pull-box">
						<span class="pullUpIcon"></span>
						<span class="pullUpLabel">上拉加载更多...</span>
					</div>
				</div>
			</div>
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script src="js/iscroll.js"></script>
		<script>
			session = getCookie('session');
			session = session.substr(1,session.length-2);
			console.log(session);
			if(!session){
				session="";
			}
			refreshs();
			
			//刷新
				var     myScroll,
				    pullDownEl, pullDownOffset,
				    pullUpEl, pullUpOffset,
				    generatedCount = 0;
				 
				/**
				 * 下拉刷新 （自定义实现此方法）
				 * myScroll.refresh(); 数据加载完成后，调用界面更新方法
				 */
				function pullDownAction () {  
				        refreshs(); 
				}
				 
				/**
				 * 滚动翻页 （自定义实现此方法）
				 * myScroll.refresh();      // 数据加载完成后，调用界面更新方法
				 */
				function pullUpAction () {
				        lastpage();
				}
				 
				/**
				 * 初始化iScroll控件
				 */
				function loaded() {
				    pullDownEl = document.getElementById('pullDown');
				    pullDownOffset = pullDownEl.offsetHeight;
				    pullUpEl = document.getElementById('pullUp');  
				    pullUpOffset = pullUpEl.offsetHeight;
				     
				    myScroll = new iScroll('wrap', {
				        scrollbarClass: 'myScrollbar',
				        useTransition: false,
				        topOffset: pullDownOffset,
				        onRefresh: function () {
				            if (pullDownEl.className.match('loading')) {
				                pullDownEl.className = '';
				                pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉刷新...';
				            } else if (pullUpEl.className.match('loading')) {
				                pullUpEl.className = '';
				                pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多...';
				            }
				        },
				        onScrollMove: function () {
				            if (this.y > 5 && !pullDownEl.className.match('flip')) {
				                pullDownEl.className = 'flip';
				                pullDownEl.querySelector('.pullDownLabel').innerHTML = '松手开始更新...';
				                this.minScrollY = 0;
				            } else if (this.y < 5 && pullDownEl.className.match('flip')) {
				                pullDownEl.className = '';
				                pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉刷新...';
				                this.minScrollY = -pullDownOffset;
				            } else if (this.y < (this.maxScrollY - 5) && !pullUpEl.className.match('flip')) {
				                pullUpEl.className = 'flip';
				                pullUpEl.querySelector('.pullUpLabel').innerHTML = '松手开始更新...';
				                this.maxScrollY = this.maxScrollY;
				            } else if (this.y > (this.maxScrollY + 5) && pullUpEl.className.match('flip')) {
				                pullUpEl.className = '';
				                pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多...';
				                this.maxScrollY = pullUpOffset;
				            }				            
				        },
				        onScrollEnd: function () {
				            if (pullDownEl.className.match('flip')) {
				                pullDownEl.className = 'loading';
				                pullDownEl.querySelector('.pullDownLabel').innerHTML = '加载中...';               
				                pullDownAction();   // ajax call
				            } else if (pullUpEl.className.match('flip')) {
				                pullUpEl.className = 'loading';
				                pullUpEl.querySelector('.pullUpLabel').innerHTML = '加载中...';               
				                pullUpAction(); // ajax call
				            }
				        }
				    });
				     
				    setTimeout(function () { document.getElementById('wrap').style.left = '0'; }, 800);
				}
				 
				//初始化绑定iScroll控件
				document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
				document.addEventListener('DOMContentLoaded', loaded, false);		
			
			//下拉刷新
			function refreshs(){
				nums=2;
				$("#pullUp").show();
				$.ajax({
					type:"post",
					url: commonUrl + 'api/stj/goods/goods_list'+versioninfo,
					data:{
						'ss':session
					},
					success:function(ret){
						if(ret.code==1){
							console.log(ret)
							html="";
							for(var i=0;i<ret.result.length;i++){
								html+='<li>';
								html+='<a href="commodity_details.php?id='+ret.result[i].id+'">';							
								html+='	<img src="'+ret.result[i].image+'" width="86" height="86" />';
								html+='	<div class="goodsinfo">';
								html+='		<h4>'+ret.result[i].name+'</h4>';
								html+='		<p class="original-price">¥<mark>'+ret.result[i].original_price+'</mark></p>';
								html+='		<div class="goods-info">';
								html+='			<p>¥<mark>'+ret.result[i].price+'</mark></p>';
								html+='			<em>销量：<strong>'+ret.result[i].sales+'</strong></em>';
								html+='		</div>';
								html+='	</div>';
								html+='</a>';
								html+='</li>';							
								
							}
							$(".goodslists").html(html);
							
							winW=$(window).width();
							$(".goodsinfo").width(winW-133);
							
//							数据条数不足页面一屏时
							var winH=$(window).height();
							var listH=$("#scroller ul").height();
							var datalength=Math.ceil(winH-listH);
							if(datalength>0){
								$("#pullUp").hide();									
							}
								
							myScroll.refresh();     //数据加载完成后，调用界面更新方法 
							
						}else{
							layer.msg(data.msg);
						}
					}
				})
			}

//	上拉加载下一页
			function lastpage(){		
				$.ajax({
							type:"post",
							url:commonUrl + 'api/stj/goods/goods_list'+versioninfo,
							dataType:"json",
							data:{
								'ss':session,
								'page':nums
							},
							success:function(data){
								if(data.code=='1'){
									console.log(data);
									html="";
									for(var i=0;i<data.result.length;i++){
										
										html+='<li>';
										html+='<a href="commodity_details.php?id='+data.result[i].id+'">';								
										html+='	<img src="'+data.result[i].image+'" width="86" height="86" />';
										html+='	<div class="goodsinfo">';
										html+='		<h4>'+data.result[i].name+'</h4>';
										html+='		<p class="original-price">¥<mark>'+data.result[i].original_price+'</mark></p>';
										html+='		<div class="goods-info">';
										html+='			<p>¥<mark>'+data.result[i].price+'</mark></p>';
										html+='			<em>销量：<strong>'+data.result[i].sales+'</strong></em>';
										html+='		</div>';
										html+='	</div>';
										html+='</a>';
										html+='</li>';						
										
									}
									$("#scroller ul").append(html);
									
									winW=$(window).width();
									$(".goodsinfo").width(winW-133);
									
									
									if(data.result.length==0){
										$("#pullUp").hide();
									}
									myScroll.refresh();     //数据加载完成后，调用界面更新方法   
								}else{
									layer.msg(data.msg);
								}
						}
						
					});	
					nums++;

			}
			
		</script>
	</body>
</html>