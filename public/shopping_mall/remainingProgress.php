<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
    <title>余额提现</title>
</head>
<style>
    .jindu img{
        width:  25px;
        height: 170px;
    }
    .jindu{
        display: flex;
        align-items: center;
        width: 92%;
        margin: 30px auto;
    }
    .jindu>div{
        margin-left: 40px;
        color: #b6b6b6;
    }
    .complete{
        width: 92%;
        margin: auto;
        border-top: 1px solid #b6b6b6;
        padding-top: 20px;
    }
    .complete>p{
        display: flex;
        justify-content: space-between;
        line-height: 20px;
        color: #b6b6b6;
    }
    button{
        width: 60%;
        height: 40px;
        margin: 40px auto;
        display: block;
        background: #fff;
        border: 1px solid #b6b6b6;
        border-radius: 5px;
    }
</style>
<body>
<div class="rProgress">
    <div class="jindu">
        <img src="./images/progress.png">
        <div>
            <p>发起提现申请</p>
            <p style="margin-top: 50px">
                <span style="font-size:16px;color: #000;">后台审核中</span><br>
                <span style="font-size: 12px">预计24小时内到账</span>
            </p>
            <p style="margin-top: 50px">
                到账成功
            </p>
        </div>
    </div>
    <div class="complete">
        <p>
            <span>提现金额</span>
            <span class="money"></span>
        </p>
        <p>
            <span>提现至微信</span>
            <span>微信零钱</span>
        </p>
        <button><a href="personal_center.php" style="color: #b6b6b6">完成</a></button>
    </div>
</div>
</body>
</html>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script>
    $(".money").text("￥ " + $_GET["money"])
</script>