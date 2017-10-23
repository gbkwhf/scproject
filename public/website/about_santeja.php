萃怡家<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>关于萃怡家</title>
		<link rel="stylesheet" href="css/common.css">	
	    <link rel="stylesheet" href="css/about_santejia.css">
	    
	</head>
	<body>
		<?php include 'header.php' ?>
		<div class="banner">
	    	<div class="content">
	    		<img class="font1" src="images/about/banner-font1.png">
	    		<img class="font2" src="images/about/banner-font2.png">
	    	</div>
	    </div>
		<!--第二部分-->
		<div class="club-profile" id="profile">
			<div class="content">
				<div class="head">
					<p class="tit-ch">俱乐部简介</p>
					<p class="tit-en">CLUB PROFILE</p>
					<div class="more-info">
						我们作为一家医疗健康服务性企业，致力于改变企业管理层及核心员工，中小企业主，高净值人群在健康领域无法享受高端医疗资源，
						没有持续跟踪健康服务的现状，针对国人慢性病频发、重疾发病率居高不下的危机局面，引入日本优质医疗资源以及产业医生制度，
						打造了一个专业的线上和线下相结合的日式健康管理服务平台。
					</div>
				</div>
				<div class="club-concept">
					<img src="images/about/part-left.jpg">
						<div class="concept">
							<p class="tit-ch">俱乐部理念</p>
							<p class="tit-en">Club concept</p>
							<p class="more-info">我们秉承对生命负责、高度责任感、提倡6分健康管理与4分医疗服务的理念，提供个性化健康管理方案，为会员的健康保驾护航。</p>
						</div>
				</div>
			</div>
		</div>
		<!--第三部分-->
		<div class="club-culture" id="concept">
			<div class="content">
				<div class="head">
					<p class="tit-ch">俱乐部文化</p>
					<p class="tit-en">CLUB CULTURE</p>
				</div>
				<div class="culture-det">
					<div class="culcontent">
						<p class="p1">寓意</p>
						<span class="hr"></span>
						<p class="p2">Sante<br>法语的意思为【健康】<br>Ja 的谐音是【家】<br>合在一起就是<span>【健康之家】</span></p>
					</div>
					<div class="culcontent">
						<p class="p1">理念</p>
						<span class="hr"></span>
						<p class="p2 p3">秉承对生命负责、高度责任感<br><span>提倡6分健康管理</span><br><span>与4分医疗服务的理念</span><br>提供个性化健康管理方案<br>为会员的健康保驾护航</p>
					</div>
					<div class="culcontent lastone">
						<p class="p1">愿景</p>
						<span class="hr"></span>
						<p class="p4"><span class="span1">会员人数</span><br><span class="span2">1,000,000</span><br><span class="span1">会员寿命100岁</span></p>
					</div>
				</div>
			</div>
		</div>
		<!--第四部分 运营团队-->
		<div class="team" id="ourteam">
			<div class="header2"></div>
			<div class="content">
				<div class="head">
					<p class="tit-ch">运营团队</p>
					<p class="tit-en">OPERATION TEAM</p>
				</div>
				<div class="team-member">
					<div class="mem"><img src="images/logo2.png"><p>首席执行官</p></div>
					<div class="mem"><img src="images/logo2.png"><p>销售总监</p></div>
					<div class="mem"><img src="images/logo2.png"><p>运营总监</p></div>
					<div class="mem lastone"><img src="images/logo2.png"><p>医务总监</p></div>
				</div>
				<div class="btn">运营团队具有深厚留日背景与丰富的行业经验</div>
			</div>
		</div>
		<!--第五部分 全国服务体系-->
		<div class="service-sys" id="service">
			<div class="content">
				<div class="head">
					<p class="tit-ch">全国服务体系</p>
					<p class="tit-en">NATIONAL SERVICE SYSTEM</p>
				</div>
				<div class="child-company">
					<div class="comp">
						<img src="images/about/shanghai.jpg">
						<div><img src="images/about/position.png">上海总部</div>
					</div>
					<div class="comp">
						<img src="images/about/beijing.jpg">
						<div><img src="images/about/position.png">北京营运中心</div>
					</div>
					<div class="comp lastone">
						<img src="images/about/guangzhou.jpg">
						<div><img src="images/about/position.png">广州营运中心</div>	
					</div>
				</div>
				
			</div>
		</div>
		<!--第六部分 俱乐部大事记-->
		<div class="club-thing" id="events">
			<div class="content">
				<div class="head">
					<p class="tit-ch">大事记</p>
					<p class="tit-en">EVENTS</p>
				</div>
				<div class="timenode">
					<span class="year">2011年</span>
					<p class="whathappen">公司正式成立</p>
					<div class="detail">2011年3月，公司在上海注册成立。</div>
				</div>
				<div class="timenode">
					<span class="year">2016年</span>
					<p class="whathappen">上海运营中心正式成立</p>
					<div class="detail">2016年8月，上海运营中心成立，位于上海市黄浦区。</div>
				</div>
				<div class="timenode">
					<span class="year">2017年</span>
					<p class="whathappen">萃怡家100俱乐部正式设立</p>
					<div class="detail">2017年1月1日，萃怡家100俱乐部在上海设立。</div>
				</div>
			</div>
		</div>
		<?php include 'footer.php' ?>
		<script type="text/javascript" src="js/jquery.min.js"></script>
	    <script>
		    $(function(){
		    	$('#tab>li:eq(1)').addClass('active');
		    });
			maodian(profile);
			maodian(concept);
			maodian(ourteam);
			maodian(service);
			function maodian(m){
				var mao = $("#" + getParam("m")); //获得锚点  
				if (mao.length > 0) { //判断对象是否存在  
					 var pos = mao.offset().top;  
					 var poshigh = mao.height();  
					 $("html,body").animate({ scrollTop: pos }, 500);  
				} 
			}
			//根据参数名获得该参数  pname等于想要的参数名  
			function getParam(pname) {  
				var params = location.search.substr(1); //  获取参数 平且去掉？  
				var ArrParam = params.split('&');  
				if (ArrParam.length == 1) {  
			   //只有一个参数的情况  
					return params.split('=')[1];  
				}  
				else {  
					//多个参数参数的情况  
					for (var i = 0; i < ArrParam.length; i++) {  
						if (ArrParam[i].split('=')[0] == pname) {  
							return ArrParam[i].split('=')[1];  
						}  
					}  
				}  
			}
			
	    </script>
	</body>
</html>
