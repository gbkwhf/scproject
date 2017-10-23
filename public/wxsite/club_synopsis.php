<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>俱乐部简介</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>		
		<link rel="stylesheet" type="text/css" href="css/club_synopsis.css" />
	</head>
	<body>
		<div class="container">
			<div class="banner">
				<img src="image/club-ban.png" />
				<p>关于我们</p>
			</div>
			<div class="anchor">
				<a href="javascript:scroll('synopsis');" class="active">简介</a>
				<a href="javascript:scroll('idea');">理念</a>
				<a href="javascript:scroll('moral');">寓意</a>
				<a href="javascript:scroll('vision');">愿景</a>
			</div>
			<div class="explain" id="synopsis">
				<h4>关于我们</h4>
				<p>我们作为一家医疗健康服务性企业,致力于改变企业管理层及核心员工,中小企业主,高净值人群在健康领域无法享受高端医疗资源,没有持续跟踪健康服务的现状，针对国人慢性病频发、重疾发病率居高不下的危机局面，引入日本优质医疗资源以及产业医生制度，打造了一个专业的线上和线下相结合的日式健康管理服务平台。</p>
			</div>
			<p class="grayline"></p>
			<div class="explain" id="idea">
				<h4>我们的理念</h4>
				<p>
					我们秉承对生命负责、高度责任感<br />
					提倡6分健康管理与4分医疗服务的理念<br />
					提供个性化健康管理方案,为会员的健康保驾护航。
				</p>
			</div>
			<p class="grayline"></p>
			<div class="explain center" id="moral">
				<h4>品牌寓意</h4>
				<p>
					Sante<br />
					法语的意思为【健康】<br />
					Ja<br />
					的谐音是【家】<br />
					合在一起就是【健康之家】
				</p>
			</div>
			<p class="grayline"></p>
			<div class="explain center" id="vision">
				<h4>我们的愿景</h4>
				<p>
					会员人数<br />
					1,000,000<br />
					会员寿命100岁
				</p>
			</div>
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script src="js/hovertreescroll.js"></script>
		<script>
			$(".explain:last").css("border-bottom","none")
			function scroll(id) {  
				$("#" + id).HoverTreeScroll(1000);  
			}
			$(".anchor a").click(function(){
				$(this).siblings("a").removeClass("active");
				$(this).addClass("active");
			})
		</script>
	</body>
</html>