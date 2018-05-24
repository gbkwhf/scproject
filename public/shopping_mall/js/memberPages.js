$(function() {

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
						//console.log(value.number);
						numberShop += parseInt(value.number)
					})
				})
				console.log(numberShop + 'iiiiiii')
				$('.shopCar span').html(numberShop);

			} else {
				layer.msg(data.msg);
			}
		}
	});
	//----------------动态显示一级分类--------------

	$.ajax({ //获取商品一级分类
		type: "post",
		dataType: 'json',
		url: commonsUrl + "api/gxsc/get/index/data" + versioninfos,
		data: {
			"ss": getCookie('openid') //请求参数  openid
		},
		success: function(data) {
			console.log(data)
			if(data.code == 1) { //请求成功
				var con = data.result.goods_first_list;
				if(con.length != 0) {
					console.log(con);
					var html = '';
					$.each(con, function(k, v) {
						var goods_first_id = con[k].goods_first_id; //一级分类id
						console.log(goods_first_id);
						var imgsrc = 'images/classifyImg' + k + '.png'
						var first_name = con[k].goods_first_name; //分类名称
						html += "<li goods_first_id=" + goods_first_id + "  class='secClick'><img src=" + imgsrc + " /><em>" + first_name + "</em></li>"
					});
					$('.shopContent').append(html); //动态显示分类名称
					$(".secClick").click(function() {
						var goods_first_id = $(this).attr("goods_first_id");
						var first_name=$(this).text();
						location.href = "member_mall_list.php?goods_first_id=" + goods_first_id+"&goods_first_name="+first_name;
						
					})
				} else {
					layer.msg(data.msg);
				}

			}

		}
	});

	//获取新发布的商品
	//	page = 1;
	//	showajax(page);

	//	function showajax(page) {
	//		layer.ready(function() {
	//			layer.load(2);
	//		})
	$.ajax({
		type: "post",
		dataType: 'json',
		url: commonsUrl + 'api/gxsc/get/index/data' + versioninfos,
		data: {
			"ss": getCookie('openid')
		},
		success: function(data) {
			console.log(data)
			layer.closeAll();
			if(data.code == 1) { //请求成功
				var con = data.result.new_goods;
				if(con.length != 0) {
					console.log(con);
					var html = '';
					$.each(con, function(k, v) {
						var goods_id = con[k].goods_id; //商品id
						var ext_id = con[k].ext_id;//扩展表id
						var goods_name = con[k].goods_name; //商品名称
						var images = con[k].image; //商品图片
						var price = con[k].price; //商品价格
						var market_price = con[k].market_price;//市场价
						var spec_name = con[k].spec_name;//规格名称
						console.log(goods_id);
						html += '<div class="shopBox" goods_id=' + goods_id + ' ext_id='+ext_id+' onclick="goDetail(' + ext_id + ')">' +
							'<div class="shopImg"><img src=' + images + ' /></div>' +
							'<div class="shopName">' + goods_name + '</div>' +
							'<div class="shopMessage">' +
							'<div class="shops">' +
							'<span class="shopPrice">￥' + price + '</span>' +
							'<span class="fan"></span>' +
							'</div>' +
							'<div class="toBuy">立即抢购</div>' +
							'</div>' +
							'</div>'
					});
					$('.shop_Box').append(html); //动态显示商品列表

				}else{
					$('.newBox').hide();
				}
//				if(con.length > 0) {
//					mui('#refreshContainer').pullRefresh().endPullupToRefresh(false);
//				} else {
//					layer.msg("已经到底了");
//					mui('#refreshContainer').pullRefresh().endPullupToRefresh(true);
//				}

			} else {
				layer.msg(data.msg);
			}

		}

	});
	//	}
	//	mui.init({
	//		pullRefresh: {
	//			container: '#refreshContainer', //待刷新区域标识，querySelector能定位的css选择器均可，比如：id、.class等
	//			auto: true, // 可选,默认false.自动上拉加载一次
	//			height: 50,
	//			up: {
	//				callback: function() {
	//						page++;
	//						showajax(page);
	//						mui('#refreshContainer').pullRefresh().endPullupToRefresh();
	//
	//					} //必选，刷新函数，根据具体业务来编写，比如通过ajax从服务器获取新数据；
	//			}
	//
	//		}
	//	});
	//banner图轮播
	$.ajax({
		type: "post",
		dataType: 'json',
		url: commonsUrl + 'api/gxsc/get/index/data' + versioninfos,
		data: {
			"ss": getCookie('openid')
		},
		success: function(data) {
			console.log(data)
			if(data.code == 1) { //请求成功
				var con = data.result.banner; //
				var sort = con.sort; //排序
				//---------------循环图片（轮播图）-----
				$.each(con, function(k, v) {
					var src = con[k].img_url; //图片地址
					var imgId = con[k].id; //图片id
					var sort = con[k].sort; //排序
					var t = "<div class='swiper-slide'> <img src=" + src + " /></div>";
					$('.swiper-wrapper').append(t)

				});
			}
			//swiper插件实现轮播图
			var mySwiper = new Swiper('.swiper-container', {
				autoplay: 5000, //可选选项，自动滑动
				loop: true,
				pagination: '.swiper-pagination',
				paginationType: 'custom', //这里分页器类型必须设置为custom,即采用用户自定义配置
				paginationCustomRender: function(swiper, current, total) {
					var customPaginationHtml = "";
					for(var i = 0; i < total; i++) {
						//判断哪个分页器此刻应该被激活  
						if(i == (current - 1)) {
							customPaginationHtml += '<span class="swiper-pagination-customs swiper-pagination-customs-active"></span>';
						} else {
							customPaginationHtml += '<span class="swiper-pagination-customs"></span>';
						}
					}
					return customPaginationHtml;
				}
			});

		}
	});
	//-----------搜索----------
	$(function() {
			$(".searchSub").click(function() {
				var shopName = $(".insearch").val();
				console.log(shopName);
				if(shopName == "" || shopName == undefined) {
					layer.msg("商品名称不能为空");
				} else {
					location.href = "searchShopList.php?shopName=" + shopName;
					
				}

			})
		})
		//改变搜索框的背景色
	$(window).scroll(function() {
		var top = $(".container").offset().top; //获取指定位置
		var scrollTop = $(window).scrollTop(); //获取当前滑动位置
		if(scrollTop > top) { //滑动到该位置时执行代码
			$(".searchTop").addClass("active");
			$(".active .searchSub").addClass("insearchs");
		} else {
			$(".searchTop").removeClass("active");
			$(".searchSub").removeClass("insearchs");
		}
	});
});

function goDetail(ext_id) {
		//		console.log(goods_id);
		location.href = "newShop_details.php?ext_id=" + ext_id;
	}

function waitting() {
	layer.msg('暂未开通，敬请期待哦！')
};