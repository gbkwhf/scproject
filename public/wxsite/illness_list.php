<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>我的病历</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/illness_list.css" />
	</head>
	<body>
		
	<div class="containers">
		<div id="wrap">
			<div id="scroller">
				<div id="pullDown">
					<div class="pull-box">
						<span class="pullDownIcon"></span>
						<span class="pullDownLabel">下拉刷新...</span>
					</div>						
				</div>
				<div class="illness">
					<!--<div class="illness-box">
						<h4>
							<p>类风湿性关节炎</p>
							<img src="image/next.png" width="9" />
						</h4>
						<div class="diagnosis botbor">
							<label for="time">就诊时间</label>
							<input type="text" name="time" id="time"/>
						</div>
						<div class="diagnosis botbor">
							<label for="address">就诊医院</label>
							<input type="text" name="address" id="address" />
						</div>
						<div class="diagnosis">
							<div class="edit">
								<img src="image/text.png" />
								<p>编辑</p>
							</div>
							<div class="del">
								<img src="image/del.png" width="15" />
								<p>删除</p>
							</div>
						</div>
					</div>				-->
				</div>
				<div id="pullUp">
					<div class="pull-box">
						<span class="pullUpIcon"></span>
						<span class="pullUpLabel">上拉加载更多...</span>
					</div>
				</div>
			</div>			
		</div>
		<p class="p-box"></p>
		<div class="btn-box">
			<!--<div class="sqm">
				<a href="authorization_code.php" class="sqm-btn">生成授权码</a>
			</div>
			<div class="add">
				<a href="illness_details_add.php" class="add-btn">添加病历</a>
			</div>				-->
		</div>					
	</div>
	
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script src="js/iscroll.js"></script>
		<script>
						
			session = getCookie('session');
			session=session.substr(1,session.length-2);
			
			if(!session){
					location.href='sign_in.php'
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
				url:commonUrl+'api/stj/user_case/case_list'+versioninfo,
				data:{
					'ss':session
				},
				success:function(data){
					if(data.code==1){
						console.log(data);
						if(data.result.length!=0){
							html="";
							for(var i=0;i<data.result.length;i++){
								html+='<div class="illness-box">';
								html+='	<h4 onclick="blid('+data.result[i].id+')">';
								html+='		<p>'+data.result[i].title+'</p>';
								html+='		<img src="image/next.png" width="9" />';
								html+='	</h4>';
								html+='	<div class="diagnosis botbor" onclick="blid('+data.result[i].id+')">';
								html+='		<p>就诊时间</p>';
								html+='		<span>'+data.result[i].time.substr(0,10)+'</span>';
								html+='	</div>';
								html+='	<div class="diagnosis botbor" onclick="blid('+data.result[i].id+')">';
								html+='		<p>就诊医院</p>';
								html+='		<span>'+data.result[i].hospital+'</span>';
								html+='	</div>';
								html+='	<div class="diagnosis">';
								html+='		<div class="edit" onclick="editbl('+data.result[i].id+')">';
								html+='			<img src="image/text.png" />';
								html+='			<p>编辑</p>';
								html+='		</div>';
								html+='		<div class="del" onclick="deletes('+data.result[i].id+')">';
								html+='			<img src="image/del.png" width="15" />';
								html+='			<p>删除</p>';
								html+='		</div>';
								html+='	</div>';
								html+='</div>';
							}
							$(".illness").html(html);
							
							btnhtml="";
							
							btnhtml+='<div class="sqm">';
							btnhtml+='	<a href="authorization_code.php" class="sqm-btn">生成授权码</a>';
							btnhtml+='</div>';
							btnhtml+='<div class="add">';
							btnhtml+='	<a href="illness_details_add.php" class="add-btn">添加病历</a>';
							btnhtml+='</div>';
							$(".btn-box").html(btnhtml);
							
							boxW=$(".diagnosis").width();
							$(".diagnosis span").width(boxW-80);
						}else{
							html="";
							html+='<p class="none">暂无病历</p>'
							$("#wrap").html(html);
							
							btnhtml="";
							
							btnhtml+='<div class="addtotal">';
							btnhtml+='	<a href="illness_details_add.php" class="add-btn">添加病历</a>';
							btnhtml+='</div>';
							$(".btn-box").html(btnhtml);
							
							winH=$(window).height();
							$(".none").css("top",winH/2-62);
						}
						
						
//							数据条数不足页面一屏时
							var winH=$(window).height();
							var listH=$(".illness").height();
							var datalength=Math.ceil(winH-listH);
							if(datalength>0){
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
						url:commonUrl+'api/stj/user_case/case_list'+versioninfo,
						dataType:"json",
						data:{
							'page':nums,
							'ss':session
						},
						success:function(data){
							if(data.code==1){
								console.log(data);
								html="";
								for(var i=0;i<data.result.length;i++){
									html+='<div class="illness-box">';
									html+='	<h4 onclick="blid('+data.result[i].id+')">';
									html+='		<p>'+data.result[i].title+'</p>';
									html+='		<img src="image/next.png" width="9" />';
									html+='	</h4>';
									html+='	<div class="diagnosis botbor" onclick="blid('+data.result[i].id+')">';
									html+='		<p>就诊时间</p>';
									html+='		<span>'+data.result[i].time.substr(0,10)+'</span>';
									html+='	</div>';
									html+='	<div class="diagnosis botbor" onclick="blid('+data.result[i].id+')">';
									html+='		<p>就诊医院</p>';
									html+='		<span>'+data.result[i].hospital+'</span>';
									html+='	</div>';
									html+='	<div class="diagnosis">';
									html+='		<div class="edit" onclick="editbl('+data.result[i].id+')">';
									html+='			<img src="image/text.png" />';
									html+='			<p>编辑</p>';
									html+='		</div>';
									html+='		<div class="del" onclick="deletes('+data.result[i].id+')">';
									html+='			<img src="image/del.png" width="15" />';
									html+='			<p>删除</p>';
									html+='		</div>';
									html+='	</div>';
									html+='</div>';
								}
								
								$(".illness").append(html);
								boxW=$(".diagnosis").width();
								$(".diagnosis span").width(boxW-80);
								
								
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
		
			function blid(blid){
				location.href="illness_details.php?id="+blid;
			}
			function deletes(id){
				$.ajax({
					type:"post",
					url:commonUrl+'api/stj/user_case/del_case'+versioninfo,
					data:{
						'ss':session,
						'id':id
					},
					success:function(ret){
						if(ret.code==1){
							console.log(ret);
							location.reload();//刷新页面
						}else if(ret.code==1011){
							layer.msg('该用户登陆数据已过期，请重新登陆');
	                    	setTimeout("location.href='sign_in.php'",1000);
						}else{
							layer.msg(ret.msg);
						}
					}
				});
			}
			function editbl(blids){
				location.href="illness_details_edit.php?id="+blids;
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