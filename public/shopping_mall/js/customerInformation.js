$(function() {
	$('.giftName').click(function() {
		$(this).addClass('gifStyle').parent().siblings().find(".giftName").removeClass("gifStyle");
	});

	$(".shop_img").click(function() {
		$(this).parent().find(".giftName").addClass("gifStyle").parent().siblings().find(".giftName").removeClass("gifStyle");
	})
});
$(function() {
	$('.phoneNum').blur(function() {
		var userPhoneNum = $('.phoneNum').val(); //获取用户手机号
		$.ajax({ //检查用户手机号码
			type: "post", //请求方式
			dataType: 'json',
			url: commonsUrl + 'api/gxsc/checkmemberreceive' + versioninfos, //请求接口
			data: {
				"user_mobile": userPhoneNum //用户手机号
			},
			success: function(data) {
				if(data.code == 1) { //请求成功
					if(data.result.tips) {
						layer.msg(data.result.tips);
					}
					//判断当用户未领取的时候直接跳转						
					if(data.result.receive_time == 0) {
						layer.msg(data.result.tips);
						location.href = "enrollSuccess.php?user_id=" + data.result.user_id + "&git=" + data.result.tips;
					} else {
						$(".staffPhoneNum").blur(function() {
							var em_PhoneNum = $('.staffPhoneNum').val(); //员工手机号
							$.ajax({ //检查员工信息
								type: "post",
								dataType: 'json',
								url: commonsUrl + 'api/gxsc/checkemployeeinfo' + versioninfos,
								data: {
									"user_mobile": em_PhoneNum //员工手机号
								},
								success: function(data) {
									console.log(data)
									if(data.code == 1) { //请求成功
										var con = data.result; //
										var agency_name = con.agency_name; //店名
										var user_name = con.user_name; //员工姓名
										//------------进行赋值---------------
										$('.shopNames').val(agency_name); //店名
										$('.employ_name').val(user_name); //员工姓名
										$('.confirm_sub').click(function() {//点击确定按钮提交数据
											//获取input框输入的值
											inputForNum = $('.phoneNum').val(); //获取用户手机号
											inputForName = $('.names').val(); //用户名
											inputAddress = $('.userAddress').val(); //地址
											giftId = $(".gifStyle").attr('checkId'); //礼品id
											inputStaffNum = $('.staffPhoneNum').val(); //员工手机号
											console.log(giftId);
											console.log(inputForNum + 'ddddd');
											console.log(inputForName + 'ddddd');
											console.log(inputAddress + 'ddddd');
											console.log(inputStaffNum + 'ddddd');
											if(!testTel(inputForNum) || inputForNum == '' || inputForNum == undefined || inputForNum == null) { //对输入的值进行判断
												layer.msg("请输入正确的手机号码");
											} else if(inputForName == "" || inputForName == undefined) {
												layer.msg("姓名不能为空");
											} else if(inputAddress == "" || inputAddress == undefined) {
												layer.msg("地址不能为空");
											} else if(!testTel(inputStaffNum) || inputStaffNum == '' || inputStaffNum == undefined || inputStaffNum == null) {
												layer.msg("请输入正确的手机号码");
											} else {
												var formData = new FormData(); //创建一个空的formData对象用来保存变量参数
												formData.append("user_mobile", inputForNum);
												formData.append("name", inputForName);
												formData.append("address", inputAddress);
												formData.append("employee_mobile", inputStaffNum);
												formData.append("gift", giftId);
												layer.load(2);
												$.ajax({ //邀请新用户进店
													type: "post", //请求方式
													dataType: 'json',
													url: commonsUrl + 'api/gxsc/invitemember' + versioninfos, //请求接口
													data: formData, //请求参数（这里将参数都保存在formData对象中）
													processData: false, //因为data值是FormData对象，不需要对数据做处理。
													contentType: false, //默认为true,不设置Content-type请求头
													success: function(data) {
														console.log(data)
														layer.closeAll();
														if(data.code == 1) { //请求成功
															if(data.result.receive_time == 0) {
																location.href = "enrollSuccess.php?user_id=" + data.result.user_id + "&git=" + data.result.tips;
															} else {
																layer.msg(data.result.tips);
															}
														} else {
															layer.msg(data.msg);
															setTimeout(function() {
																location.href = "enrollFail.php";
															}, 1000)
														}

													}
												});
											}
										})

									} else {
										layer.msg(data.msg);
									}

								}
							});
						})

					}
				} else {
					layer.msg(data.msg);
				}

			}
		});
	})
})