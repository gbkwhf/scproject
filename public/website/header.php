
	    <link rel="stylesheet" href="css/header.css">
	   
		<div class="header">
			<div class="horizontal">
                <div class="loginmsg" style="text-align: right;line-height: 29px;width: 1000px;margin: 0 auto;font-size:14px;">
                    <span class="logindo" style="cursor: pointer;" onclick="location.href='login.php'">您好，请登录</span>
                    <span style="margin-left: 20px;margin-right: 20px;cursor: pointer" class="regdo"  onclick="location.href='register.php'">[免费注册]</span>
                    <span class="regdo" style="margin-right:27px;cursor: pointer" onclick="location.href='searchReport.php'">查看病历</span>
                </div>
            </div>
			<div class="content">
				<div class="top-logo" onclick="location.href='index.php'">
					<img src="images/logo2.png">
				</div>
				<div class="nav-item">
					<ul id="tab">
						<li><a href="index.php">首页</a></li>
						<li>
							<a href="about_santeja.php">关于萃怡家</a>
							<ul id="about_list">
								<li id="clubpro"><a href="about_santeja.php?mao=profile">俱乐部简介</a></li>
								<li id="clubconcept"><a href="about_santeja.php?mao=concept">俱乐部理念</a></li>
								<li id="clubwish"><a href="about_santeja.php?mao=concept">俱乐部文化</a></li>
								<li id="operteam"><a href="about_santeja.php?mao=ourteam">运营团队</a></li>
								<li id="servsys"><a href="about_santeja.php?mao=service">全国服务体系</a></li>
								<li id="clubthings"><a href="about_santeja.php?mao=events">俱乐部大事记</a></li>
								<li id="report"><a>董事长致辞</a></li>
								<li id="report"><a>企业社会责任</a></li>
							</ul>
						</li>
						<li>
							<a href="vipserv.php?index=2">健康管理</a>
							<ul id="health_manage"></ul>
						</li>
						<li>
							<a href="medical.php">高端医疗</a>
							<ul id="highmedi">
								<li><a href="medical.php">医疗机构</a></li>
								<li><a href="medical_expert.php">医疗专家</a></li>
							</ul>
						</li>
						<li>
							<a href="vipserv.php">尊享服务</a>
							<ul id="enjoyserv">
								<li><a href="vipserv.php?index=7">医疗服务</a></li>
								<li><a href="vipserv.php?index=1">海外医疗</a></li>
								<li><a href="vipserv.php?index=2">健康管理</a></li>
								<li><a href="vipserv.php?index=3">产业医生</a></li>
								<li><a href="vipserv.php?index=4">增值服务</a></li>
								<li><a href="vipserv.php?index=5">个性化订制</a></li>
								<li><a href="vipserv.php?index=6">保险经纪</a></li>
								<li><a href="health_mall.php">健康商城</a></li><!--新加-->
								
							</ul>
						</li>
						<li>
							<a href="contact_us.php">联系我们</a>
							<ul id="consult">
								<li><a href="contact_us.php">我要咨询</a></li>
								<li><a href="join_us.php">申请合作</a></li>
							</ul>
						</li>
						<div class="clearfix"></div>
					</ul>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<!--右侧悬浮窗-->
		<div class="fixed-box">
			<p class="top-title">欢迎咨询<img src="images/close.png" id="close"></p>
			<div class="tel">
				<img src="images/code-phone.png">
				<p>联系电话</p>
				<span class="con_tel">
					<em style="display: block;color:#fff;font-style: normal;font-size:16px;font-weight: 600;line-height: 22px;margin-top:5px;">400-100-8287</em>
					<i style="display: block;color:#fff;font-style: normal;font-size:16px;font-weight: 600;line-height: 22px;">+86（21）5401-8287</i>
				</span>
			</div>
			<div class="coding">
				<img src="images/qrcode.jpg">
				<p>扫描二维码获取更多资讯</p>
			</div>
		</div>
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/common.js"></script>
        <script type="text/javascript" src="js/layer/layer.js"></script>
        <script type="text/javascript" src="js/function.js"></script>
		<script>
            //获取登录状态
            sessionArr = getCookie('sessionArr');
            if(sessionArr){
//              console.log(JSON.parse(sessionArr));
                username = JSON.parse(sessionArr).user_name;
                html = '<span style="margin-right: 6px;">Hi~</span><span style="margin-right:30px;">'+username+'</span><span style="text-decoration: underline;margin-right: 20px;cursor: pointer;" onclick="location.href=\'personal_center.php\'">个人中心</span><span onclick="logoutdo()" style="margin-right: 27px;cursor: pointer;">[退出]</span>'
                $('.loginmsg').html(html);
            }
            function logoutdo(){
                removeCookie('sessionArr');
                //退出登陆
                layer.msg("退出成功",{time:1000},function(){
					location.href="index.php";
					removeCookie('sessionArr');
                });
            }
            $.ajax({
                type:'POST',
                url:commonUrl + 'api/stj/service_order/order_list'+versioninfo,
                data:{
                    'type':2
                },
                success:function(data){
//                  console.log(data);
                    if(data.code == 1){
                        html= '';
                        //列表形式
                        list = data.result.list;
                        for(i=0;i<list.length;i++){
                            html +='<li><a href="vipserv.php?index=2&checkid='+list[i].id+'">'+list[i].title+'</a></li>';
                        }
                        $('#health_manage').html(html);
                    }else{
                        layer.msg(data.msg);
                    }
                }
            });



			$('#tab>li').click(function(){
				$(this).addClass('active');
				$(this).siblings().removeClass('active');
			});
			$('#tab>li').mouseover(function(){
				$(this).children('ul').css('display','block');
			});
			$('#tab>li').mouseout(function(){
				$(this).children('ul').css('display','none');
			});
			$('#close').click(function(){
				$('.fixed-box').css('display','none');
			});
		</script>
        <style>
            .layui-layer-btn .layui-layer-btn0 {
                border-color: #21c0d5;
                background-color: #21c0d5;
                color: #fff;
            }
        </style>