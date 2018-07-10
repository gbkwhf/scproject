<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>余额明细</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
</head>
<style>
    p {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 15px;
    }

    .remainder_list {
        border-bottom: 1px solid #ddd;
    }

    .remainder_list > div {
        width: 92%;
        margin: 15px auto;
    }

    .remainder_list p:nth-of-type(2) {
        color: #ccc;
    }
</style>
<body>
<div class="remainder">

        <script id="test" type="text/html">
            <div class="remainder_list">
            <div>
                <p>
                    <span>-{{amount}}</span>
                    <span style="color: red">{{desc}}</span>
                </p>
                <p>
                    <span>{{type}}</span>
                    <span style="font-size: 13px;">{{created_at}}</span>
                </p>
            </div>
            </div>
        </script>

</div>
<span style="display:block;line-height: 616px; text-align: center; color: rgb(198, 191, 191)" class="show">
    暂无商品,敬请期待!</span>
</body>
</html>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script>
    $(function () {
        $.ajax({
            type: 'post',
            url: commonsUrl + "/api/gxsc/get/bill/list/info" + versioninfos,
            data: {
                "ss": getCookie('openid'),
                "page": 1
            },
            success: res => {
                console.log(res.result)
                try {
                    let data=res.result
                    data.length == 0 ? $(".show").show() : $(".show").hide()
                    for (let val of data) {
                        let temp = $("#test").html()
                        temp = temp.replace("{{amount}}", val.amount)
                                   .replace("{{type}}", val.type == 1 ? "提现" : "积分兑换")
                                   .replace("{{desc}}", val.desc)
                                   .replace("{{created_at}}", val.created_at)
                        $(".remainder").append(temp)
                    }
                } catch (e) {
                    console.log(e)
                }
            }
        })
    })
</script>


