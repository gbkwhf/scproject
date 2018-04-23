<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>收货地址</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"><meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/receiving_address.css">
</head>

<body>

	<div class="wrapper">
		<div class="address-box">
			<!--<div class="address-details">
				<div class="address-info">
					<div class="user-info">
						<h5>陈一，15709283666</h5>
						<p class="address">陕西西安雁塔区丈八东路陕西西安雁塔区丈八东路陕西西安雁塔区丈八东路</p>
					</div>
					<div class="sign">					
						<span>使用</span>
						<img src="images/pitch-on.png" width="30"/>				
					</div>
				</div>
				<div class="operation-btn">
					<p class="default-address"><img src="images/unselected.png" width="16"/><span>设为默认</span></p>
					<p class="deletes"><img src="images/delete.png" width="16"/><span>删除</span></p>-->
					<!--<p class="edits"><img src="images/edits.png" width="14"/><span>编辑</span></p>-->
				<!--</div>
			</div>-->
		</div>
		<a href="javascript:void(0);" class="add-address">新增地址</a>
	</div>
	
	<!--删除-->
	<div id="delete-pop">
		<p>确定要删除该地址吗？</p>
		<div class="btn-box-pop">
			<a href="javascript:void(0);" class="cancel" onclick="cancelPop()">取消</a>
			<a href="javascript:void(0);" class="confirm">确认</a>
		</div>
	</div>
	<!--新增-->
	<div id="add-pop">
		<h4>添加新收货地址</h4>
		<div class="adinfo">
			<input type="text" class="user-name" placeholder="名字" />
			<input type="text" class="user-phone" placeholder="电话" maxlength="11" onkeyup="value=value.replace(/[^0-9.]/g,'') " />
		</div>
		<!--选择地区-->
        <section class="express-area">
            <a class="expressArea" href="javascript:void(0)">
                <dl>
                    <dt>选择地区：</dt>
                    <dd>省 > 市 > 区/县</dd>
                </dl>
            </a>
        </section>
        <textarea rows="3" class="detailed" placeholder="详细地址（可填写街道、小区、大厦）"></textarea>
        <a href="javascript:;" class="save-use">保存并使用</a>
        <span class="layui-layer-setwin">
        	<a class="layui-layer-ico layui-layer-close layui-layer-close2" href="javascript:;" onclick="closeAddpop()"></a>
        </span>
	</div>
	<!--选择地区弹层-->
    <section id="areaLayer" class="express-area-box">
        <header>
            <h3>选择地区</h3>
            <a id="closeArea" class="close" href="javascript:void(0)" title="关闭"></a>
        </header>
        <article id="areaBox">
            <ul id="areaList" class="area-list"></ul>
        </article>
    </section>
    <!--遮罩层-->
    <div id="areaMask" class="mask"></div>
    <div class="masks"></div>
</body>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script src="js/jquery.area.js"></script>
<script>
	
	//拉取所有地址信息
	$.ajax({
		type:'post',
		url:commonsUrl + 'api/gxsc/get/delivery/goods/address' + versioninfos,
		data:{'ss':getCookie('openid')},
		success:function(data){
			if(data.code==1){
				console.log(data);
				html='';
				for(var i=0;i<data.result.length;i++){
					html+='<div class="address-details" address_id="'+data.result[i].address_id+'">'+
						'	<div class="address-info">'+
						'		<div class="user-info">'+
						'			<h5>'+data.result[i].name+'，'+data.result[i].mobile+'</h5>'+
						'			<p class="address">'+data.result[i].address+'</p>'+
						'		</div>'+
						'		<div class="sign">';
						if($_GET['address_id'] == data.result[i].address_id){
							html+='	<span style="display:none">使用</span>'+
							'		<img src="images/pitch-on.png" width="30" style="display:block"/>';
						}else{
							html+='	<span>使用</span>'+
							'		<img src="images/pitch-on.png" width="30"/>';
						}
						if(data.result[i].is_default==0){			
							html+='		</div>'+
							'	</div>'+
							'	<div class="operation-btn">'+
							'		<p class="default-address"><img src="images/unselected.png" width="16"/><span>设为默认</span></p>';
						}else if(data.result[i].is_default==1){				
							html+='		</div>'+
							'	</div>'+
							'	<div class="operation-btn">'+
							'		<p class="default-address"><img src="images/selected.png" width="16"/><span>已设为默认</span></p>';
						}
						html+='		<p class="deletes"><img src="images/delete.png" width="16"/><span>删除</span></p>'+
						'	</div>'+
						'</div>';
				}
				$('.address-box').html(html);
				
				//使用该地址
				$('.address-info').click(function(){		
					$('.sign span').show();
					$('.sign img').hide();
					$(this).find('.sign span').hide();
					$(this).find('.sign img').show();
					var address_id = $(this).parents('.address-details').attr('address_id');
					location.href='formOrder.php?address_id='+address_id;
				})
				
				//设为默认地址
				$('.default-address').click(function(){
					$('.default-address img').attr('src','images/unselected.png');
					$('.default-address span').html('设为默认');
					$('.default-address span').css('color','#464646');
					$(this).children('img').attr('src','images/selected.png');
					$(this).children('span').html('已设为默认');
					$(this).children('span').css('color','#88c5be');
					var address_id = $(this).parents('.address-details').attr('address_id');
					$.ajax({
						type:"post",
						url:commonsUrl+"api/gxsc/handle/delivery/goods/default/address"+versioninfos,
						data:{'address_id':address_id,'ss':getCookie('openid')},
						success:function(data){
							if(data.code==1){
								console.log(data);
							}
						}
					});
				})
			
				//删除
				$('.deletes').click(function(){
					var address_id = $(this).parents('.address-details').attr('address_id');
					$('#delete-pop').attr('address_id',address_id);
					layer.open({
						type:1,
						title:false,
						closeBtn:false,
						shadeClose:false,
						content:$('#delete-pop')
					});
					
				})
			}else{
                layer.msg(data.msg);
            }
		}
	})
	
	
	//新增
	$('.add-address').click(function(){
		
		$('.masks').fadeIn();
		$('#add-pop').fadeIn();

	})
	
	$('.save-use').click(function(){
		var userName = $('.user-name').val();
		var mobile = $('.user-phone').val();
		var areaAddress = $('.expressArea dl dd').text();
		var detailsAddress = $('.detailed').val();
		if(testTel(mobile)){
			if(userName == ''){
				layer.msg('请输入收货人姓名');
			}else if(areaAddress=='省 > 市 > 区/县'){          	
            	layer.msg('请选择省市区');
			}else if(detailsAddress==''){
				layer.msg('请填写详细地址');
			}else{
				areaAddress = areaAddress.replace(/>/g,'');
				var allAddress = areaAddress +'  '+ detailsAddress;
				//新增收货地址
				$.ajax({
					type:"post",
					url:commonsUrl + "api/gxsc/add/delivery/goods/address" +versioninfos,
					data:{'address':allAddress,'mobile':mobile,'name':userName,'ss':getCookie('openid')},
					success:function(data){
						if(data.code==1){
							console.log(data);
							closeAddpop();
							location.href='formOrder.php?address_id='+data.result.address_id;
						}else{
			                layer.msg(data.msg);
			            }
					}
				});
			}
            
        }else{
            layer.msg('请输入正确的手机号');
        }
	})
	
	//关闭新增弹框
	function closeAddpop(){
		$('#add-pop').fadeOut();
		$('.masks').fadeOut();
	}
	
	/*打开省市区选项*/
	$(".expressArea").click(function() {
		$("#areaMask").fadeIn();
		$("#areaLayer").fadeIn();
	});
	/*关闭省市区选项*/
	$("#areaMask, #closeArea").click(function() {
		clockArea();
	});
	
	
	//删除地址
	$('.confirm').click(function(){
		var addressId = $(this).parents('#delete-pop').attr('address_id');
		$.ajax({
			type:"post",
			url:commonsUrl+ "api/gxsc/delete/delivery/goods/address" +versioninfos,
			data:{'ss':getCookie('openid'),'address_id':addressId},
			success:function(data){
				if(data.code==1){
					console.log(data);
					cancelPop();
					location.reload();
				}
			}
		});
	})
	
</script>
<style type="text/css">
	.layui-layer.layui-anim.layui-layer-page{
		border-radius: 5px;
	} 
</style>
</html>