  $(function() {

  	function shopCar() {
  		//获取购物车中的商品信息
  		$.ajax({
  			type: "post", //请求方式
  			dataType: 'json', //数据格式
  			url: commonsUrl + '/api/gxsc/get/goods/car/commodity/info' + versioninfos, //请求地址
  			data: {
  				"ss": getCookie('openid') //请求参数  openid
  			},
  			success: function(data) {
  				console.log(data)
  				if(data.code == 1) { //请求成功
  					var con = data.result.info;
  					//					console.log(con);
  					var html = '';
  					$.each(con, function(k, v) {
  						var car_id = con[k].car_id; //购物车id
  						var first_class_id = con[k].first_class_id;
  						var created_at = con[k].created_at;
  						var goods_id = con[k].goods_id; //商品id
  						var goods_name = con[k].goods_name == '' ? '无' : con[k].goods_name; //商品名称
  						var goods_price = con[k].goods_price == '' ? '无' : con[k].goods_price; //商品单价
  						var goods_url = con[k].goods_url == '' ? '无' : con[k].goods_url; //商品图片
  						var number = con[k].number == '' ? '无' : con[k].number; //商品数量
  						var state = con[k].state; //商品状态
  						var state1 = con[k].state == '0' ? te(k) : hh(k); //商品状态
  						html += "<div class='shopCartList'><div class='shopCartLeft'><label class='label1'><input  type='checkbox'  class='input'   /><input type='checkbox' state=" + state + " url=" + goods_url + " price=" + goods_price + " inNum=" + number + " name=" + goods_name + " id=" + goods_id + " value=" + car_id + " hidden='hidden' class='hiIn'/></label></div><div class='shopCartRight'><div class='catRightTop'><div class='rightCart1'><span class='imgBoxBorder'><img src=" + goods_url + " alt='' class='carImage'/></span></div><div class='rightCart2'><div class='rightTitleCat'><span>" + goods_name + "</span><span class='deleteImg'><img src='images/shopDelete.png' alt='' style='display:inline-block;vertical-align: middle;width:17px;height:18px;'/></span></div><div class='catPrice'><span class='catPrice1'><span style='font-size: 10px;'>￥</span><span class='priceEvery'>" + goods_price + "</span></span></div></div></div><div class='AddJianBox'><span class='jianId minus' id='minus'>-</span><span style='display: inline-block;' class='numkk'><input type='text' name='' id='' value=" + number + " class='inputNum' readonly='readonly'/></span><span class='jianId addClass' id='plus'>+</span></div></div></div>"
  					});
  					$('.shopCartBox').append(html); //动态显示商品

  					pricenum = data.result.no_return + data.result.return
  					$(".totalPrice").text(pricenum);
  				}
  			}
  		});
  	}
	//商品状态未选中
  	function te(m) {
  		//		alert(m+'mm0');
  		setTimeout(function() {
  			//				console.log( $('.shopCartList').eq(m));
  			$('.shopCartList').eq(m).find('label').removeClass("checked");
  			$('.shopCartList').eq(m).find('input.input').prop('checked', false);

  			allsetTotal();
  			//判断如果所有的上面框选择，复选框是否选择
  			var s = $(".input").length;
  			var a = $(".input:checked").length;
  			if(s == a) {
  				allInput.prop('checked', true);
  				allInput.parent().addClass("checked");
  			} else {
  				allInput.prop('checked', false);
  				allInput.parent().removeClass("checked");
  			}
  		}, 300);
  	}
//商品状态选中
  	function hh(m) {
  		//		alert(m+'mm1');
  		setTimeout(function() {
  			$('.shopCartList').eq(m).find('label').addClass("checked");
  			$('.shopCartList').eq(m).find('input.input').prop('checked', true);
  			allsetTotal();
  			//判断如果所有的上面框选择，复选框是否选择
  			var s = $(".input").length;
  			var a = $(".input:checked").length;
  			if(s == a) {
  				allInput.prop('checked', true);
  				allInput.parent().addClass("checked");
  			} else {
  				allInput.prop('checked', false);
  				allInput.parent().removeClass("checked");
  			}
  		}, 300)

  	}

  	shopCar();
  	//点击取消删除框的事件
  	$('#cancelsId').click(function() {
  			layer.closeAll()
  		})
  		//全选
  	var allInput = $(".input1");
  	allInput.click(function() {
  		if(this.checked == true) {
  			$.ajax({
  				type: "post", //请求方式
  				dataType: 'json', //数据格式
  				url: commonsUrl + '/api/gxsc/update/goods/car/commodity/state' + versioninfos, //请求地址
  				data: {
  					"flag": 2, //请求参数
  					"ss": getCookie('openid') //请求参数  openid
  				},
  				success: function(data) {
  					$(".input").prop('checked', true);
  					$("label").addClass("checked");
  					allsetTotal();
  				}
  			});
  		} else {
  			$.ajax({
  				type: "post", //请求方式
  				dataType: 'json', //数据格式
  				url: commonsUrl + '/api/gxsc/update/goods/car/commodity/state' + versioninfos, //请求地址
  				data: {
  					"flag": 1, //请求参数
  					"ss": getCookie('openid') //请求参数  openid
  				},
  				success: function(data) {
  					$(".input").prop('checked', false);
  					$("label").removeClass("checked");
  					//$(".totalPrice").text("0.00");
  					allsetTotal();
  				}
  			});
  		}
  	});

  	setTimeout(function() {
  			$('.deleteImg').click(function() {
  					var tt = $(this).parent().parent().parent().parent().parent();
  					var car_id = $(this).parent().parent().parent().parent().parent().find("input[class*=hiIn]").val(); //获取car_id的值
  					//点击删除按钮出现的弹框
  					var Layer = layer.open({
  						type: 1,
  						title: false,
  						content: $('.popBox'),
  						btnAlign: 'c',
  						area: ["278px", "97px"],
  						closeBtn: 0,
  						shadeClose: true, //点击遮罩层消失
  						yes: function(Layer) {
  							//vm.updateGoodsClass();
  							layer.close(Layer);
  						},
  						//关闭按钮的回调函数
  						cancel: function() {
  							layer.close();
  						}
  					});
  					//点击确定删除的事件
  					$('#confirmId').click(function() {
  						//-------调用ajax删除商品-----------
  						$.ajax({
  							type: "post", //请求方式
  							dataType: 'json', //数据格式
  							url: commonsUrl + '/api/gxsc/delete/goods/car/commodity' + versioninfos, //请求地址
  							data: {
  								"car_id": car_id, //请求参数
  								"ss": getCookie('openid') //请求参数  openid
  							},
  							success: function(data) { //请求成功
  								console.log(data)
  								$(tt).remove(); //移除当前的商品
  								//循环这些数组，判断前面的复选框选中之后，就把相应的数组删除
  								//判断如果所有的上面框选择，复选框是否选择
  								var s = $(".input").length;
  								var a = $(".input:checked").length;
  								if(s == a) {
  									allInput.prop('checked', true);
  									allInput.parent().addClass("checked");
  								} else {
  									allInput.prop('checked', false);
  									allInput.parent().removeClass("checked");
  								}
  								//								var nval = $('.totalPrice').text(); //获取总价的值
  								//								console.log(nval);
  								//								if(nval != 0.00 || nval != 0) {
  								//									allsetTotal(); //调用总价
  								//								}
  								allsetTotal();
  								//								setTimeout(function() {
  								//									layer.msg('您已经删除该宝贝了');
  								//								}, 800)
  								layer.closeAll();
  								var numNew = $(".shopCartBox .shopCartList").length;
  								if(numNew == 0) { //判断购物车的数量
  									//$('.totalPrice').text('0.00');
  									$('.shopFotter label').removeClass('checked') //移除全选状态
  								}
  							}
  						});
  					})

  				})
  				//----------------单选------
  			$(".input").click(function() {

  				var car_id = $(this).parent().parent().parent().find("input[class*=hiIn]").val(); //获取car_id的值
  				//				console.log( $(this).parent());
  				console.log(car_id + 'kdddddop[p');

  				//给单选框添加checked选项
  				$(this).parent().toggleClass("checked");
  				//如果这个单选框选中
  				if($(this).parent().hasClass('checked')) {
  					$.ajax({
  						type: "post", //请求方式
  						dataType: 'json', //数据格式
  						url: commonsUrl + '/api/gxsc/update/goods/car/commodity/state' + versioninfos, //请求地址
  						data: {
  							"car_id": car_id, //请求参数
  							"ss": getCookie('openid') //请求参数  openid
  						},
  						success: function(data) { //请求成功
  							console.log('这是对的');
  							console.log(data)
  							allsetTotal();

  						}
  					});

  					//					$(this).parent().parent().parent().find('input[class*=inputNum]').removeAttr("readonly");
  					//取当前的选中的数量
  					var t = $(this).parent().parent().parent().find('input[class*=inputNum]').val();
  					console.log(t);
  					//取当前选中的单价
  					var p = $(this).parent().parent().parent().find('span[class*=priceEvery]').text();
  					console.log(p);
  					if(parseInt(t) == "" || undefined || null || isNaN(t) || isNaN(parseInt(t))) {
  						t = 1;
  					}
  					//计算总价
  					var s = parseInt(t) * parseFloat(p);
  					console.log(s + '总价');
  					//获取合计
  					var totalParice = $(".totalPrice").text();
  					console.log(totalParice + "合计");
  					//合计加上当前选中的总价（最后的合计）
  					var t = parseFloat(s) + parseFloat(totalParice);
  					console.log(t + '最后的合计');
  					//给合计赋值    
  					//$(".totalPrice").text(t.toFixed(2))
  				} else { //当前的复选框没有选中
  					$.ajax({ //判断是否选中的ajax接口
  						type: "post", //请求方式
  						dataType: 'json', //数据格式
  						url: commonsUrl + '/api/gxsc/update/goods/car/commodity/state' + versioninfos, //请求地址
  						data: {
  							"car_id": car_id, //请求参数
  							"ss": getCookie('openid') //请求参数  openid
  						},
  						success: function(data) { //请求成功
  							console.log('这是对的');
  							console.log(data)
  							allsetTotal();

  						}
  					});

  					//取当前的选中的数量
  					var t = $(this).parent().parent().parent().find('input[class*=inputNum]').val();
  					console.log(t);
  					//取当前选中的单价
  					var p = $(this).parent().parent().parent().find('span[class*=priceEvery]').text();
  					console.log(p);
  					if(parseInt(t) == "" || undefined || null || isNaN(t) || isNaN(parseInt(t))) {
  						t = 1;
  					}
  					//计算总价
  					var s = parseInt(t) * parseFloat(p);
  					console.log(s + '总价');
  					//获取合计
  					var totalParice = $(".totalPrice").text();
  					console.log(totalParice + "合计");
  					//合计减去当前选中的总价（最后的合计）
  					var t = parseFloat(totalParice) - parseFloat(s);
  					console.log(t + '最后的合计');
  					//给合计赋值      
  					//$(".totalPrice").text(t.toFixed(2))
  				}

  				//判断如果所有的上面框选择，复选框是否选择
  				var s = $(".input").length;
  				var a = $(".input:checked").length;
  				if(s == a) {
  					allInput.prop('checked', true);
  					allInput.parent().addClass("checked");
  				} else {
  					allInput.prop('checked', false);
  					allInput.parent().removeClass("checked");
  				}
  			});
  			//点击增加按钮触发事件++++
  			$(".addClass").click(function() {
  				var mmThis = $(this);
  				var sure1 = $(this).parent().parent().parent();
  				var goods_id = $(sure1).find("input[class*=hiIn]").attr("id"); //获取car_id的值
  				console.log(goods_id + '点击出现购物车id');
  				//调用加号的ajax接口
  				$.ajax({
  					type: "post", //请求方式
  					dataType: 'json', //数据格式
  					url: commonsUrl + '/api/gxsc/update/goods/car/commodity/number' + versioninfos, //请求地址
  					data: {
  						"symbol": 1, //点击加号传1
  						"goods_id": goods_id, //请求参数
  						"ss": getCookie('openid') //请求参数  openid
  					},
  					success: function(data) { //请求成功
  						console.log(data);
  						var nummm = $(mmThis).parent().find('input[class*=inputNum]');
  						//单品数量增加
  						nummm.val(parseInt(nummm.val()) + 1);
  						allsetTotal();
  					}
  				});

  				//前面的复选框有没有选中
  				var isTrue = $(this).parent().parent().parent().find('label[class*=label1]').hasClass('checked');
  				console.log(isTrue);
  				if(isTrue) {
  					var num = $(this).parent().find('input[class*=inputNum]');
  					//单品数量增加
  					//				num.val(parseInt(num.val()) + 1);
  					//				//计算总价
  					var goods_price = parseInt($(this).parent().parent().find('span[class*=priceEvery]').text());
  					console.log(goods_price + '这是单价');
  					var val = parseInt($(num).val());
  					console.log(val + '数量');
  					console.log(goods_price * val + '总价');
  					//					var nextVal = parseInt($(".totalPrice").text()) + (goods_price);
  					//					$(".totalPrice").text(nextVal.toFixed(2));
  				}
  			});

  			//点击减少按钮触发事件--------
  			$(".minus").click(function() {
  				var hhThis = $(this);
  				var sure1 = $(this).parent().parent().parent();
  				var goods_id = $(sure1).find("input[class*=hiIn]").attr("id"); //获取car_id的值
  				console.log(goods_id + '点击出现购物车id');
  				//点击减少按钮的ajax
  				$.ajax({
  					type: "post", //请求方式
  					dataType: 'json', //数据格式
  					url: commonsUrl + '/api/gxsc/update/goods/car/commodity/number' + versioninfos, //请求地址
  					data: {
  						"symbol": 2, //点击减号传2
  						"goods_id": goods_id, //请求参数
  						"ss": getCookie('openid') //请求参数  openid
  					},
  					success: function(data) { //请求成功
  						console.log(data);
  						var tjj = $(hhThis).parent().find('input[class*=inputNum]');
  						if(tjj.val() == "" || undefined || null) {
  							tjj.val(1);
  						}
  						tjj.val(parseInt(tjj.val()) - 1)
  						if(parseInt(tjj.val()) < 1) {
  							tjj.val(1);
  							layer.msg('亲，这个数量不能再少了');
  						}
  						allsetTotal();
  					}
  				});
  				//判断前面的复选框有没有选择
  				var isTrue = $(this).parent().parent().parent().find('label[class*=label1]').hasClass('checked');
  				//获取到input框的值
  				var m = $(this).parent().find('input[class*=inputNum]');
  				var val = parseInt($(m).val());
  				console.log(val + '数量+++++++++++++++++++++++++');
  				//判断如果这个框的值为1，就不能再有点击事件
  				if(val < 1) {
  					isTrue = false;
  					layer.msg('亲，这个数量不能再少了');
  				}
  				//表示前面的复选框已经选中，就可以进行减法计算
  				if(isTrue) {
  					//如果这个框的值为下面的情景，都赋值为1
  					var t = $(this).parent().find('input[class*=inputNum]');
  					if(t.val() == "" || undefined || null) {
  						t.val(1);
  					}
  					//				t.val(parseInt(t.val()) - 1)
  					if(parseInt(t.val()) < 1) {
  						t.val(1);
  						layer.msg('亲，这个数量不能再少了');
  					}
  					//这是进行减的计算，添加到合计中
  					var goods_price = parseInt($(this).parent().parent().find('span[class*=priceEvery]').text());
  					console.log(goods_price + '这是单价--------------------------');
  					var neVal = parseInt($(".totalPrice").text()) - (goods_price);
  					console.log(neVal + '合计的值******************************');
  					//$(".totalPrice").text(neVal.toFixed(2));
  				}
  			});
  		}, 500)
  		//-------结算的点击事件--------------
  	$(".jiesuanStyle").click(function() {
  		//循环这些数组，判断前面的复选框选中之后，
  		var nval = $('.totalPrice').text();
        var returnprice = $('#returnprice').val();
        var noreturnprice = $('#noreturnprice').val();
  		console.log(nval);
  		if(nval != 0.00 || nval != 0) {
            if(returnprice>=1280){
                var arrId = []; //car_id,
                var shopArrId = []; //商品id
                var nameArr = []; //名称
                var priceArr = []; //单价
                var imgUrl = []; //商品图片
                var shopNum = []; //商品数量
                var len = $(".checked input[class*=hiIn]").length;
                $(".checked input").each(function() {
                    if($(this).hasClass("hiIn")) {
                        for(var i = 0; i < len; i++) {
                            var thisVal = $(this).val(); //car_id
                            var shopId = $(this).attr('id'); ////商品id
                            var thisNum = $(this).parent().parent().parent().find('input[class*=inputNum]').val(); //商品数量
                            var thisName = $(this).attr('name'); //商品名称
                            var thisPrice = $(this).attr('price'); //商品单价
                            var thisImg = $(this).attr('url'); //商品图片
                        }
                        shopArrId.push(shopId); //商品id
                        arrId.push(thisVal); //car_id
                        nameArr.push(thisName); //商品名称
                        priceArr.push(thisPrice); //单价
                        imgUrl.push(thisImg); //商品图片
                        shopNum.push(thisNum); //商品数量
                    }
                })
                //console.log(shopArrId);
                //console.log(arrId);
                //console.log(nameArr);
                //console.log(priceArr);
                //console.log(imgUrl);
                //console.log(shopNum);
                var dataArr = [];
                for(var i = 0; i < arrId.length; i++) {
                    var param = {
                        "id": arrId[i],
                        "shopId": shopArrId[i],
                        "name": nameArr[i],
                        "price": priceArr[i],
                        "src": imgUrl[i],
                        "number": shopNum[i]
                    }
                    dataArr.push(param);
                }
                console.log(dataArr);
                localStorage.setItem("moneyArr", JSON.stringify(dataArr));
                window.location.href = 'purchase.php';
            }else{
                layer.msg('您返利区的价钱为'+returnprice+",需大于等于1280");
            }

  		} else {
  			layer.msg('请选择你要结算的宝贝');
  		}
  	})

  	//全选的价格计算
  	function allsetTotal() {
  		//		var s = 0;
  		//		$(".shopCartBox .shopCartList").each(function() {
  		//			var isChecked = $(this).find('label[class*=label1]').hasClass("checked");
  		//			if(isChecked) {
  		//				//计算选中的价格
  		//				var t = $(this).find('input[class*=inputNum]').val();
  		//				console.log(t + '复选框选择到的数量');
  		//				var p = $(this).find('span[class*=priceEvery]').text();
  		//				console.log(p + '复选框选择到的单价');
  		//
  		//				if(parseInt(t) == "" || undefined || null || isNaN(t) || isNaN(parseInt(t))) {
  		//					t = 0;
  		//				}
  		//				s += parseInt(t) * parseFloat(p);
  		//				$(".totalPrice").text(s.toFixed(2));
  		//			} else {
  		//				$(".totalPrice").text("0.00");
  		//			}
  		//		})
  		$.ajax({
  			type: "post", //请求方式
  			dataType: 'json', //数据格式
  			url: commonsUrl + '/api/gxsc/get/goods/car/commodity/info' + versioninfos, //请求地址
  			data: {
  				"ss": getCookie('openid') //请求参数  openid
  			},
  			success: function(data) {
  				if(data.code == 1) { //请求成功
  					pricenum = data.result.no_return + data.result.return;
  					$(".totalPrice").text(pricenum);
                    $("#returnprice").val(data.result.return);
                    $("#noreturnprice").val(data.result.no_return);
  				}
  			}
  		});

  	}

  });