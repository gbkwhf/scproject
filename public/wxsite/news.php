<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>健康资讯</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/news.css" />
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
				<ul>
					<!--<li>
						<img src="image/news.jpg" />
						<p>标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题</p>
					</li>
					<li>
						<img src="image/news.jpg" />
						<p>标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题标题</p>
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
				$.ajax({
					type:"post",
					url:commonUrl+'api/stj/list/newlist'+versioninfo,
					success:function(data){
						if(data.code=1){
							console.log(data);
							html="";
							for(var i=0;i<data.result.list.length;i++){
								html+='<li onclick="linknext('+data.result.list[i].id+')">';
								html+='	<img src="'+data.result.list[i].image+'" />';
								html+='	<p>'+data.result.list[i].title+'</p>';
								html+='</li>';
							}
							$("#scroller ul").html(html);
							
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

//	下拉加载下一页
			function lastpage(){		
				$.ajax({
							type:"post",
							url:commonUrl+"api/stj/list/newlist"+versioninfo,
							dataType:"json",
							data:{
								'page':nums
							},
							success:function(data){
								if(data.code=='1'){
									console.log(data);
									html="";
									for(var i=0;i<data.result.list.length;i++){
										html+='<li onclick="linknext('+data.result.list[i].id+')">';
										html+='	<img src="'+data.result.list[i].image+'" />';
										html+='	<p>'+data.result.list[i].title+'</p>';
										html+='</li>';
									}
									$("#scroller ul").append(html);
									
									if(data.result.list.length==0){
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
			
			function linknext(id){
				location.href="newsDetail.php?id="+id;
			}
		</script>
	</body>
</html>