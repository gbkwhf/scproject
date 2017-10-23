<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>全国服务体系</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>		
		<link rel="stylesheet" type="text/css" href="css/club_synopsis.css" />
	</head>
	<body>
		<div class="container">
			<div class="banner">
				<img src="image/country.png" />
				<!--<p>标题</p>-->
			</div>
			<div class="anchor">
				<a href="javascript:scroll('benbu');" class="active">本部</a>
				<a href="javascript:scroll('shanghai');">上海</a>
				<a href="javascript:scroll('beijing');">北京</a>
				<a href="javascript:scroll('guangzhou');">广州</a>
			</div>
			<div class="explains" id="benbu">
				<h4>公司总部</h4>
				<p>总部作为一家医疗健康服务性企业,致力于改变企 业管理层员工及中小企业主,高净值人群在健康领域无法享受高端医疗资源,没有持续跟踪服务的现状，针对国人慢性病蔓延、重疾爆发的危机局面，引入日本优质医疗资源以及产业医生制度，打造了一个专业的线上和线下相结合的日式健康管理服务平台。</p>
				<img src="image/company.png" width="280" />
			</div>
			<p class="grayline"></p>
			<div class="explains" id="shanghai">
				<h4>上海</h4>
				<p>上海运营中心作为一家医疗健康服务性企业，致企业管理层员工及中小企业主，高净值人群在健康领域无法享受高端医疗资源,没有持续跟踪服务的现状，针对国人慢性病蔓延、重疾爆发的危机局面，引入日本优质医疗资源以及产业医生制度，打造了一个专业的线上和线下相结合的日式健康管理服务平台。</p>
				<img src="image/shanghai.png" width="280" />
			</div>
			<p class="grayline"></p>
			<div class="explains" id="beijing">
				<h4>北京</h4>
				<p>北京运营中心作为一家医疗健康服务性企业，致企业管理层员工及中小企业主，高净值人群在健康领域无法享受高端医疗资源,没有持续跟踪服务的现状，针对国人慢性病蔓延、重疾爆发的危机局面，引入日本优质医疗资源以及产业医生制度，打造了一个专业的线上和线下相结合的日式健康管理服务平台。</p>
				<img src="image/beijing.png" width="280" />
			</div>
			<p class="grayline"></p>
			<div class="explains" id="guangzhou">
				<h4>广州</h4>
				<p>广州运营中心作为一家医疗健康服务性企业，致企业管理层员工及中小企业主，高净值人群在健康领域无法享受高端医疗资源,没有持续跟踪服务的现状，针对国人慢性病蔓延、重疾爆发的危机局面，引入日本优质医疗资源以及产业医生制度，打造了一个专业的线上和线下相结合的日式健康管理服务平台。</p>
				<img src="image/guangzhou.png" width="280" />
			</div>
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script src="js/hovertreescroll.js"></script>
		<script>
			$(".explains:last").css("border-bottom","none")
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