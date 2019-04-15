<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>我的邀请</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/invitation.css">
</head>
<style>
    .conNav div{
        line-height: 22px;
        letter-spacing:1px
    }
</style>
<body>
<div class="inviHead"><!--<img src="images/inviHead.png"/>--></div>
<div class="inviNav">
    <div class="wrapNav">
        <div class="inviTitle">推荐共享返利条件</div>
        <div class="conNav">
            <div>邀请人要求：必须是平台上成功注册的会员。</div>
            <div>邀请收益标准：享受邀请收益必须是被邀请人在平台上下单，不限制下单金额，一级邀请享受订单利润的3%，二级邀请享受订单利润的1%。</div>
            <div>邀请收益天数：永久（只要被邀请人成功下单，就可获得邀请收益）。</div>
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
    let invite_role
    requesturl = commonsUrl + 'shopping_mall/invitation.php';
    getWxConfig(requesturl);

    $.ajax({
        type: 'post',
        url: commonsUrl + 'api/gxsc/user/profile' + versioninfos,
        data: {'ss': getCookie('openid')},
        success: function (data) {
            invite_role = data.result.invite_role
            var user_id = data.result.user_id;//用户id
            var thumbnail_image_url=data.result.thumbnail_image_url;
            console.log(data);

            $(".inviBtn1").click(function () {
                window.location.href="QRcodeInvitation.php?user_id=" + user_id+"&thumbnail_image_url="+thumbnail_image_url;
            })
            wx.ready(function () {
                //        var tzurl = encodeURIComponent(commonsUrl+'/shopping_mall/userRegister.php?user_id='+user_id);
                wx.onMenuShareAppMessage({
                    title: '双创共享商城', // 分享标题
                    desc: '双创共享商城免费注册送大礼', // 分享描述
                    link: commonsUrl + 'api/gxsc/invite/others/register?user_id=' + user_id, // 分享链接
                    imgUrl: commonsUrl + '/shopping_mall/images/logoimg.png', // 分享图标
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
                    link: commonsUrl + 'api/gxsc/invite/others/register?user_id=' + user_id, // 分享链接
                    imgUrl: commonsUrl + '/shopping_mall/images/logoimg.png', // 分享图标
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

    var winH = $(window).height();
    var inviHeadH = $(".inviHead").height();
    $(".inviNav").height(winH - inviHeadH);

</script>


<style type="text/css">
    .layui-layer.layui-anim.layui-layer-page {
        top: 20px;
    }
</style>
</html>
