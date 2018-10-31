<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/reclassify.css">
    <title>商超店铺</title>
</head>

<body>
<div id="body">
    <div class="clarity"></div>
    <div style="position: fixed;top: 0;left: 0;background: #fff;width: 100%;z-index: 99;border-bottom: 1px solid #ccc;">
        <div class="wrapper wrapper02" id="wrapper02">
            <div class="scroller">
                <ul class="clearfix">
                    <script type="text/html" id="navList">
                        <li>
                            <a href="javascript:void(0)" id="{{goods_second_id}}">{{goods_second_name}}</a>
                        </li>
                    </script>
                </ul>
            </div>
            <img src="images/bot.png" id="show" alt="">
        </div>
    </div>
    <div class="float">
        <ul>
            <img src="images/top.png" id="hide"/>
        </ul>
    </div>
    <div class="commodity" style="margin-top: 40px;">
        <ul>
            <script type="text/html" id="commentList">

                <li onclick="location.href='newShop_details.php?ext_id={{ext_id}}'">
                    <div>
                        <img src="{{image}}" alt="">
                    </div>
                    <p>{{goods_name}}</p>
                    <div>
                        <span>￥{{price}}</span>
                        <span>￥{{market_price}}</span>
                    </div>
                </li>

            </script>
        </ul>
    </div>

    <p style="line-height: 616px; text-align: center; color: rgb(198, 191, 191);display:none" class="show">
        暂无商品,敬请期待!</p>
</div>
</body>

</html>

<script src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/config.js"></script>
<script type="text/javascript" src="js/navbar/flexible.js"></script>
<script type="text/javascript" src="js/navbar/iscroll.js"></script>
<script type="text/javascript" src="js/navbar/navbarscroll.js"></script>
<script src="js/layer/layer.js"></script>
<script>
    $(function () {
        console.log($_GET['store_id'])
        console.log(decodeURI($_GET['name']))
        $("title").text(decodeURI($_GET['name']))
        var page = 1
        var id = $_GET['store_id']
        var auto = true
        var viewHeight = $(this).height();//可见高度

        $(".clarity").css("height", $(this).height() + "px")

        $("#show").click(function () {
            $(".float").show()
            $(".clarity").show()
        })

        $("#hide").click(function () {
            $(".float").hide()
            $(".clarity").hide()
        })
        // 获取导航列表
        $.ajax({
            type: "post",
            dataType: "json",
            url: commonsUrl + 'api/gxsc/get/store/class/list' + versioninfos,
            data: {
                store_id: $_GET['store_id'],
                ss: getCookie('openid')
            },
            success: function (res) {
                console.log(res)
                id = res.result[0].store_class_id
                var daTa = res.result
                if (daTa.length <= 4) {
                    $("#show").hide()
                }
                $.each(daTa, function (val, index) {
                    var temp = $("#navList").html()
                    temp = temp.replace("{{goods_second_name}}", daTa[val].store_class_name).replace("{{goods_second_id}}", daTa[val].store_class_id)
                    $(".clearfix").append(temp)
                    $(".float ul").append("<li id=" + daTa[val].store_class_id + '>' + daTa[val].store_class_name + "</li>")
                })
                $('.wrapper').navbarscroll();
                // 获取商品列表数据
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: commonsUrl + 'api/gxsc/get/store_class/goods/list' + versioninfos,
                    data: {
                        store_class_id: id,
                        page: 1,
                        ss: getCookie('openid')
                    },
                    success: function (res) {
                        console.log(res)
                        var data = res.result
                        if (data.length == 0) {
                            $(".show").show()
                        } else {
                            $(".show").hide()
                        }
                        for (var val of data) {
                            var temp = $("#commentList").html()
                            temp = temp.replace("{{goods_name}}", val.goods_name).replace("{{image}}", val.image).replace("{{price}}", val.price).replace("{{market_price}}", val.market_price).replace("{{ext_id}}", val.ext_id)
                            $(".commodity ul").append(temp)
                        }

                        $(".float li").click(function () {
                            var store_second_id = $(this).attr("id")
                            var index = $(this).index()
                            var nums = -parseInt(index) / 0.02;
                            $(".scroller").attr("style", "width: " + daTa.length * 98 + "px;transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1);transition-duration: 0ms;transform: translate(" + nums + "px, 0px) translateZ(0px);")
                            // $(".tem li").remove()
                            $(".commodity ul li").remove()
                            // shop(store_second_id, page)
                            $(".float").hide()
                            $(".clarity").hide()
                            $(".clearfix li").each(function () {
                                if (index == $(this).index()) {
                                    $(this).addClass("cur").siblings().removeClass("cur")
                                }
                            })
                            $.ajax({
                                type: "post",
                                dataType: "json",
                                url: commonsUrl + 'api/gxsc/get/store_class/goods/list' + versioninfos,
                                data: {
                                    store_class_id: store_second_id,
                                    page: "1",
                                    ss: getCookie('openid')
                                },
                                success: function (res) {
                                    console.log(res)
                                    var data = res.result
                                    if (data.length == 0) {
                                        $(".show").show()
                                    } else {
                                        $(".show").hide()
                                    }
                                    $.each(data, function (val, index) {
                                        var temp = $("#commentList").html()
                                        temp = temp.replace("{{goods_name}}", data[val].goods_name).replace("{{image}}", data[val].image).replace("{{price}}", data[val].price).replace("{{market_price}}", data[val].market_price).replace("{{ext_id}}", data[val].ext_id)
                                        $(".commodity ul").append(temp)
                                    })
                                }
                            })
                        })

                        $(".clearfix a").click(function (e) {
                            page = 1
                            auto = true
                            id = $(this).attr("id")
                            console.log(id)
                            $(".commodity ul li").remove()
                            $.ajax({
                                type: "post",
                                dataType: "json",
                                url: commonsUrl + 'api/gxsc/get/store_class/goods/list' + versioninfos,
                                data: {
                                    store_class_id: id,
                                    page: "1",
                                    ss: getCookie('openid')
                                },
                                success: function (res) {
                                    console.log(res)
                                    var data = res.result
                                    if (data.length == 0) {
                                        $(".show").show()
                                    } else {
                                        $(".show").hide()
                                    }
                                    $.each(data, function (val, index) {
                                        var temp = $("#commentList").html()
                                        temp = temp.replace("{{goods_name}}", data[val].goods_name).replace("{{image}}", data[val].image).replace("{{price}}", data[val].price).replace("{{market_price}}", data[val].market_price).replace("{{ext_id}}", data[val].ext_id)
                                        $(".commodity ul").append(temp)
                                    })
                                }
                            })
                        })
                        $(this).scroll(function () {
                            var contentHeight = $("#body").get(0).scrollHeight;//内容高度
                            var scrollHeight = $(this).scrollTop();//滚动高度
                            if ((contentHeight - viewHeight) / scrollHeight <= 1) {
                                if (auto) {
                                    page++
                                    $.ajax({
                                        type: "post",
                                        dataType: "json",
                                        url: commonsUrl + 'api/gxsc/get/store_class/goods/list' + versioninfos,
                                        data: {
                                            store_class_id: id,
                                            page: page,
                                            ss: getCookie('openid')
                                        },
                                        success: function (res) {
                                            console.log(res)
                                            var data = res.result
                                            if (data.length == 0) {
                                                layer.msg("没有更多了")
                                                auto = false
                                            } else {
                                                $.each(data, function (val, index) {
                                                    var temp = $("#commentList").html()
                                                    temp = temp.replace("{{goods_name}}", data[val].goods_name).replace("{{image}}", data[val].image).replace("{{price}}", data[val].price).replace("{{market_price}}", data[val].market_price).replace("{{ext_id}}", data[val].ext_id)
                                                    $(".commodity ul").append(temp)
                                                })
                                            }
                                        }
                                    })
                                }
                            }
                        })
                    }
                })
            }
        })


    })

</script>