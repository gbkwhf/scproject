<!DOCTYPE html>
<html>
	<meta charset="utf-8">
    <title>申请加盟</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"><meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/franchiseeApplication.css">
	<body>
		<div class="essentialInformationBox">
			<div class="informationTitle">基本信息</div>
			<div class="inforName">
				<div class="lastName"><span class="symol">*</span>姓名：</div>
				<div class="inName"><input type="text" placeholder="请输入姓名" class="inputForName"/></div>
			</div>
			<div class="inforMobil">
				<div class="phoneNum"><span class="symol">*</span>电话：</div>
				<div class="inNum"><input type="text" placeholder="请输入联系电话" class="inputForNum"/></div>
			</div>
			<div class="inforCompany">
				<div class="companys">公司名称：</div>
				<div class="firmName"><input type="text" placeholder="请输入公司名称" class="inputCompany"/></div>
			</div>
		</div>
		<div class="on_product">
			<div class="onProductTitle">产品信息</div>
			<div class="productBox">
				<div class="productName"><span class="symol">*</span>产品名称：</div>
				<div class="pros"><input type="text" placeholder="请输入产品名称" class="inputProductName"/></div>
			</div>
			<div class="proAttributesBox">
				<div class="attributesName">产品属性：</div>
				<div class="proAttributes"><input type="text" placeholder="请输入相关产品相关词汇" class="inputAttributes"/></div>
			</div>
			<div class="productDrawingBox">
				<div class="productDescription">产品配图<span class="remark">（1M以内，jpg，png格式）</span></div>
				<div class="productImg">
					<div id="uploadBox">
						<!--<div class="divImg" id="uploadImg">
						
					     </div>-->
					</div>
					
					<div class="uploadDIv">
						<span>+</span><input type="file" multiple id="inputs" accept="image/*"/> 
					</div>
				</div>
			</div>
		</div>
		<div class="confirmApply">确定申请</div>
	</body>
</html>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
	$("#inputs").change(function () {
		var fil = this.files;
			for (var i = 0; i < fil.length; i++) {
			 reads(fil[i]);
			}
		});
	});
	function reads(fil){
		var reader = new FileReader();
		reader.readAsDataURL(fil);
		reader.onload = function(){
		   document.getElementById("uploadBox").innerHTML += "<div class='divImg' id='uploadImg'><img src='"+reader.result+"'></div>";
		};
 	}
</script>


