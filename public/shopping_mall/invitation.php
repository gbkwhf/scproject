<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>我的邀请</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"><meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/invitation.css">
</head>

<body>
	<div class="inviNav">
		
		<div class="inviBtn1">邀请好友</div>
		<div class="inviBtn2" onclick="location.href='invitationList.php'">我邀请的伙伴</div>
		<div class="popBox" style="display: none;">
			<div class="tiImg">
				<div class="tiTitle">点击右上角按钮进行邀请</div>
			</div>
			
		</div>
	</div>
</body>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
    let invite_role	
    requesturl = commonsUrl+'shopping_mall/invitation.php';
    getWxConfig(requesturl);

    $.ajax({
        type:'post',
        url:commonsUrl + 'api/gxsc/user/profile' + versioninfos,
        data:{'ss':getCookie('openid')},
        success:function(data){
            invite_role	=data.result.invite_role	
            var user_id = data.result.user_id;//用户id
            console.log(data);
            if(invite_role==0){
                location.href='myinvite.php'
            }else{
                   
            }
            wx.ready(function () {
    //        var tzurl = encodeURIComponent(commonsUrl+'/shopping_mall/userRegister.php?user_id='+user_id);
                    wx.onMenuShareAppMessage({
                        title: '双创共享商城', // 分享标题
                        desc: '双创共享商城免费注册送大礼', // 分享描述
                        link: commonsUrl + 'userRegister.php?user_id='+user_id, // 分享链接
                        imgUrl: commonsUrl+'/shopping_mall/images/logoimg.png', // 分享图标
                        type: '', // 分享类型,music、video或link，不填默认为link
                        dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                        success: function () {
                            // 用户确认分享后执行的回调函数
                            layer.msg("分享成功！");
                        },
                        cancel: function () {
                            // 用户取消分享后执行的回调函数
                        }
                    });

                    wx.onMenuShareTimeline({
                        title: '双创共享商城', // 分享标题
                        desc: '双创共享商城免费注册送大礼', // 分享描述
                        link: commonsUrl + 'userRegister.php?user_id='+user_id, // 分享链接
                        imgUrl: commonsUrl+'/shopping_mall/images/logoimg.png', // 分享图标
                        type: '', // 分享类型,music、video或link，不填默认为link
                        dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                        success: function () {
                            // 用户确认分享后执行的回调函数
                            layer.msg("分享成功！");
                        },
                        cancel: function () {
                            // 用户取消分享后执行的回调函数
                        }
                    });
                });
        }
    })


    //link: "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx97bfadf3a81d8206&redirect_uri="+tzurl+"&response_type=code&scope=snsapi_base&state=123#wechat_redirect"

	   var winH=$(window).height();
	   var inviHeadH=$(".inviHead").height();
	   $(".inviNav").height(winH);
       $(".inviBtn2,.inviBtn1").css("margin-top",winH-110+"px")

	$(".inviBtn1").click(function(){
        $('.popBox').show()
        $('.popBox').click(function() {
            $('.popBox').hide()
        })
           
	})

	
</script>


<style type="text/css">
	.layui-layer.layui-anim.layui-layer-page{
		top: 20px;
	} 
</style>
</html>
