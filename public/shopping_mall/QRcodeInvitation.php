<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="css/common.css">
		<title>二维码邀请</title>
	</head>
	<style>
		.QRcodeInvitation {
			/*overflow: hidden;*/
			background: url("./images/invitation_back.png") no-repeat;
			background-size: 100% 100%;
			position: relative;
		}
		
		.QRcodeInvitation> canvas {
			width: 100%;
			height: 100%;
			border-radius: 10px;
		}
		
		.QRcodeInvitation> img {
			display: none;
		}
		
		#qrcodeCanvas {
			display: none;
		}
		
		#canvasImg {
			width: 100%;
			height: 100%;
			background: #fff;
			overflow: hidden;
			display: block!important;
			position: absolute;
			top: 0;
			left: 0;
		}
		
		#user_imgs {
			width: 68px;
			height: 69px;
			border-radius: 50%;
			margin-bottom: 20px;
		}
	</style>

	<body>
		<div class="QRcodeInvitation">
			<canvas id="canvas">请升级浏览器</canvas>
			<img id="canvasImg" src="" alt="">
			<img id="user_imgs" src="" alt="">
			<img id="invite_friends" src="./images/invite_friends.png" alt="">
			<img id="code" src="./images/code.png" alt="">
			<div id="qrcodeCanvas"></div>
			<img id="base" src="" alt="">
			<img id="invitation_back" src="./images/inviteBj.png" alt="">
		</div>
	</body>

</html>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script src="./js/utf.js"></script>
<script src="./js/jquery.qrcode.js"></script>

<script>
	var user_id = $_GET['user_id'];
	var thumbnail_image_url = $_GET['thumbnail_image_url'];
	console.log(thumbnail_image_url);
	var cav = $('canvas')[0],
		ctx = cav.getContext('2d')
	var img = new Image()
		// 允许跨域，后端需要在响应头添加'Access-Control-Allow-Origin: *'(cors解决跨域)
	img.crossOrigin = '*'
	img.onload = function() {
		var r=38;
        var x=10;
        var y=25;
        var d=10;
          ctx.save();
        var d =2 * r;
        var cx = x + r;
        var cy = y + r;
        ctx.arc(cx, cy, r, 0, 2 * Math.PI);
        ctx.clip();
        ctx.drawImage(img, x, y, d, d);
        ctx.restore();
			try {
				// 读取图片base64 [不传会默认读取为image/png格式，不可压缩] [如果把png图片读取为image/jpeg，那么透明区域会变黑，可大幅度压缩质量] [如果读取为image/webp格式，只有谷歌支持读取和显示这种格式]
				var base64 = cav.toDataURL()
				console.log(base64)
					// canvas 转换为 img
				$('#user_imgs')[0].src = base64
			} catch(err) {
				// 报错'Failed to execute 'toDataURL' on 'HTMLCanvasElement': Tainted canvases may not be exported.'表示图片需同源
				console.warn('图片需同源')
			}
		}
		// src最后赋值，为了兼容ie8
	img.src = thumbnail_image_url;
	if(thumbnail_image_url != '') {
		img.src = thumbnail_image_url
	} else {
		img.src = './images/head-portrait.png'
	}
	$("#qrcodeCanvas").qrcode({
		render: "canvas", //设置渲染方式，有table和canvas，使用canvas方式渲染性能相对来说比较好
		text: commonsUrl + "api/gxsc/invite/others/register?user_id=" + user_id, //扫描二维码后显示的内容,可以直接填一个网址，扫描二维码后自动跳向该链接
		width: "200", //二维码的宽度
		height: "200", //二维码的高度
		background: "#ffffff", //二维码的后景色
		foreground: "#000000", //二维码的前景色
		src: './images/scLogo.png' //二维码中间的图片
	})
	window.onload = function() {
		var loding = layer.load(0, {
			offset: '35%'
		});
		var imgData = document.getElementById("qrcodeCanvas").children[0].toDataURL("image/jpeg");
		//      if (thumbnail_image_url != "") {
		//      $('#user_imgs').attr('src', thumbnail_image_url);
		//  	};
		$('#base').attr('src', imgData);
		document.documentElement.style.fontSize = (document.documentElement.clientWidth / 750) * 100 + 'px';
		var height = $(this).height();
		$('.QRcodeInvitation').css('height', height + 'px');
		var user_imgs = document.getElementById('user_imgs');
		var invite_friends = document.getElementById('invite_friends');
		var codeImg = document.getElementById('base');
		var invitation_back = document.getElementById('invitation_back')

		function draw() {
			var canvas = document.getElementById('canvas');
			var ctx = canvas.getContext('2d');
			//  计算画布的宽度
			width = canvas.offsetWidth,
				//  计算画布的高度
				height = canvas.offsetHeight,
				//  设置宽高
				canvas.width = width;
			canvas.height = height;
			var cvwidth = parseInt(width * .8);
			var cvheight = parseInt(height * .6);
			var left = parseInt(width * .1);
			var top = parseInt(height * .15);
			ctx.drawImage(invitation_back, 0, 0, width, height);
			//绘制一个圆角矩形
			strokeRoundRect(ctx, left, top, cvwidth, cvheight, 10);

			//绘制并填充一个圆角矩形
			fillRoundRect(ctx, left, top, cvwidth, cvheight, 10, '#fff');
			//			ctx.drawImage(user_img, left + 15, top - 30, cvwidth - 15, cvheight * .4);
			ctx.drawImage(user_imgs, left + 95, top - 60, 300, 150);
			ctx.drawImage(invite_friends, left + 15, top + 65, cvwidth - 15, cvheight * .4);
			ctx.drawImage(codeImg, (cvwidth / 2) - (cvheight * .20 / 2), height * .42, height * .23, height * .23);
			ctx.font = '13px Arial';
			ctx.fillStyle = '#000';
			ctx.fillText('长按识别二维码', (width / 2) - 50, height * .7);
			layer.close(loding);
		}

		function fillRoundRect(cxt, x, y, width, height, radius, /*optional*/ fillColor) {
			//圆的直径必然要小于矩形的宽高
			if(2 * radius > width || 2 * radius > height) {
				return false;
			}

			cxt.save();
			cxt.translate(x, y);
			//绘制圆角矩形的各个边
			drawRoundRectPath(cxt, width, height, radius);
			cxt.fillStyle = fillColor || "#fff"; //若是给定了值就用给定的值否则给予默认值
			cxt.fill();
			cxt.restore();
		}

		function strokeRoundRect(cxt, x, y, width, height, radius, /*optional*/ lineWidth, /*optional*/ strokeColor) {
			//圆的直径必然要小于矩形的宽高
			if(2 * radius > width || 2 * radius > height) {
				return false;
			}

			cxt.save();
			cxt.translate(x, y);
			//绘制圆角矩形的各个边
			drawRoundRectPath(cxt, width, height, radius);
			cxt.lineWidth = lineWidth || 2; //若是给定了值就用给定的值否则给予默认值2
			cxt.strokeStyle = strokeColor || "#fff";
			cxt.stroke();
			cxt.restore();
		}

		function drawRoundRectPath(cxt, width, height, radius) {
			cxt.beginPath(0);
			//从右下角顺时针绘制，弧度从0到1/2PI
			cxt.arc(width - radius, height - radius, radius, 0, Math.PI / 2);

			//矩形下边线
			cxt.lineTo(radius, height);

			//左下角圆弧，弧度从1/2PI到PI
			cxt.arc(radius, height - radius, radius, Math.PI / 2, Math.PI);

			//矩形左边线
			cxt.lineTo(0, radius);

			//左上角圆弧，弧度从PI到3/2PI
			cxt.arc(radius, radius, radius, Math.PI, Math.PI * 3 / 2);

			//上边线
			cxt.lineTo(width - radius, 0);

			//右上角圆弧
			cxt.arc(width - radius, radius, radius, Math.PI * 3 / 2, Math.PI * 2);

			//右边线
			cxt.lineTo(width, height - radius);
			cxt.closePath();
		}

		setTimeout(function() {
			draw();
			var type = 'jpg';
			download(type);
		}, 500)

		//图片下载操作,指定图片类型
		function download(type) {
			var canvas = document.getElementById('canvas');
			//设置保存图片的类型
			var imgdata = canvas.toDataURL(type);
			//将mime-type改为image/octet-stream,强制让浏览器下载
			var fixtype = function(type) {
				type = type.toLocaleLowerCase().replace(/jpg/i, 'jpeg');
				var r = type.match(/png|jpeg|bmp|gif/)[0];
				return 'image/' + r;
			}
			imgdata = imgdata.replace(fixtype(type), 'image/octet-stream')
			$('#canvasImg').attr('src', imgdata).css('display', 'black')

		}
	}
</script>