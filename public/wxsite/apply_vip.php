<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>申请尊享会员</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/apply_vip.css" />
	</head>
	<body>
		<div class="container">
			<div class="content">
				<p class="sub-tit">选择会员类型</p>
				<ul class="vip-type">
					<li>
						<div class="card-vip red">
							<img src="image/red-card.png" />
							<span>红卡会员</span>
						</div>
						<div class="input">
							<input type="radio" name="card" id="red" grade="2" />
							<label for="red"></label>
						</div>
					</li>
					<li>
						<div class="card-vip yellow">
							<img src="image/yellow-card.png" />
							<span>红+卡会员</span>
						</div>
						<div class="input">
							<input type="radio" name="card" id="yellow" grade="3" />
							<label for="yellow"></label>
						</div>
					</li>
					<li>
						<div class="card-vip white">
							<img src="image/white-card.png" />
							<span>白金卡会员</span>
						</div>
						<div class="input">
							<input type="radio" name="card" id="white" grade="4" />
							<label for="white"></label>
						</div>
					</li>
					<li>
						<div class="card-vip black">
							<img src="image/black-card.png" />
							<span>黑卡会员</span>
						</div>
						<div class="input">
							<input type="radio" name="card" id="black" grade="5" />
							<label for="black"></label>
						</div>
					</li>
				</ul>
				<p class="greyline"></p>
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
					<input type="text" placeholder="手机号码" id="uphone" maxlength="11" onkeyup="this.value=this.value.replace(/\D/g,'')" />
				</div>
				<div class="border-box">
					<div class="pic-box">
						<img src="image/phone.png" width="13px" style="margin-top: 19px;" />
					</div>
					<input type="text" placeholder="推荐人手机号码" id="rphone" maxlength="11" onkeyup="this.value=this.value.replace(/\D/g,'')" />
				</div>
				<p class="exp-txt">为了更快的为您开通会员，请填写自己真实联系信息</p>
			</div>
			
			<a href="javascript:;" class="determine" onclick="apply()">确定申请</a>
		</div>
		<!--弹框-->
		<div class="login-pop">
			<h4>提示</h4>
			<p>您还尚未登录,赶快去登录吧！</p>
			<div class="btn-xw">
				<a href="sign_in.php" class="confirm-btn">确定</a>
				<!--<a href="javascript:;" class="cancel-btn">取消</a>-->
			</div>
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script src="js/layer/layer.js"></script>
		<script>
			session = getCookie('session');
			session=session.substr(1,session.length-2);
			
			console.log(session);
			if(!session){
				layer.open({
					type: 1,
					closeBtn: 0, //不显示关闭按钮
					title: false,
					shadeClose: false, //关闭点击遮罩关闭弹框
					content: $(".login-pop")
				})				
				
			}
			
			$(".confirm-btn").click(function(){
				loginpage=function(){
	            	location.href="sign_in.php"
	            }
	            setTimeout(loginpage,2000);
			})
			
//			$(".cancel-btn").click(function(){
//				layer.closeAll();
//			})
			
			var qc=$_GET['qc'];
			console.log(qc);
			if(qc!=undefined){
				$("#rphone").val(qc);
			}
			
			winH=$(window).height();
			contH=$(".content").height();
			if(winH-contH>58){
				$(".determine").addClass("fixed");
			}
			
			winW=$(window).width();
			$(".card-vip").width(winW-85);
			function apply(){
				uname=$('#uname').val();
				uphone=$('#uphone').val();
				uphonelength=uphone.length;
				rphone=$('#rphone').val();
				vipgrade=$(".input input:checked").attr("grade");
//				console.log(vipgrade);

				var myreg = /^1[034578][0-9]{9}$/; //正则判断手机号是否有效
				
				if(uname == ''||uphone==''||vipgrade==undefined){
                    layer.msg("请填写完整！");
                }else if(uphonelength<11||!myreg.test($("#uphone").val())){
                	layer.msg("请输入11位有效手机号")
                }else{
                	$.ajax({
                		type:"post",
                		url:commonUrl+'api/stj/member_apply'+versioninfo,
                		data:{
                			'code':rphone,
                			'grade':vipgrade,
                			'mobile':uphone,
                			'name':uname,
                			'ss':session
                		},
                		success:function(data){
                			if(data.code==1){
                				console.log(data);
                				layer.msg("申请成功");
                				backlist=function(){
                					location.href="home_list.php"
                				}
                				setInterval(backlist,2000)
                			}else if(data.code==1011){
								layer.msg('该用户登陆数据已过期，请重新登陆');
			                	setTimeout("location.href='sign_in.php'",1000);
							}else{
                				layer.msg(data.msg)
                			}
                		}
                	});
                }
			}
		</script>
		<style>
			#layui-layer1{
				width: 240px!important;
			}
	        .layui-layer{
	            left:0;
	        }
	        .ui-loader{
	            display: none;
	        }
	    </style>
	</body>
</html>