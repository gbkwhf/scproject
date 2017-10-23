<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>商品订单</title>
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
				<ul class="goodslist">
					<!--<li onclick="linknext()">
						<img src="image/goods.png" width="86" height="86" />
						<div class="goodsinfos">
							<h4>泰国茉莉香米</h4>
							<span>2016-10-25</span>
							<div class="goods-infos">
								<em>未付款</em>
								<p>¥<mark>1800.00</mark></p>							
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
		<script src="js/layer/layer.js"></script>
		<script src="js/iscroll.js"></script>
		<script>
				session = getCookie('session');
				session=session.substr(1,session.length-2);
				console.log(session);
				
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
					url: commonUrl + 'api/stj/goods/order_list'+versioninfo,
					data:{
						'ss':session
					},
					success:function(ret){
						if(ret.code==1){
							console.log(ret);
							if(ret.result.length!=0){
								retdata=localStorage.setItem("retdata",JSON.stringify(ret));
								html="";
								for(var i=0;i<ret.result.length;i++){
									html+='<li>';
									html+='<a href="order_details.php?id='+ret.result[i].id+'">';
									html+='<div class="goodsinfo-box">'							
									html+='	<img src="'+ret.result[i].image+'" width="86" height="86" />';
									html+='	<div class="goodsinfos">';
									html+='		<h4>';
									html+='			<p>'+ret.result[i].name+'</p>';
									if(ret.result[i].state==0){
										html+='			<span>未付款</span>';
										html+='		</h4>';
										html+='		<span class="data-sp">'+ret.result[i].created_at.substr(0,10)+'</span>';
										html+='<div class="price-box">';
										html+='		<p>¥<mark>'+ret.result[i].price+'</mark></p>';	
										html+='		<div class="good-num">x<span>'+ret.result[i].num+'</span></div>';																
										html+='</div>';																
										html+='</div>';																				
										html+='		</div>';
										html+='<p class="addprice">合计：<span class="apcolor">¥<mark>'+ret.result[i].amount+'</mark></span></p>';
									}else if(ret.result[i].state==1){
										html+='			<span>已付款</span>';
										html+='		</h4>';
										html+='		<span class="data-sp">'+ret.result[i].payment_at.substr(0,10)+'</span>';
										html+='<div class="price-box">';
										html+='		<p>¥<mark>'+ret.result[i].price+'</mark></p>';	
										html+='		<div class="good-num">x<span>'+ret.result[i].num+'</span></div>';																
										html+='</div>';																
										html+='</div>';																				
										html+='		</div>';
										html+='<p class="addprice">合计：<span class="apcolors">¥<mark>'+ret.result[i].amount+'</mark></span></p>';
									}else if(ret.result[i].state==2){
										html+='			<span class="goodsstate">已发货</span>';
										html+='		</h4>';
										html+='		<span class="data-sp">'+ret.result[i].payment_at.substr(0,10)+'</span>';
										html+='<div class="price-box">';
										html+='		<p>¥<mark>'+ret.result[i].price+'</mark></p>';	
										html+='		<div class="good-num">x<span>'+ret.result[i].num+'</span></div>';																
										html+='</div>';																
										html+='</div>';																				
										html+='		</div>';
										html+='<p class="addprice">合计：<span class="apcolors">¥<mark>'+ret.result[i].amount+'</mark></span></p>';
									}
										
									html+='	</div>';
									html+='</a>';				
									html+='</li>';							
									
								}
								$(".goodslist").html(html);
								
								winW=$(window).width();
								$(".goodsinfos").width(winW-133);
								goodsinfosW=$(".goodsinfos").width();
								$(".goodsinfos h4 p").width(goodsinfosW-39);
								

									
								myScroll.refresh();     //数据加载完成后，调用界面更新方法 
								
							}else{
								html="";
								html+='<p class="none">暂无订单</p>';
								$("#wrap").html(html);
								
								winH=$(window).height();
								$(".none").css("top",winH/2-62);
							}
//								数据条数不足页面一屏时
								if(ret.result.length<10){
									$("#pullUp").hide();
								}						
						}else if(ret.code==1011){
							layer.msg('该用户登陆数据已过期，请重新登陆');
		                	setTimeout("location.href='sign_in.php'",1000);
						}else{
							layer.msg(ret.msg);
						}
					}
				})
			}
			//	上拉加载下一页
			function lastpage(){		
				$.ajax({
						type:"post",
						url:commonUrl+'api/stj/goods/order_list'+versioninfo,
						dataType:"json",
						data:{
							'page':nums,
							'ss':session
						},
						success:function(ret){
							if(ret.code==1){
								console.log(ret);
								if(ret.result.length!=0){
									retdata=localStorage.setItem("retdata",JSON.stringify(ret));
									html="";
									for(var i=0;i<ret.result.length;i++){
										html+='<li>';
									html+='<a href="order_details.php?id='+ret.result[i].id+'">';
									html+='<div class="goodsinfo-box">'							
									html+='	<img src="'+ret.result[i].image+'" width="86" height="86" />';
									html+='	<div class="goodsinfos">';
									html+='		<h4>';
									html+='			<p>'+ret.result[i].name+'</p>';
									if(ret.result[i].state==0){
										html+='			<span>未付款</span>';
										html+='		</h4>';
										html+='		<span class="data-sp">'+ret.result[i].created_at.substr(0,10)+'</span>';
										html+='<div class="price-box">';
										html+='		<p>¥<mark>'+ret.result[i].price+'</mark></p>';	
										html+='		<div class="good-num">x<span>'+ret.result[i].num+'</span></div>';																
										html+='</div>';																
										html+='</div>';																				
										html+='		</div>';
										html+='<p class="addprice">合计：<span class="apcolor">¥<mark>'+ret.result[i].amount+'</mark></span></p>';
									}else if(ret.result[i].state==1){
										html+='			<span>已付款</span>';
										html+='		</h4>';
										html+='		<span class="data-sp">'+ret.result[i].payment_at.substr(0,10)+'</span>';
										html+='<div class="price-box">';
										html+='		<p>¥<mark>'+ret.result[i].price+'</mark></p>';	
										html+='		<div class="good-num">x<span>'+ret.result[i].num+'</span></div>';																
										html+='</div>';																
										html+='</div>';																				
										html+='		</div>';
										html+='<p class="addprice">合计：<span class="apcolors">¥<mark>'+ret.result[i].amount+'</mark></span></p>';
									}else if(ret.result[i].state==2){
										html+='			<span class="goodsstate">已发货</span>';
										html+='		</h4>';
										html+='		<span class="data-sp">'+ret.result[i].payment_at.substr(0,10)+'</span>';
										html+='<div class="price-box">';
										html+='		<p>¥<mark>'+ret.result[i].price+'</mark></p>';	
										html+='		<div class="good-num">x<span>'+ret.result[i].num+'</span></div>';																
										html+='</div>';																
										html+='</div>';																				
										html+='		</div>';
										html+='<p class="addprice">合计：<span class="apcolors">¥<mark>'+ret.result[i].amount+'</mark></span></p>';
									}
										
									html+='	</div>';
									html+='</a>';				
									html+='</li>';							
										
									}
									$(".goodslist").append(html);
									
									winW=$(window).width();
									$(".goodsinfos").width(winW-133);
									goodsinfosW=$(".goodsinfos").width();
									$(".goodsinfos h4 p").width(goodsinfosW-39);

								}
								if(ret.result.length==0){
										$("#pullUp").hide();
									}
									
									myScroll.refresh();     //数据加载完成后，调用界面更新方法   
							}else if(ret.code==1011){
								layer.msg('该用户登陆数据已过期，请重新登陆');
			                	setTimeout("location.href='sign_in.php'",1000);
							}else{
									layer.msg(ret.msg);
								}
						}
					});	
					nums++;

			}
		
		</script>
		<style>
	        .layui-layer{
	            left:0;
	        }
	        .ui-loader{
	            display: none;
	        }
	    </style>
	</body>
</html>