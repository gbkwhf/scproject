<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>申请合作</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/vip_introduce.css" />
	</head>
	<body>
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
				<input type="text" placeholder="手机号码" id="uphone" maxlength="11" />
			</div>
			<div class="border-box" id="hz-type">
				<div class="pic-box">
					<img src="image/type-icon.png" width="16px" style="margin-top: 20px;" />
				</div>
				<input type="text" placeholder="选择合作类型" id="ctype" readonly="readonly" />
			</div>
			<p class="sub-tit">合作意向</p>
			<textarea rows="6" id="intention"></textarea>
			<a href="javascript:;" class="submit" onclick="submits()">提交</a>
		</div>
		<!--弹窗-->
		<ul class="popup" style="display: none;width: 180px;">
			<!--<li>医疗机构</li>
			<li>医疗专家</li>
			<li>企业合作</li>-->
		</ul>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script src="js/layer/layer.js"></script>
		<script>
			
			session = getCookie('session');
			session=session.substr(1,session.length-2);
		
			$("#hz-type").click(function(){
				layer.open({
					type: 1,
					closeBtn: 0, //不显示关闭按钮
					title: false,
					shadeClose: true, //开启遮罩关闭
					content: $(".popup")
				})
			})
			
			
			function submits(){
				uname=$('#uname').val();
				uphone=$('#uphone').val();
				uphonelength=uphone.length;
				intention=$('#intention').val();
				ctype=$('#ctype').val();
				
				var myreg = /^1[034578][0-9]{9}$/; //正则判断手机号是否有效
				
				if(uname == ''||uphone==''||intention==''||ctype==''){
                    layer.msg("请填写完整！");
                }else if(uphonelength<11||!myreg.test($("#uphone").val())){
                	layer.msg("请输入11位有效手机号")
                }else{
                	$.ajax({
                		type:"post",
                		url:commonUrl+'api/stj/cooperation_apply'+versioninfo,
                		data:{
                			'content':intention,
                			'mobile':uphone,
                			'name':uname,
                			'ss':session,
                			'type':hztype
                		},
                		success:function(data){
                			if(data.code==1){
                				console.log(data);
                				layer.msg("提交成功");
                				backlist=function(){
                					location.href="home_list.php"
                				}
                				setInterval(backlist,2000)
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
			
			$.ajax({
				type:"post",
				url: commonUrl + 'api/stj/getquestiontype'+versioninfo,
				async:false,
				data:{
					'type':1
				},
				success:function(ret){
					if(ret.code==1){
						console.log(ret);
						html="";
						for(var i=0;i<ret.result.length;i++){
							html+='<li type='+ret.result[i].id+'>'+ret.result[i].name+'</li>';
						}
						$(".popup").html(html);
						
						
						$(".popup li").click(function(){
							var type=$(this).text();
							$("#hz-type input").val(type);
							hztype=$(this).attr('type');
							layer.closeAll();
						})
					}else if(ret.code==1011){
						layer.msg('该用户登陆数据已过期，请重新登陆');
	                	setTimeout("location.href='sign_in.php'",1000);
					}else{
        				layer.msg(ret.msg);
        			}
				}
			});
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