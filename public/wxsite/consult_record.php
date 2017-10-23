<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>咨询记录</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/consult_record.css" />
		<style>
			#wrap {
				position:absolute; 
				z-index:1;
				top:0px; 
				bottom:58px; 
				left:-9999px;
				width:100%;
				background:#f2f2f2;
				overflow:auto;
			}
			.line{
				height: 10px;
				width: 100%;
				background-color: #fff;
				position: fixed;
				bottom: 48px;
				left: 0;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div id="wrap">
				<div id="scroller">
					<div id="pullDown">
						<div class="pull-box">
							<span class="pullDownIcon"></span>
							<span class="pullDownLabel">下拉刷新...</span>
						</div>						
					</div>
					<ul class="health-list">
						<!--<li>
							<span class="title">咨询类型</span>
							<span class="arrow"><img src="image/arrow-right.png"></span>
							<span class="time">2016.09.22</span>
							<span class="state">未反馈</span>
						</li>
						<li>
							<span class="title">咨询类型</span>
							<span class="arrow"><img src="image/arrow-right.png"></span>
							<span class="time">2016.09.22</span>
							<span class="states">已反馈</span>
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
			<p class="line"></p>
			<a class="consult-btn" href="consult.php">我要咨询</a>
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script src="js/layer/layer.js"></script>
		<script src="js/iscroll.js"></script>
		<script>
		
			session = getCookie('session');
			session=session.substr(1,session.length-2);
			
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
					url:commonUrl + 'api/stj/question'+versioninfo,
					data:{
						'ss':session
					},
					success:function(data){
						if(data.code==1){
							console.log(data);
							if(data.result.length!=0){
								html="";
								for(var i=0;i<data.result.length;i++){
									html+='<li onclick="shownext('+data.result[i].id+')">';
									html+='	<span class="title">'+data.result[i].type+'</span>';
									html+='	<span class="arrow"><img src="image/arrow-right.png"></span>';
									html+='	<span class="time">'+data.result[i].created_at.substr(0,10)+'</span>';
									if(data.result[i].state==0){
										html+='	<span class="states">未处理</span>';
									}else if(data.result[i].state==1){
										html+='	<span class="state">已处理</span>';
									}
									html+='</li>';
								}
								$(".health-list").html(html);
								
							}else{
								html="";
								html+='<p class="none">暂无咨询</p>';
								$(".health-list").html(html);
								
								winH=$(window).height();
								$(".none").css("top",winH/2-62);
							}
							
							var winH=$(window).height();
							var listH=$(".health-list").height();
							var datalength=Math.ceil(winH-listH-58);
//							数据条数不足页面一屏时
							if(data.result.length<10){
								$("#pullUp").hide();
							}
								
							myScroll.refresh();     //数据加载完成后，调用界面更新方法
						}else if(data.code==1011){
							layer.msg('该用户登陆数据已过期，请重新登陆');
		                	setTimeout("location.href='sign_in.php'",1000);
						}else{
							layer.msg(data.msg);
						}
					}
				});

			}
			
			//	上拉加载下一页
			function lastpage(){		
				$.ajax({
						type:"post",
						url:commonUrl+'api/stj/question'+versioninfo,
						dataType:"json",
						data:{
							'page':nums,
							'ss':session
						},
						success:function(data){
							if(data.code=='1'){
								console.log(data);
								if(data.result.length!=0){
									html="";
									for(var i=0;i<data.result.length;i++){
										html+='<li onclick="shownext('+data.result[i].id+')">';
										html+='	<span class="title">'+data.result[i].type+'</span>';
										html+='	<span class="arrow"><img src="image/arrow-right.png"></span>';
										html+='	<span class="time">'+data.result[i].created_at.substr(0,10)+'</span>';
										if(data.result[i].state==0){
											html+='	<span class="states">未处理</span>';
										}else if(data.result[i].state==1){
											html+='	<span class="state">已处理</span>';
										}
										html+='</li>';
									}
									$(".health-list").append(html);									
								}								
								
								
								if(data.result.length==0){
									$("#pullUp").hide();
								}
								myScroll.refresh();     //数据加载完成后，调用界面更新方法   
							}else if(data.code==1011){
								layer.msg('该用户登陆数据已过期，请重新登陆');
			                	setTimeout("location.href='sign_in.php'",1000);
							}else{
								layer.msg(data.msg);
							}
						}
						
					});	
					nums++;

			}
			
			function shownext(zxid){
				location.href="consult-details.php?id="+zxid;
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