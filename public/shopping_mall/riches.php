<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>我的财富</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="css/mui.min.css">
		<link rel="stylesheet" href="css/common.css">
		<link rel="stylesheet" type="text/css" href="css/riches.css" />
	</head>

	<body>
		<div id="refreshContainer" class="mui-scroll-wrapper">
			<div class="mui-scroll">
				<div class="richesBox">
					<div class="coountReturn">返利总额（积分）</div>
					<div class="returnNum">100000</div>
				</div>
				<div class="noneBox"></div>
				<div class="recordName">返利记录</div>

				<div class="rebateRecordBox">
					<!--<div class="mallRecordBox">
						<div class="recordLeft">商城返利</div>
						<div class="recordRight">
							<p class="recordMoney">100000元</p>
							<p class="recordTime">2018-02-02 10:10</p>
						</div>
					</div>
					<div class="mallRecordBox">
						<div class="recordLeft">邀请返利</div>
						<div class="recordRight">
							<p class="recordMoney">10000元</p>
							<p class="recordTime">2018-02-02 10:10</p>
						</div>
					</div>-->
				</div>
			</div>
		</div>
	</body>
	<style type="text/css">
		.layui-layer.layui-anim.layui-layer-page {
			border-radius: 5px;
		}
	</style>

</html>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script src="js/mui.min.js"></script>
<script>
	var winH = $(window).height();

	page = 1;
	showajax(page);

	function showajax(page) {
		layer.ready(function(){ layer.load(2); })
		$.ajax({
			type: "post",
			url: commonsUrl + "api/gxsc/get/cashbacklist" + versioninfos,
			data: {
				'ss': getCookie('openid'),
				'page': page
			},
			success: function(data) {
				layer.closeAll();
				if(data.code == 1) { //请求成功
					var t = [];
					var con = data.result;
					if(con.length == 0 && page == 1){
						layer.closeAll();
						$('.rebateRecordBox').html('<p>暂无返利记录哦！</p>');
						$('.rebateRecordBox p').css({'line-height':winH - 236 +'px','text-align': 'center','color':'#8f8f94'});
					}else{
						console.log(con);
						t.push(con);
						console.log(t);
						var html = '';
						$.each(con, function(k, v) {
							var amount = con[k].amount; //商品id
							var created_at = con[k].created_at; //商品名称
							var pay_describe = con[k].pay_describe; //商品图片
							html += '<div class="mallRecordBox">' +
								'<div class="recordLeft">' + pay_describe + '</div>' +
								'<div class="recordRight">' +
								'<div class="recordMoney">' + amount + '</div>' +
								'<div class="recordTime">' + created_at + '</div>' +
								'</div>' +
								'</div>';
						});
						$('.rebateRecordBox').append(html); //动态显示商品列表

					}
					if(con.length > 0) {
						mui('#refreshContainer').pullRefresh().endPullupToRefresh(false);
					} else {
						layer.msg("已经到底了");
						mui('#refreshContainer').pullRefresh().endPullupToRefresh(true);
					}

				} else {
					layer.msg(data.msg);
				}
			}
		});
	}
	mui.init({
		pullRefresh: {
			container: '#refreshContainer', //待刷新区域标识，querySelector能定位的css选择器均可，比如：id、.class等
			auto: true, // 可选,默认false.自动上拉加载一次
			height: 50,
			up: {
				callback: function() {
						page++;
						showajax(page);
						mui('#refreshContainer').pullRefresh().endPullupToRefresh();

					} //必选，刷新函数，根据具体业务来编写，比如通过ajax从服务器获取新数据；
			}

		}
	});
</script>