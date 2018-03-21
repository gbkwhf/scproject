$(function() {

	var tarr = [];
	var numberShop = 0;
	//获取购物车的数量
	$.ajax({
		type: "post",
		url: commonsUrl + '/api/gxsc/get/goods/car/commodity/info' + versioninfos,
		data: {
			"ss": getCookie('openid')
		},
		success: function(data) {
			if(data.code == 1) { //请求成功
				console.log(data);
				var arr = data.result.info;
				$.each(arr, function(k, v) {
					$.each(v.goods_list, function(key, value) {
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
	//获取新发布的商品
	$.ajax({
		type: "post",
		dataType: 'json',
		url: commonsUrl + 'api/gxsc/get/new/commodity/class' + versioninfos,
		data: {
			"flag":1,//返利区
			"page": 1 //请求参数  openid
		},
		success: function(data) {
			console.log(data)
			if(data.code == 1) { //请求成功
				var con = data.result;
				if(con.length != 0) {
					console.log(con);
					var html = '';
					$.each(con, function(k, v) {
						var goods_id = con[k].goods_id; //商品id
						var goods_name = con[k].goods_name; //商品名称
						var images = con[k].image; //商品图片
						var price = con[k].price; //商品价格
						var num = con[k].num; //库存
						var time = con[k].time; //商品上架时间
						var sales = con[k].sales; //商品销量
						console.log(goods_id);
						html += '<div class="shopBox" goods_id=' + goods_id + ' onclick="goDetail(' + goods_id + ')">' +
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
				} else {
					$('.newBox').hide();
				}

			}

		}

	});
	//banner图轮播
	$.ajax({
		type: "post",
		dataType: 'json',
		url: commonsUrl + 'api/gxsc/get/banner/list' + versioninfos,
		success: function(data) {
			console.log(data)
			if(data.code == 1) { //请求成功
				var con = data.result; //
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
				autoplay: 1500, //可选选项，自动滑动
				loop: true,
				pagination: '.swiper-pagination',
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
				location.href = "searchShopList.php?vid=1&shopName=" + shopName;
			}

		})
	})

	//公共的底部
//	$('#commId').load('commfooter.php');
//
//	setTimeout(function() { //#e63636
//		$(".memberIndex dd").css('color', '#e63636');
//		$(".memberIndex dt img").attr("src", "images/in2.jpg")
//		$(".shopCar dt img").attr("src", "images/che1.jpg");
//		$(".shopCar dd").css('color', '#333333');
//		$(".personal dt img").attr("src", "images/my.png");
//		$(".personal dd").css('color', '#333333');
//		$('.shopCar').click(function() {
//			location.href = "newShop_cart.php";
//		});
//		$('.personal').click(function() {
//			location.href = "personal_center.php";
//		})
//	}, 100)
});

function goDetail(goods_id) {
	//		console.log(goods_id);
	location.href = "shopDetails.php?goods_id=" + goods_id;
};

function waitting() {
	layer.msg('暂未开通，敬请期待哦！')
};