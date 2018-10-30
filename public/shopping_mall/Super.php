<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/Super.css"/>
    <meta charset="UTF-8">
    <title>商超</title>
</head>
<style>
    .tab-head {
        width: 92%;
        list-style-type: none;
        display: flex;
        flex-wrap: nowrap;
        justify-content: space-between;
        padding: 0;
        overflow: auto;
        height: 70px;
    }

    #show{
        position: absolute;
        right: 0;
        top: 0;
        width: 45px;
        height: 40px;
    }

    .tab-head-item{
        height: 30px;
        flex: 1 0 auto;
        margin: 4px 5px 0;
        line-height: 30px;
        padding: 0 10px;
        text-align: center;
    }

    .select{
        color: #fff;
        background: #e63737;
        border-radius: 30px;
    }
</style>
<body>
<div class="clarity"></div>
<div id="body">
    <div style="position: fixed;top: 0;left: 0;background: #fff;width: 100%;z-index: 99;">
        <div style="height: 40px;overflow: hidden;position: relative;border-bottom: 1px solid #ccc;">
            <ul class="tab-head">
                <script type="text/html" id="navList">
                    <li class="tab-head-item" id="{{goods_second_id}}">{{goods_second_name}}</li>
                </script>
            </ul>
            <img src="images/bot.png" id="show" alt="">
        </div>
    </div>
    <div class="msg">
        <ul class="tem">
            <script type="text/html" id="commentList">
                <li onclick="location.href='reclassify.php?store_id={{store_id}}&name={{name}}'"
                    style="border-top:6px solid #f3f2f2">
                    <div>
                        <img src="{{logo}}"/>
                        <p>
                            <span style="font-size:.4rem">{{store_name}}</span>
                            <span>进入专场</span>
                        </p>
                    </div>
                </li>
            </script>
        </ul>
        <div class="float">
            <ul>
                <img src="images/top.png" id="hide"/>
            </ul>
        </div>
    </div>
    <p style="line-height: 616px; text-align: center; color: rgb(198, 191, 191);display:none" class="show">
        暂无商品,敬请期待!</p>
</body>

</html>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/config.js"></script>
<script src="js/jquery.min.js"></script>
<!--<script src="js/super.js"></script>-->
<script>
    $(function () {
        alert('123')
        $.ajax({
            type: "post",
            dataType: "json",
            url: commonsUrl + 'api/gxsc/get/second/info/list' + versioninfos,
            data: {
                'ss': getCookie('openid'),
                'store_first_id': store_first_id
            },
            success:function (res) {
                alert(res)
            }
        })
    })
</script>