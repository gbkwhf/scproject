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
	<div class="inviHead"><!--<img src="images/inviHead.png"/>--></div>
	<div class="inviNav">
		<div class="wrapNav">
		<div class="inviTitle">推荐共享返利条件</div>
		<div class="conNav">
			<div>推荐人要求：必须在平台会员区产生消费并达到返利 资格的会员，方可推荐其他用户加入平台并注册。</div> 
			<div>被推荐人必须在平台商城消费，且订单完成后（无退货）；如推荐人未在平台会员区消费，其被推荐人在会员区消费订单达到基数要求，推荐人将不能享受推荐共享返利。</div>
			<div>推荐共享标准：每日平台（被推荐人平台交易的订单）总利润10%；</div>
			<div>利润共享天数：永久（备注：只要被推荐人进行线上下单交易就可以享受推荐返利）。达到以上条件，平台会根据会员个人所推荐的总人数给予会员个人一定比例的推荐返利，推荐共享的金额每天根据财务数据统计，由系统自动返到会员的平台账户“可用余额”里。</div>
			<div style="font-size: 12px;"> 备注：被推荐人消费达到自购消费基数，同样可享受会员自购利润共享返利。</div>
		</div>
		</div>
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

    requesturl = commonsUrl+'shopping_mall/invitation.php';
    getWxConfig(requesturl);

    $.ajax({
        type:'post',
        url:commonsUrl + 'api/gxsc/user/profile' + versioninfos,
        data:{'ss':getCookie('openid')},
        success:function(data){

            var user_id = data.result.user_id;//用户id
            console.log(user_id);
            wx.ready(function () {
//        var tzurl = encodeURIComponent(commonsUrl+'/shopping_mall/userRegister.php?user_id='+user_id);
                wx.onMenuShareAppMessage({
                    title: '双创共享商城', // 分享标题
                    desc: '双创共享商城免费注册送大礼', // 分享描述
                    link: commonsUrl+'api/gxsc/invite/others/register?user_id='+user_id, // 分享链接
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
                    link: commonsUrl+'api/gxsc/invite/others/register?user_id='+user_id, // 分享链接
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
	   $(".inviNav").height(winH-inviHeadH);

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
