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
		<div class="address-details">
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
				<p class="deletes"><img src="images/delete.png" width="16"/><span>删除</span></p>
				<!--<p class="edits"><img src="images/edits.png" width="14"/><span>编辑</span></p>-->
			</div>
		</div>
		<div class="address-details">
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
				<p class="deletes"><img src="images/delete.png" width="16"/><span>删除</span></p>
				<!--<p class="edits"><img src="images/edits.png" width="14"/><span>编辑</span></p>-->
			</div>
		</div>
		<div class="address-details">
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
				<p class="deletes"><img src="images/delete.png" width="16"/><span>删除</span></p>
				<!--<p class="edits"><img src="images/edits.png" width="14"/><span>编辑</span></p>-->
			</div>
		</div>
		<a href="javascript:void(0);" class="add-address">新增地址</a>
	</div>
	
	<!--编辑-->
	<div id="edit-pop">
		<h4>编辑地址</h4>
		<div class="adinfo">
			<input type="text" class="user-name" placeholder="名字" value="陈一" />
			<input type="text" class="user-phone" placeholder="电话" />
		</div>
		<!--选择地区-->
        <!--<section class="express-area">
            <a class="expressArea" href="javascript:void(0)">
                <dl>
                    <dt>选择地区：</dt>
                    <dd>省 > 市 > 区/县</dd>
                </dl>
            </a>
        </section>
        <textarea rows="" cols="" class="detailed" placeholder="详细地址（可填写街道、小区、大厦）"></textarea>
        <a href="javascript:;" class="save-use">保存并使用</a>-->
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
	
	$('.address-details').eq(0).find('.sign span').css('display','none');
	$('.address-details').eq(0).find('.sign img').css('display','block');
	$('.address-details').eq(0).find('.default-address img').attr('src','images/selected.png');
	$('.address-details').eq(0).find('.default-address span').html('已设为默认');
	$('.address-details').eq(0).find('.default-address span').css('color','#88c5be');
	
	//使用该地址
	$('.sign span').click(function(){		
		$('.sign span').show();
		$('.sign img').hide();
		$(this).hide();
		$(this).siblings('img').show();
	})
	
	//设为默认地址
	$('.default-address').click(function(){
		$('.default-address img').attr('src','images/unselected.png');
		$('.default-address span').html('设为默认');
		$('.default-address span').css('color','#464646');
		$(this).children('img').attr('src','images/selected.png');
		$(this).children('span').html('已设为默认');
		$(this).children('span').css('color','#88c5be');
	})

	//删除
	$('.deletes').click(function(){
		
		layer.open({
			type:1,
			title:false,
			closeBtn:false,
			shadeClose:false,
			content:$('#delete-pop')
		})
		
	})
	//编辑
	$('.edits').click(function(){
		
		layer.open({
			type:1,
			title:false,
			closeBtn:true,
			shadeClose:false,
			content:$('#edit-pop')
		})
		
	})
	
	//新增
	$('.add-address').click(function(){
		
		$('.masks').fadeIn();
		$('#add-pop').fadeIn()
		
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
	
</script>
<style type="text/css">
	.layui-layer.layui-anim.layui-layer-page{
		border-radius: 5px;
	} 
</style>
</html>