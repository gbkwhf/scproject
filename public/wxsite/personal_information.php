<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>个人信息</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/mobiscroll/mobiscroll.core-2.5.2.css" />
		<link rel="stylesheet" type="text/css" href="css/mobiscroll/mobiscroll.animation-2.5.2.css" />
		<link rel="stylesheet" type="text/css" href="css/mobiscroll/mobiscroll.android-ics-2.5.2.css" />
		<link rel="stylesheet" type="text/css" href="css/personal_information.css" />
		<style>
	        .upload-box{
	        	overflow: hidden;
	        	margin-top: 9px;
				float: right;
	        }
        	.upload-box .upload{
        		width: 40px;
			    height: 40px;			   
			    background-image: url('image/smile.png');
			    background-size: contain;
			    background-repeat: no-repeat;
			    position: relative;
			    transition: all 0.3s;
			    -webkit-transition: all 0.3s;
			    overflow: hidden;
			    float: left;
        	}
        	.upload-box input{
        		width: 100%;
        		height: 100%;
        		opacity: 0;
        		position: absolute;
        		left: 0px;
        		top: 0px;
        		z-index: 4;
        	}
        </style>
	</head>
	<body>
		<div class="container">
			<div class="content">
				<!--<div class="border-box">
					<p>头像</p>
					<div class="right-box">
						<img src="image/headportrait.png" width="40" height="40" />
					</div>
				</div>
				<div class="border-box">
					<p>姓名</p>
					<div class="right-box">
						<span>夏天</span>
					</div>
				</div>
				<div class="border-box">
					<p>会员类型</p>
					<div class="right-box">
						<span>金卡会员</span>
					</div>
				</div>
				<div class="border-box">
					<p>性别</p>
					<div class="right-box">
						<span>女</span>
					</div>
				</div>
				<div class="border-box">
					<p>出生日期</p>
					<div class="right-box">
						<span>1989年01月02日</span>
					</div>
				</div>
				<div class="border-box">
					<p>所在地区</p>
					<div class="right-box">
						<span>上海市闵行区</span>
					</div>
				</div>
				<div class="border-box">
					<p>二维码名片</p>
					<div class="right-box">
						<img src="image/code.png" width="40" />
					</div>
				</div>
				<div class="border-box">
					<p>修改密码</p>
					<div class="right-box">
						<img src="image/arrow-right.png" width="9" style="margin-top: 21px;" />
					</div>
				</div>
				<div class="border-box">
					<p>工作单位</p>
					<div class="right-box">
						<span>上海市闵行区</span>
					</div>
				</div>-->
			</div>
			<div class="btn-boxs">
				
			</div>
		</div>
		<!--弹窗-->
		<ul class="popup" style="display: none;width: 240px;">
			<li>男</li>
			<li>女</li>
		</ul>
		<!--登录弹框-->
		<div class="login-pop">
			<h4>退出登录</h4>
			<p>退出登录后不会删除任何历史数据，下次登录依然可以使用本账号！</p>
			<div class="btn-xw">
				<a href="sign_in.php" class="confirm-btn" onclick="confirm()">确定</a>
				<a href="javascript:;" class="cancel-btn" onclick="cancel()">取消</a>
			</div>
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script src="js/layer/layer.js"></script>
		<script src="js/mobiscroll/mobiscroll.core-2.5.2.js"></script>
		<script src="js/mobiscroll/mobiscroll.core-2.5.2-zh.js"></script>
		<script src="js/mobiscroll/mobiscroll.datetime-2.5.1.js"></script>
		<script src="js/mobiscroll/mobiscroll.datetime-2.5.1-zh.js"></script>
		<script src="js/mobiscroll/mobiscroll.android-ics-2.5.2.js"></script>
		<script src="js/lib/exif.js" type="text/javascript"></script>
        <script src="js/lib/mobileFix.mini.js" type="text/javascript"></script>
		<script src="js/lrz.js" type="text/javascript"></script>
		<script>
			session = getCookie('session');
			session=session.substr(1,session.length-2);
			
			personal=JSON.parse(localStorage.getItem("personal"));
			console.log(personal);
			
			//压缩图片2
			function changefile(obj) {
                filetest = obj;
                layer.load(2, {time: 7*1000});
                lrz(filetest.files[0], {width: 400,quality:1}, function (result) {
                    // 你需要的数据都在这里，可以以字符串的形式传送base64给服务端转存为图片。
                    submitData={
                        base64_string:result.base64
                    };
                    //上传图片
                    $.ajax({
                        type: "POST",
                        url: commonUrl+'wxsite/upload.php',
                        data: submitData,
                        dataType: 'json',
                        success: function(data){
//                          console.log(data);
                            if(data.code==1){
                                layer.closeAll();
                                $(filetest).parent(".upload").css({
                                    "background-image":"url('http://"+data.data+"')",
                                    "background-size":"contain"
                                }).attr("org_img",'http://'+data.data);
                            }else{
                                alert(data.msg);
                            }
                        }
                    });
                });
            }
			html="";
			html+='<div class="border-box">';
			html+='	<p>头像</p>';
			html+='	<div class="right-box">';
			html+='<div class="upload-box">';
			if(personal.result[0].thumbnail_image_url==""){
	        	html+='	<div class="upload" org_img="">';
	        	html+='		<input type="file" accept="image/*" class="filetest" onchange="changefile(this)"/>';
	        	html+='	</div>';
	        	
			}else{
				imagelength=personal.result[0].thumbnail_image_url.length;
				imageurl=personal.result[0].thumbnail_image_url.substr(20,imagelength-1);	
	        	html+='	<div class="upload" org_img="'+personal.result[0].thumbnail_image_url+'" style="background-image:url(\'http://106.75.10.216'+imageurl+'\');background-size: contain";>';
	        	html+='		<input type="file" accept="image/*" class="filetest" onchange="changefile(this)"/>';
	        	html+='	</div>';        	
			}			
			
			html+='</div>';
			html+='	</div>';
			html+='</div>';
			html+='<div class="border-box username">';
			html+='	<p style="width:30px">姓名</p>';
			html+='	<div class="right-box">';
			if(personal.result[0].name==null){
				html+='		<input type="text" placeholder="请填写" id="user" onkeyup="filter()" maxlength="6" />';
			}else{
				html+='		<input type="text" value="'+personal.result[0].name+'" id="user" onkeyup="filter()" maxlength="6" />';
			}			
			html+='	</div>';
			html+='</div>';
			html+='<div class="border-box">';
			html+='	<p>会员类型</p>';
			html+='	<div class="right-box">';
			grade=personal.result[0].grade;
			if(grade==1){
				html+='		<input type="text" value="普通会员" readonly="readonly" />';
			}if(grade==2){
				html+='		<input type="text" value="红卡会员" readonly="readonly" />';
			}else if(grade==3){
				html+='		<input type="text" value="金卡会员" readonly="readonly" />';
			}else if(grade==4){
				html+='		<input type="text" value="白金卡会员" readonly="readonly" />';
			}else if(grade==5){
				html+='		<input type="text" value="黑卡会员" readonly="readonly" />';
			}			
			html+='	</div>';
			html+='</div>';
			html+='<div class="border-box">';
			html+='	<p style="width:60px">会员编号</p>';
			html+='	<div class="right-box">';
			html+='		<input type="text" value="'+personal.result[0].vip_code+'"  maxlength="30" readonly="readonly" />';
			html+='	</div>';
			html+='</div>';
			html+='<div class="border-box" id="sex">';
			html+='	<p>性别</p>';
			html+='	<div class="right-box">';
			if(personal.result[0].sex_id==null||personal.result[0].sex_id==0){
				html+='		<input type="text" placeholder="请选择" readonly="readonly" id="sex_id" />';
			}else if(personal.result[0].sex_id==1){
				html+='		<input type="text" value="男" id="sex_id" readonly="readonly" />';
			}else if(personal.result[0].sex_id==2){
				html+='		<input type="text" value="女" id="sex_id" readonly="readonly" />';
			}
			html+='	</div>';
			html+='</div>';
			html+='<div class="border-box">';
			html+='	<p>出生日期</p>';
			html+='	<div class="right-box">';
			html+='		<input type="text" value="'+personal.result[0].birthday.substr(0,10)+'" id="build_time" />';
			html+='	</div>';
			html+='</div>';
			html+='<div class="border-box address">';
			html+='	<p style="width:60px">所在地区</p>';
			html+='	<div class="right-box">';
			if(personal.result[0].live_place==""){
				html+='		<input type="text" placeholder="详细街道和具体门牌" id="live_place" maxlength="30" />';
			}else{
				html+='		<input type="text" value="'+personal.result[0].live_place+'" id="live_place" maxlength="30" />';
			}			
			html+='	</div>';
			html+='</div>';
			html+='<div class="border-box">';
			html+='<a href="code.php?pic='+personal.result[0].qc_code+'">';
			html+='	<p>二维码名片</p>';
			html+='	<div class="right-box">';
			html+='		<img src="'+personal.result[0].qc_code+'" width="40" />';
			html+='	</div>';
			html+='</a>';
			html+='</div>';
			html+='<div class="border-box" onclick="reset()">';
			html+='	<p>修改密码</p>';
			html+='	<div class="right-box">';
			html+='		<img src="image/arrow-right.png" width="9" style="margin-top: 21px;" />';
			html+='	</div>';
			html+='</div>';
			html+='<div class="border-box address">';
			html+='	<p style="width:60px">工作单位</p>';
			html+='	<div class="right-box">';
			if(personal.result[0].work_address=='""'||personal.result[0].work_address==""){
				html+='		<input type="text" placeholder="请填写" id="work_address"  maxlength="30" />';
			}else{
				html+='		<input type="text" value="'+personal.result[0].work_address+'" id="work_address" maxlength="30" />';
			}
			html+='	</div>';
			html+='</div>';
			
			$(".content").html(html);
			
			btnhtml="";
			btnhtml+='<div class="btn-box">';
			btnhtml+=' <a href="javascript:;" class="keep" onclick="keep()">保存</a>';
			btnhtml+=' <a href="javascript:;" class="quit">退出登录</a>';
			btnhtml+='</div>';
			
			$(".btn-boxs").html(btnhtml);
			
			winH=$(window).height();
			contH=$(".content").height();
			if(winH-contH>70){
				$(".btn-boxs").addClass("fixed");
			}
			
			//弹框
			$("#sex").click(function(){
				layer.open({
					type: 1,
					closeBtn: 0, //不显示关闭按钮
					title: false,
					shadeClose: true, //开启遮罩关闭
					content: $(".popup")
				})
			})
			
			$(".popup li").click(function(){
				var type=$(this).text();
				$("#sex .right-box input").val(type);
				layer.closeAll();
			})
			winW=$(window).width();
			$(".address").children(".right-box").css("width",winW-95);
			$(".username").children(".right-box").css("width",winW-65);		
			//修改密码
			function reset(){
				location.href='reset_password.php';
			}
			time=personal.result[0].birthday.substr(0,10);
			//时间
			$(function() {			
					var currYear = (new Date()).getFullYear();
					var opt = {};
					opt.date = {
						preset: 'date'
					};
					//opt.datetime = { preset : 'datetime', minDate: new Date(2012,3,10,9,22), maxDate: new Date(2014,7,30,15,44), stepMinute: 5  };
					opt.datetime = {
						preset: 'datetime'
					};
					opt.time = {
						preset: 'time'
					};
					opt.default = {
						theme: 'android-ics light', //皮肤样式
						display: 'modal', //显示方式 
						mode: 'scroller', //日期选择模式
						lang: 'zh',
						startYear: currYear - 80, //开始年份
//						endYear: currYear + 0 //结束年份
						maxDate: new Date()	//从当前年，当前月，当前日结束
					};   
					$("#build_time").val(time).scroller('destroy').scroller($.extend(opt['date'], opt['default']));
					var optDateTime = $.extend(opt['datetime'], opt['default']);
					var optTime = $.extend(opt['time'], opt['default']);
			});
			
			//保存个人信息
			function keep(){
				
				//上传头像
				attrval=$(".upload").attr("org_img");
				console.log(attrval);
				if(attrval!=""){
					$.ajax({
						type:"post",
						url:commonUrl+'api/stj/user/update/updateuser_img'+versioninfo,
						data:{
							'img_url':attrval,
							'ss':session
						},
						success:function(data){
							if(data.code==1){
								console.log(data);
							}else{
								layer.msg(data.msg)
							}
						}
					});
				}
				
				var birthday=$("#build_time").val();
				var live_place=$("#live_place").val();
				var name=$("#user").val();
				var namelength=name.length;
				var sex_id=$("#sex_id").val();
				if(sex_id=="男"){
					sex_id=1;
				}else if(sex_id=="女"){
					sex_id=2;
				}else{
					sex_id=0
				}
				var work_address=$("#work_address").val();
				if(name==""){
					layer.msg("请填写用户名");
				}else{
					$.ajax({
						type:"post",
						url:commonUrl+'api/stj/user/update/updateProfile'+versioninfo,
						data:{
							'birthday':birthday,
							'live_place':live_place,
							'name':name,
							'sex_id':sex_id,
							'work_address':work_address,
							'ss':session
						},
						success:function(data){
							if(data.code==1){
								console.log(data);
								layer.msg("保存成功");
								backlist=function(){
									location.href="home_list.php"
								}
								setInterval(backlist,2000)
							}else{
								layer.msg(data.msg);
							}
						}
					});
										
				}
			}
			//正则判断用户名
			function filter(){
				var name=$("#user").val();
				name=name.replace(/([^0-9a-zA-Z\u4e00-\u9fa5]+)$/,'');//英文字母、数字、中文
				console.log(name);
				$("#user").val(name);
			}
			
			//登录弹框
			$(".quit").click(function(){
				layer.open({
					type: 1,
					closeBtn: 0, //不显示关闭按钮
					title: false,
					shadeClose: true, //开启遮罩关闭
					content: $(".login-pop")
				})
			})
			
			//取消退出
			function cancel(){
				layer.closeAll();
			}
			
			//退出登录
			function confirm(){
	            removeCookie('session');
//	            layer.msg('退出登录成功');
	            loginpage=function(){
	            	location.href="sign_in.php"
	            }
	            setTimeout(loginpage,2000);
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