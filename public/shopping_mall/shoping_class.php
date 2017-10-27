<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>商城</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="format-detection" content="telephone=no" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link href="css/common.css" type="text/css" rel="stylesheet" />
		<style>
			body{
				background: #f3f5f7;
			}
			.container{
				padding:27px 12px;			
				
			}
			.container>ul{
				overflow: hidden;
				width:100%;
			}
			.container>ul>li{
				float:left;
				width:48%;
				text-align: center;
				background: #fff;
				padding:33px 0;
				box-sizing: border-box;
				margin-bottom:6px;
				border-radius: 5px;
				border: 1px solid #e6e6e6;
				box-shadow:0 3px 5px  #e3e5e7;
				
			}
			.container>ul>li>img{
				width:42px;
				height:44px;
				vertical-align: top;
				
			}
			.container>ul>li>em{
				font-style: normal;
				font-size: 15px;
				color:#4d4d4d;
				display: block;
				width:100%;
				text-align: center;
				margin-top:15px;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<ul>
				<li style="margin-right:9px;" onclick="location.href=''">
					<img src="images/tutechan.png"/>
					<em>食品土特产</em>
				</li>
				<li onclick="location.href=''">
					<img src="images/jiaju.png" />
					<em>家居家装</em>
				</li>
				<li style="margin-right:9px;" onclick="location.href='healthy_mall_list.php'">
					<img src="images/health_shoping.png"/>
					<em>健康商城</em>
				</li>
				<li onclick="location.href='http://ysbt.kospital.com/wx/index.php'">
					<img src="images/linyi_shop.png"/>
					<em>聆医管</em>
				</li>
				<li style="margin-right:9px;" onclick="location.href=''">
					<img src="images/life.png"/>
					<em>生活缴费</em>
				</li>
				<li onclick="location.href=''">
					<img src="images/Customer.png"/>
					<em>售后交流</em>
				</li>
			</ul>
			
		</div>
	</body>
	<script src="js/common.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="js/config.js"></script>
	
	<script type="text/javascript" src="js/jquery.min.js"></script>
</html>
