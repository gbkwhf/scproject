<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>余额提现</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
</head>
<style>
    body {
        background: #f3f2f2;
    }

    header {
        padding-left: 4%;
        height: 50px;
        display: flex;
        align-items: center;
        background: #fff;
    }

    .money {
        width: 92%;
        padding: 30px 4%;
        background: #fff;
        margin-top: 8px;
    }

    .money input {
        border: none;
        font-size: 23px;
    }

    .money > div {
        margin: 20px 0 10px;
        border-bottom: 1px solid #f3f2f2;
        display: flex;
        align-items: center;
    }

    button {
        width: 92%;
        display: block;
        margin: 30px auto;
        height: 40px;
        border: none;
        color: #fff;
        background: #4d6dfc;
        border-radius: 5px;
    }
</style>
<body>
<header>
    提现到微信
</header>
<div class="money">
    <p>提现金额</p>
    <div>
        <span style="font-size: 30px"> ￥</span>
        <input type="text" name="money">
    </div>
    <span>提现最大额度20,000元,可提现金额为 <span style="color: #c94d10;" class="lastMoney"></span></span>
</div>
<button>提现</button>
</body>
</html>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script>
    $(".lastMoney").text(($_GET["balance"] - $_GET["balance"] * 0.08).toFixed(2))
    $("button").click(function () {
        let val = $("input").val()
        if (val == '') {
            layer.msg("请输入金额")
            return
        } else if (val > 20000) {
            layer.msg("提现金额不能大于20000")
            return
        }
        if (val > $(".lastMoney").text()) {
            layer.msg("余额不足")
            return
        } else {
            $.ajax({
                type: 'post',
                url: commonsUrl + "/api/gxsc/withdraw/deposit/balance" + versioninfos,
                data: {
                    "ss": getCookie('openid'),
                    "money": val
                },
                success: res => {
                    console.log(res)
                    try {
                        if (res.code == "1") {
                            layer.msg(res.msg)
                            setTimeout(function () {
                                location.href = 'remainingProgress.php?money=' + val
                            }, 2000)
                        } else if (res.code == 7003) {
                            layer.msg("提现金额必须大于1元")
                        } else {
                            layer.msg(res.msg)
                        }
                    } catch (e) {
                        console.log(e)
                    }
                }
            })
            return
        }
    })
</script>