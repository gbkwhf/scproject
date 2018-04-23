<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>商品详情</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/newShop_details.css" />
		<link rel="stylesheet" type="text/css" href="css/swiper-3.4.0.min.css">
	</head>
	<script>
		//解决IOS微信webview后退不执行JS的问题
		window.onpageshow = function(event) {
			if(event.persisted) {
				window.location.reload();
			}
		};
	</script>
	<style type="text/css">
		.arrBox {
			clear: both;
		}
		
		.cl {
			margin-right: 4px;
		}
	</style>

	<body>
		<!-----------顶部固定----------->
		<div class="shopTitle">
			<a class="shop dd" onclick="click_scroll1();">商品</a>
			<a class="details" onclick="click_scroll2();">详情</a>
			<a class="pin" onclick="click_scroll3();">评价</a>

		</div>

		<!-----------轮播图------------->
		<div class="swiper-container aa" id="001">
			<div class="swiper-wrapper"></div>
			<div class="swiper-pagination"></div>
		</div>
		<!--------商品名称-->
		<div class="shopIntroduce"></div>
		<!--------------商品单价------>
		<div class="shopPrice">
			<div class="priceBox"><span class="price"></span><span class="originalCost"></span></div>
			<!--<div class="bor"></div>-->
			<!--<div class="super" onclick="location.href='super_return.php'">超级返?</div>-->
		</div>
		<div class="saleBox">
			<div class="postage">邮费：<span class="postNum"></span></div>
			<div class="sales">销量：<span class="saleNum"></span></div>
		</div>
		<div class="saleBox">
			<div class="postage">返利期限：<span class="postNum2">365</span>天</div>
		</div>
		<div class="saleBox">
			<div class="postage">返利积分：<span class="postNum1"></span></div>

		</div>
		<!-----------选择商品属性-->
		<div class="selectAttributes">
			<div class="attributes"></div>
			<div class="backs"><img src="images/selectBack.png" /></div>
		</div>
		<!-----------选择商品属性弹出层-->
		<div class="selectPopup" style="display: none;" id="dd">
			<div class="hideBox"></div>
			<div class="attributesBox">
				<div class="close"></div>
				<div class="attrHead">
					<!--<div class="attrImg"><img src="images/attrImg.png" /></div>
					<div class="selectName">
						<p class="shop_name">精美琥珀核桃仁</p>
						<p class="selectAttr"><span id="shu1">请选择属性</span><span id="shu2" style="padding-left: 4px;"></span></p>
					</div>-->
					<!--<div class="close"></div>-->
				</div>
				<div class="attrContent">

					<div class="arrGuiGe">
						<!--<div class="arrBox">
							<div class="atrrName">品种</div>
							<div class="attrType">
								<div class="type_one">盒装</div>
								<div class="type_one">袋装</div>
							</div>
						</div>-->

					</div>

					<div class="buyNumBox">
						<div class="buyTitle">购买数量</div>
						<div class="calculateBox">
							<div class="jian">-</div>
							<div class="inputBox"><input type="tel" name="inNum" id="inNum" value="1" readonly="readonly" class="shopNum" /></div>
							<div class="add">+</div>
						</div>
					</div>
				</div>
				<div class="confirm">确定</div>
			</div>
		</div>

		<!--<div class="rebate">
			<h4>利润共享返利条件</h4>
			<ul class="rebate-con">
				<li>
					<em>用户自购利润共享：</em> 必须在平台会员区一次性消费1280元/单（含1280）以上，且订单完成（无退货）。
				</li>
				<li>
					<em>利润共享标准：</em> 每日平台总利润50%÷会员每日订单基数；
				</li>
				<li>
					<em>利润共享天数：</em> 180天，由系统每天自动返还。达到以上条件，平台会根据会员个人所推荐的总人数给予会员个人一定比例的推荐返利，推荐共享的金额每天根据财务数据统计，由系统自动返到会员的平台账户“可用余额”里。
				</li>
			</ul>
		</div>-->
		<div class="kong"></div>
		<!--<div class="shopInformation">
			<div class="shopIntoduce">
				<a href="#002">商品介绍</a>
				<a class="details" onclick="click_scroll2();">详情</a>
			</div>
			<div class="apprarise">
				<a href="#003">评论</a>
			</div>
		</div>-->
		<!-----------底部固定------------->
		<div class="shopBuy">
			<div class="botBox1">
				<!--<div class="store">
					<dl>
						<dt><img src="images/store.png"/></dt>
						<dd>店铺</dd>
					</dl>
				</div>-->
				<div class="shop_car" onclick="location.href='newShop_cart.php'">
					<dl>
						<dt>
							<img src="images/shop_car.png" class="carImg"/>
							<span class="carNum">0</span>
						</dt>
						<dd>购物车</dd>
					</dl>
				</div>
			</div>
			<div class="addCar" id="addCar">加入购物车</div>
			<!--<div class="buyNow">立即结算</div>-->
		</div>
		<span style="display: none;" id="hhId"></span>
		<!------------商品详情------------>
		<div class="detailTitle aa" id="002">商品详情</div>
		<div class="shopImg"></div>
		<!------------商品评价------------>
		<div class="shopApprarise aa" id="003">商品评价</div>
		<div class="apprariseBox">
			<!--<div class="apprariseNav">
				<div class="userMessage">
					<div class="userImg">
						<img src="images/userImg1.png" />
					</div>
					<div class="userName">
						<p class="user-name">外屏总是碎</p>
					</div>
					<div class="apprariseDate">2017-02-22</div>
				</div>
				<div class="evaluationContent"></div>
			</div>-->
		</div>

	</body>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/shopDetails.js"></script>
	<script type="text/javascript" src="js/common.js"></script>
	<script type="text/javascript" src="js/config.js"></script>
	<script type="text/javascript" src="js/swiper-3.4.0.min.js"></script>
	<script type="text/javascript" src="js/shopDetails.js"></script>
	<script type="text/javascript" src="js/layer/layer.js"></script>
	<script type="text/javascript" src="js/jquery.fly.min.js"></script>
	<script type="text/javascript" src="js/requestAnimationFrame.js"></script>
	<script type="text/javascript">
		//根据商品id获取商品详情
		$(function() {
			shopCarts(); //页面加载的时候显示购物车的数量
			var ext_id = $_GET['ext_id']; //获取商品id
			console.log(ext_id + '+++++');
			getCon(ext_id);
			var exYId = ''; //点击加入购物车  确定的exit_id;
			function getCon(ext_id) {
				$.ajax({
					type: "post",
					dataType: 'json',
					url: commonsUrl + 'api/gxsc/getgoodsextendinfo' + versioninfos,
					data: {
						"ss": getCookie('openid'),
						"ext_id": ext_id
					},
					success: function(data) {
						console.log(data)
						if(data.code == 1) { //请求成功
							var con = data.result; //
							var content = con.content; //商品详情
							var ext_id = con.ext_id; //商品扩展id
							var g_id = con.g_id; //商品id
							var goods_name = con.goods_name; //商品名称
							var goods_image = con.goods_image; //商品图片列表
							var market_price = con.market_price; //市场价
							var rebate_amount = con.rebate_amount; //返利金额
							var sales = con.sales; //商品销量
							var price = con.price; //商品单价
							var shipping_price = con.shipping_price; //商品运费
							var spec_name = con.spec_name; //规格名
							var spec_value = con.spec_value; //规格值
							var store_id = con.store_id; //店id
							var value_list = con.value_list; //规格值列表
							var biaoqian = '';
							var nameBox = $(".arrBox");
							$("#hhId").attr("goosId", g_id);
							//------------进行赋值---------------
							$('.swiper-pagination-total').html(goods_image.length); //轮播图计数
							$('.shopIntroduce').html(goods_name); //商品名
							$('.price').html('¥' + price); //商品单价
							$('.originalCost').html('原价' + market_price + '元')
							$('.shopImg').html(content); //商品内容
							$('.saleNum').html(sales); //销量
							$('.postNum').html(shipping_price + '元'); //运费
							$('.postNum1').html(rebate_amount + '积分'); //返利积分

							//---------------循环图片（轮播图）-----
							$.each(goods_image, function(k, v) {
								var src = goods_image[k].image;
								var imgId = goods_image[k].img_id;
								var t = "<div class='swiper-slide'><image src=" + src + "/></div>";
								$('.swiper-wrapper').append(t)
							});

							var img = '';
							var imConShow = $(".swiper-slide-active img").attr("src");
							img += '<div class="attrImg"><img src=' + imConShow + ' /></div>' +
								'<div class="selectName">' +
								'<p class="shop_name">' + goods_name + '</p>' +
								'<p class="selectAttr"></p>' +
								'</div>';
							$('.attrHead').html(img);
							$.each(nameBox, function(k, v) {
								var conTT = $(v).find(".typeHide").html();
								var attrCon = $(v).find(".typeHide").attr('data-id');
								if(conTT == undefined) {
									conTT = '请选择' + arr[k].name;

								} else {
									conTT = conTT;
								}
								biaoqian += '<span class="cl" attrCon=' + attrCon + '>' + conTT + '</span>'
							});
							$(".selectAttr").html('已选：' + biaoqian);
							$('.attributes').html('已选：' + biaoqian);
						}
						//swiper插件实现轮播图
						var mySwiper = new Swiper('.swiper-container', {
							paginationType: 'fraction', //分页器
							loop: true,
							pagination: '.swiper-pagination',
						});

					}
				});
			}
			setTimeout(function() {
				var biaoqian = '';
				var arrBox = $(".arrBox")
				$.each(arrBox, function(k, v) {
					$(v).find(".attrType .type_one").eq(0).addClass("typeHide").siblings().removeClass("typeHide");
					var conTT = $(v).find(".typeHide").html();
					var attrCon = $(v).find(".typeHide").attr('data-id');
					if(conTT == undefined) {
						conTT = '请选择' + arr[k].name;

					} else {
						conTT = conTT;
					}
					biaoqian += '<span class="cl" attrCon=' + attrCon + '>' + conTT + '</span>'

					$(".selectAttr").html('已选：' + biaoqian);
					$('.attributes').html('已选：' + biaoqian);

					var c1 = $(".selectAttr .cl");
					var sum = '';
					$.each(c1, function(k, v) {
						console.log('efff');
						sum += $(v).attr("attrCon");
					});
					console.log(sum);
					var conId = '';
					for(var i = 0; i < valueList.length; i++) {
						if(sum == valueList[i].nameId) {
							console.log('已经选择好了 ddddd');
							conId = valueList[i].ext_id;
							console.log(conId);
							exYId = conId;
							console.log(exYId + 'ddddd');
						}
					}
				});

			}, 500);
			//---------------评价列表-------------

			var winH = $('.apprariseBox').height();
			$.ajax({
				type: "post",
				dataType: 'json',
				url: commonsUrl + 'api/gxsc/getgoodsextendinfo' + versioninfos,
				data: {
					"ss": getCookie('openid'),
					"ext_id": ext_id
				},
				success: function(data) {
					console.log(data)
					if(data.code == 1) {
						var cont = data.result.comment_list;
						if(cont.length != 0) {
							var html = '';
							$.each(cont, function(k, v) {
								var comment_image = cont[k].comment_image; //评论图
								var create_time = cont[k].create_time; //时间
								var images = cont[k].image; //用户头像
								var name = cont[k].name; //用户名
								var user_comment = cont[k].user_comment; //评论内容
								if(images == '') {
									thumbnail_image_url = 'images/head-portrait.png'
								}
								html += '<div class="apprariseNav">' +
									'<div class="userMessage">' +
									'<div class="userImg">' +
									'<img src=' + images + ' />' +
									'</div>' +
									'<div class="userName">' +
									'<p class="user-name">' + name + '</p>' +
									'</div>' +
									'<div class="apprariseDate">' + create_time + '</div>' +
									'</div>' +
									'<div class="evaluationContent">' + user_comment + '</div>' +
									'</div>'
							});
							$('.apprariseBox').append(html); //动态显示邀请列表
						} else {
							$('.apprariseBox').html('<p>暂无评论哦!</p>');
							$('.apprariseBox p').css({
								'text-align': 'center',
								'color': '#c6bfbf',
								'line-height': winH + 'px'
							});
						}
					} else {
						layer.msg(data.msg);
					}
				}
			});
			//获取购物车中的商品数量
			function shopCarts() {
				var tarr = [];
				var numberShop = 0;
				//获取购物车的数量
				$.ajax({
					type: "post",
					url: commonsUrl + '/api/gxsc/v2/get/goods/car/info' + versioninfos,
					data: {
						"ss": getCookie('openid')
					},
					success: function(data) {
						if(data.code == 1) { //请求成功
							console.log(data);
							var arr = data.result.info;
							$.each(arr, function(k, v) {
								$.each(v.others, function(key, value) {
									numberShop += parseInt(value.number)
								})
							})
							console.log(numberShop + 'iiiiiii')
							$('.carNum').html(numberShop);

						} else {
							layer.msg(data.msg);
						}
					}
				});
			};
			//商品规格选择
			shopGuiCHE(ext_id);
			var valueList = [];
			var arr = [];

			function shopGuiCHE(ext_id) {
				arr = [];
				valueList = [];
				$.ajax({
					type: "post",
					url: commonsUrl + 'api/gxsc/getgoodsextendinfo' + versioninfos,
					data: {
						"ss": getCookie('openid'),
						"ext_id": ext_id
					},
					success: function(data) {
						if(data.code == 1) { //请求成功
							console.log(data);
							var con = data.result;
							var goods_name = con.goods_name;
							var goods_image = con.goods_image.image;
							var value_list = con.value_list;
							var spec_name = con.spec_name;
							var spec_value = con.spec_value;

							var index = 0;
							for(var k in spec_name) {
								var obj = {};
								obj.name = spec_name[k];
								obj.id = k;
								obj.child = [];
								arr.push(obj)
							}

							for(var j in spec_value) {
								for(var m in spec_value[j]) {
									var objC = {};
									objC.name = spec_value[j][m];
									objC.id = m;
									arr[index].child.push(objC);
								}
								index++;
							}
							console.log(arr);
							var maxArr = arr;
							var attBiaoqian = '';
							for(var i = 0; i < maxArr.length; i++) {
								attBiaoqian += '<span class="cl" >' + arr[i].name + '</span>'
							}
							$(".selectAttr").html('请选择' + attBiaoqian);
							$('.attributes').html('请选择' + attBiaoqian);
							var htmlTen = "";
							for(var i = 0; i < maxArr.length; i++) {
								htmlTen += '<div class="arrBox"><div class="atrrName">' + maxArr[i].name + '</div><div class="attrType">';
								for(var j = 0; j < maxArr[i].child.length; j++) {
									htmlTen += '<div class="type_one" data-id=' + maxArr[i].child[j].id + '>' + maxArr[i].child[j].name + '</div>'
								}
								htmlTen += "</div></div>";
							}
							$(".arrGuiGe").html(htmlTen);

							for(var h in value_list) {
								var listObj = {};
								listObj.nameId = h;
								listObj.ext_id = value_list[h].ext_id;
								valueList.push(listObj);
							}
							console.log(valueList);

						} else {
							layer.msg(data.msg);
						}

					}
				});
			};

			setTimeout(function() {
				$(".arrGuiGe .type_one").click(function() {
					var conSpan = $(this).html();
					$(this).addClass("typeHide").siblings().removeClass("typeHide");
					var biaoqian = '';
					var nameBox = $(".arrBox");
					//							console.log(nameBox.length);

					$.each(nameBox, function(k, v) {
						var conTT = $(v).find(".typeHide").html();
						var attrCon = $(v).find(".typeHide").attr('data-id');
						if(conTT == undefined) {
							conTT = '请选择' + arr[k].name;

						} else {
							conTT = conTT;
						}
						biaoqian += '<span class="cl" attrCon=' + attrCon + '>' + conTT + '</span>'
					});
					$(".selectAttr").html('已选：' + biaoqian);
					$('.attributes').html('已选：' + biaoqian);

					var c1 = $(".selectAttr .cl");
					var sum = '';
					$.each(c1, function(k, v) {
						console.log('efff');
						sum += $(v).attr("attrCon");
					});
					console.log(sum);
					var conId = '';
					for(var i = 0; i < valueList.length; i++) {
						if(sum == valueList[i].nameId) {
							console.log('已经选择好了');
							conId = valueList[i].ext_id;
							console.log(conId);
							exYId = conId;
							getCon(conId);
						}
					}

				});
				//---------创建购物车--------------
				$(".confirm").click(function() {
					var tArray = [];
					var nameBox1 = $(".arrBox");
					console.log(nameBox1);
					$.each(nameBox1, function(k, v) {
						var conTjj = $(v).find(".typeHide").html();
						if(conTjj == undefined) {} else {
							tArray.push(conTjj);
						}

					});
					if(tArray.length < nameBox1.length) {
						layer.msg('请选择商品属性');
					} else {
						console.log(tArray); //只想打印一个
					}
					var goods_id = $("#hhId").attr("goosId");
					var ext_id = $_GET['ext_id'];
					var shopNum = $('.shopNum').val();
					$.ajax({
						type: "post", //请求方式
						dataType: 'json', //数据格式
						url: commonsUrl + '/api/gxsc/v2/add/goods/car' + versioninfos, //请求地址
						data: {
							"ext_id": exYId,
							"goods_id": goods_id, //请求参数
							"ss": getCookie('openid'), //请求参数  openid
							"number": shopNum
						},
						success: function(data) { //请求成功
							console.log(data);
							//							layer.msg("<span style='color: red;font-size: 30px;'>√</span><br/>添加成功，在购物车等亲~~")
							if(data.code == 1) {
								layer.msg("亲，添加成功，在购物车等您哦！");
							} else {
								layer.msg(data.msg);
							}
							shopCarts();
						}
					});

				})
			}, 300)

		});
	</script>
	<script type="text/javascript">
		function click_scroll1() {
			var scroll_offset = $("#001").offset(); //得到pos这个div层的offset，包含两个值，top和left 
			$("body,html").animate({
				scrollTop: scroll_offset.top //让body的scrollTop等于pos的top，就实现了滚动 
			}, 0);
		}

		function click_scroll2() {
			var scroll_offset = $("#002").offset(); //得到pos这个div层的offset，包含两个值，top和left 
			$("body,html").animate({
				scrollTop: scroll_offset.top //让body的scrollTop等于pos的top，就实现了滚动 
			}, 0);
		}

		function click_scroll3() {
			var scroll_offset = $("#003").offset(); //得到pos这个div层的offset，包含两个值，top和left 
			$("body,html").animate({
				scrollTop: scroll_offset.top //让body的scrollTop等于pos的top，就实现了滚动 
			}, 0);
		}
	</script>
	<script type="text/javascript">
		$(function() {
			$('.selectAttributes,.addCar').click(function() {
				$('.selectPopup').fadeIn();
			});
			$('.close,.hideBox,.confirm').click(function() {
				$('.selectPopup').fadeOut();
			});

		})
	</script>
	<script type="text/javascript">
		$('.type_one').click(function() {
			if($(this).hasClass('typeHide')) {
				$(this).removeClass('typeHide');
			} else {
				$(this).addClass('typeHide').siblings().removeClass('typeHide');
			}
		})
		$(".add").click(function() {
			var num = $(this).parent().find('input[class*=shopNum]'); //获取input框的值
			//单品数量增加
			num.val(parseInt(num.val()) + 1);
		});
		$(".jian").click(function() {
			var m = $(this).parent().find('input[class*=shopNum]'); //获取input框的值
			//对input框的值进行判断
			if(m.val() == "" || undefined || null) {
				m.val(1);
			}
			m.val(parseInt(m.val()) - 1) //给input框赋值
				//对input框的值进行判断
			if(parseInt(m.val()) <= 1) {
				m.val(1);
				layer.msg('亲，这个数量不能再少了');
			}
			var val = parseInt($(m).val());
			console.log(val + '数量------');

		})
	</script>
	<script type="text/javascript">
		//		$(document).ready(function() {
		//			$(window).scroll(function() {
		//				var top = $(".swiper-container").offset().top; //获取指定位置
		//				var scrollTop = $(window).scrollTop(); //获取当前滑动位置
		//				if(scrollTop > top){                 //滑动到该位置时执行代码
		//                 $(".shopTitle").addClass("active");
		//                 $(".active a .dd").css({"color":"#ffffff"});
		//               }else{
		//                 $(".shopTitle").removeClass("active");
		//               }
		//			});
		//		});
	</script>

</html>