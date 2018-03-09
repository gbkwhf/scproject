	$(function() {
		//点击删除按钮出现的弹框
		setTimeout(function() {
			$('.dleconImg').click(function() {
				var mThis = $(this);
				var thPar = $(this).parents('.storeConHei');
				var thgo_id = $(this).attr('thgo_id');
				var seParBox = $(this).parents(".shopCartCon");
				var Layer = layer.open({
					type: 1,
					title: false,
					content: $('.popBox'),
					btnAlign: 'c',
					area: ["278px", ""],
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
				$('#confirmkk').click(function() { //确定
					//请求删除的接口 成功之后做下面的逻辑-----------------------ajax

					$(thPar).remove();
					layer.closeAll();
					var getStrC = $(seParBox).find('.storeConHei').length;
					if(getStrC == 0) { //做店铺的删除
						$(seParBox).remove();
					}

					//获取所有的input   -------店铺的全选
					var getAllIn = $(seParBox).find('.childInput').length;
					//获取所有选中的input
					var getCheckIn = $(seParBox).find(".childInput[checkcon='true']").length;
					if((getAllIn == getCheckIn) && (getCheckIn > 0)) {
						//店铺的全选
						$(seParBox).find('.seconLabel').addClass('checked');
						$(seParBox).find('.seconParent').attr('checkCon', 'true');
					} else {
						//店铺的全选
						$(seParBox).find('.seconLabel').removeClass('checked');
						$(seParBox).find('.seconParent').attr('checkCon', 'false');
					}

					//获取所有的input ---------总全选
					var getLarge_In = $(".shopBox1").find("input[type='checkbox']").length;
					//获取所有选中的input
					var getCheck_In = $(".shopBox1").find("input[checkcon='true']").length;
					console.log(getCheck_In);
					if((getLarge_In == getCheck_In) && (getCheck_In > 0)) {
						$(".label1").addClass("checked");
						$(".oneParent").attr('checkCon', 'true');
					} else {
						$(".label1").removeClass("checked");
						$(".oneParent").attr('checkCon', 'false');
					}

					//如果shopBox没有内容(最大的全选)
					var numNew = $(".shopBox1 .shopCartCon").length;
					if(numNew == 0) { //最大的全选
						$("label").removeClass("checked");
						$(".input").attr('checkCon', 'false');
					}

				})
			})
		}, 300)

		//点击取消删除框的事件
		$('#cancelsId').click(function() {
			layer.closeAll()
		})

		setTimeout(function() {
			//点击加号
			$(".addClass").click(function() {
				var addSib = $(this).parents(".addBox").find(".inTeCon"); //找数字input框

				var addVal = $(this).parents(".addBox").find(".inTeCon").val(); //取数子框的值
				//单品数量增加
				addSib.val(parseInt(addVal) + 1);

			})

			//点击减号
			$(".minClass").click(function() {
				var minSib = $(this).parents(".addBox").find(".inTeCon"); //找数字input框

				var minVal = $(this).parents(".addBox").find(".inTeCon").val(); //取数子框的值
				//单品数量增加
				minSib.val(parseInt(minVal) - 1);
				if(parseInt(minVal) <= 1) {
					minSib.val(1);
					layer.msg('亲，这个数量不能再少了');
				}

			})

			//总多选
			var allInput = $(".oneParent");
			allInput.click(function() {
				if(this.checked == true) {
					$("label").addClass("checked");
					$(".input").attr('checkCon', 'true');
				} else {
					$("label").removeClass("checked");
					$(".input").attr('checkCon', 'false');
				}
			});

			//店铺（全选）选择
			$(".seconParent").click(function() {
				$(this).parent().toggleClass("checked");
				var checkTrue = $(this).parent().hasClass('checked');
				if(checkTrue == true) {
					$(this).attr('checkCon', 'true');
					$(this).parent().parent().parent().parent().find(".childLabel").addClass("checked");
					$(this).parent().parent().parent().parent().find(".childInput").attr('checkCon', 'true');
				} else {
					$(this).attr('checkCon', 'false');
					$(this).parent().parent().parent().parent().find(".childLabel").removeClass("checked");
					$(this).parent().parent().parent().parent().find(".childInput").attr('checkCon', 'false');
				}

				//获取所有的input---------总的全选
				var getLarge_In = $(this).parents(".shopBox1").find("input[type='checkbox']").length;
				//获取所有选中的input
				var getCheck_In = $(this).parents(".shopBox1").find("input[checkcon='true']").length;
				if(getLarge_In == getCheck_In) {
					$(".label1").addClass("checked");
					$(".oneParent").attr('checkCon', 'true');
				} else {
					$(".label1").removeClass("checked");
					$(".oneParent").attr('checkCon', 'false');
				}

			});
			//点击单选（单个商品的选中）
			var childInput = $('.childInput');
			childInput.click(function() {
				//找他属于商铺的父级
				var sePar = $(this).parents(".shopCartCon");
				//点击添加样式，再次点击隐藏（父级）
				$(this).parent().toggleClass("checked");
				//如果父级有选中，则让本身添加选中，否则不选中
				if($(this).parent().hasClass('checked')) {
					$(this).attr('checkCon', 'true');
				} else {
					$(this).attr('checkCon', 'false');
				}
				//获取所有的input   -------店铺的全选
				var getAllIn = $(this).parents(".storeCon").find('.childInput').length;
				//获取所有选中的input
				var getCheckIn = $(this).parents(".storeCon").find(".childInput[checkcon='true']").length;
				if(getAllIn == getCheckIn) {
					$(sePar).find('.seconLabel').addClass('checked');
					$(sePar).find('.seconParent').attr('checkCon', 'true');
				} else {
					$(sePar).find('.seconLabel').removeClass('checked');
					$(sePar).find('.seconParent').attr('checkCon', 'false');
				}
				//获取所有的input---------总的全选
				var getLarge_In = $(this).parents(".shopBox1").find("input[type='checkbox']").length;
				//获取所有选中的input
				var getCheck_In = $(this).parents(".shopBox1").find("input[checkcon='true']").length;
				if(getLarge_In == getCheck_In) {
					$(".label1").addClass("checked");
					$(".oneParent").attr('checkCon', 'true');
				} else {
					$(".label1").removeClass("checked");
					$(".oneParent").attr('checkCon', 'false');
				}

			})

		}, 300)

		//公共的底部
		$('#commId').load('commfooter.php');
		setTimeout(function() { //#e63636
			$(".memberIndex dd").css('color', '#333333');
			$(".memberIndex dt img").attr("src", "images/in1.jpg")
			$(".shopCar dt img").attr("src", "images/che2.jpg");
			$(".shopCar dd").css('color', '#e63636');

			$('.memberIndex').click(function() {

				location.href = "memberPages.php";

			})
		}, 100)

	})