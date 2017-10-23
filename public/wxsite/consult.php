<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>我要咨询</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/apply_vip.css" />
	</head>
	<body>
		<div class="wrap">
			<div class="container">
				<div class="border-box">
					<div class="pic-box">
						<img src="image/user-icon.png" width="16px" style="margin-top: 19px;" />
					</div>
					<input type="text" placeholder="您的真实姓名" id="uname" maxlength="6" />
				</div>
				<div class="border-box">
					<div class="pic-box">
						<img src="image/phone.png" width="13px" style="margin-top: 19px;" />
					</div>
					<input type="text" placeholder="联系电话" id="uphone" maxlength="11" onkeyup="this.value=this.value.replace(/\D/g,'')" />
				</div>
				<div class="border-box regist-type">
					<div class="pic-box">
						<img src="image/type-icon.png" width="16px" style="margin-top: 19px;" />
					</div>
					<input class="regist_value" type="text" placeholder="请选择" readonly="readonly" id="type" />
				</div>
				<p class="sub-tit">描述</p>
				<textarea rows="6" id="describe"></textarea>			
			</div>
			<a href="javascript:;" class="submit" onclick="submits()">提交</a>
		</div>
		<!--弹窗-->
		<ul class="popup" style="display: none;width: 180px;">
			<!--<li>付款相关</li>
			<li>会员相关</li>-->
		</ul>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script src="js/layer/layer.js"></script>
		<script>
		
			session = getCookie('session');
			session=session.substr(1,session.length-2);
			
			if(!session){
					location.href='sign_in.php'
				}
			
			winH=$(window).height();
			$(".submit").css("top",winH-48);
			
			//弹框
			$(".regist-type").click(function(){
				layer.open({
					type: 1,
					closeBtn: 0, //不显示关闭按钮
					title: false,
					shadeClose: true, //开启遮罩关闭
					content: $(".popup")
				})
			})
			//咨询类型
			$.ajax({
				type:"post",
				url:commonUrl+'api/stj/getquestiontype'+versioninfo,
				async:false,
				data:{
					'type':2
				},
				success:function(data){
					if(data.code==1){
//						console.log(data);
						html="";
						for(var i=0;i<data.result.length;i++){
							html+='<li typeid='+data.result[i].id+'>'+data.result[i].name+'</li>';
						}
						$(".popup").html(html);
						
						$(".popup li").click(function(){
							var type=$(this).text();
							$(".regist_value").val(type);
							typeid=$(this).attr("typeid");
							layer.closeAll();
						})
					}else if(data.code==1011){
						layer.msg('该用户登陆数据已过期，请重新登陆');
	                	setTimeout("location.href='sign_in.php'",1000);
					}else{
						layer.msg(data.msg);
					}
				}
			});
			
			//提交
			function submits(){
//				console.log(typeid);
				describe=$('#describe').val();
				uphone=$('#uphone').val();
				var uphonelength=uphone.length;
				uname=$('#uname').val();
				
				var myreg = /^1[034578][0-9]{9}$/; //正则判断手机号是否有效
				
				if(type == ''||describe==''||uphone==''||uname==''){
                    layer.msg("请填写完整！");
                }else if(uphonelength<11||!myreg.test($("#uphone").val())){
                	layer.msg("请输入11位有效手机号")
                }else{
                	$.ajax({
						type:"post",
						url:commonUrl+'api/stj/question/newquestion'+versioninfo,
						data:{
							'content':describe,
							'mobile':uphone,
							'ss':session,
							'name':uname,
							'type':typeid
						},
						success:function(data){
							if(data.code==1){
								console.log(data);
								layer.msg("提交成功");
								listjump=function(){
									location.href="consult_record.php"
								}
								setInterval(listjump,2000);
							}else if(data.code==1011){
								layer.msg('该用户登陆数据已过期，请重新登陆');
			                	setTimeout("location.href='sign_in.php'",1000);
							}else{
								layer.msg(data.msg);
							}
						}
					});
                }
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