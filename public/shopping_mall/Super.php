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
<script type="text/javascript">
    $(function () {
        alert('123')
    })
        // let page = 1;
        // let store_second_id = '';
        // if ($_GET["store_first_id"] == 1) {
        //     $("title").text("商超")
        // } else if ($_GET["store_first_id"] == 2) {
        //     $("title").text("精品馆")
        // } else {
        //     $("title").text("土特产")
        // }
        // $(".clarity").css("height", $(this).height() + "px")
        // $("#show").click(function () {
        //     $(".float").show()
        //     $(".clarity").show()
        // })
        // $("#hide").click(function () {
        //     $(".float").hide()
        //     $(".clarity").hide()
        // })
        // // 导航分类
    //     $.ajax({
    //         type: "POST",
    //         dataType: "json",
    //         url: commonsUrl + 'api/gxsc/get/second/info/list' + versioninfos,
    //         data: {
    //             ss: getCookie('openid'),
    //             store_first_id: $_GET['store_first_id']
    //         },
    //         success: (res) => {
    //             console.log(res)
    //             alert(res)
    //             try {
    //                 let data = res.result;
    //                 if (data.length <= 4) {
    //                     $("#show").hide()
    //                 }
    //                 store_second_id = data[0].store_second_id
    //
    //                 for (let val of data) {
    //                     let temp = $("#navList").html()
    //                     temp = temp.replace("{{goods_second_name}}", val.store_second_name).replace("{{goods_second_id}}", val.store_second_id)
    //                     $(".tab-head").append(temp)
    //                     $(".float ul").append("<li id=" + val.store_second_id + '>' + val.store_second_name + "</li>")
    //                 }
    //                 $(".tab-head li").eq(0).addClass("select").siblings().removeClass("select");
    //                 shop(store_second_id, page);
    //                 $(".tab-head li").click(function () {
    //                     console.log($(this).attr('id'))
    //                     store_second_id = $(this).attr('id')
    //                     let index = $(this).index()
    //                     $(this).addClass("select").siblings().removeClass("select");
    //                     page = 1
    //                     $(".tem li").remove()
    //
    //                     shop(store_second_id, page)
    //                 })
    //
    //
    //                 $(".float li").click(function () {
    //                     let store_second_id = $(this).attr("id")
    //                     let index = $(this).index()
    //                     $(".tem li").remove()
    //                     shop(store_second_id, page)
    //                     $(".float").hide()
    //                     $(".clarity").hide()
    //                     $('.tab-head li').each(function () {
    //                         if(index == $(this).index()){
    //                             var moveX = $(this).position().left + $(this).parent().scrollLeft();
    //                             var pageX = document.documentElement.clientWidth;//设备的宽度
    //                             var blockWidth = $(this).width();
    //                             var left = moveX - (pageX / 2) + (blockWidth / 2);
    //                             $(".tab-head").animate({scrollLeft: left}, 400);
    //                             $(this).addClass("select").siblings().removeClass("select");
    //                         }
    //                     })
    //                 })
    //             } catch (e) {
    //                 console.log(e)
    //                 layer.msg(res.msg);
    //             }
    //
    //         }
    //     })
    //
    //
    //     $(this).scroll(function () {
    //         var viewHeight = $(this).height();//可见高度
    //         var contentHeight = $("#body").get(0).scrollHeight;//内容高度
    //         var scrollHeight = $(this).scrollTop();//滚动高度
    //         if ((contentHeight - viewHeight) / scrollHeight <= 1) {
    //             page++
    //             shop(store_second_id, page, show)
    //         }
    //     })
    //
    //
    //     // // 门店
    //     function shop(id, page, show) {
    //         $.ajax({
    //             type: "POST",
    //             dataType: "json",
    //             url: commonsUrl + 'api/gxsc/get/store/list' + versioninfos,
    //             data: {
    //                 ss: getCookie('openid'),
    //                 store_second_id: id,
    //                 page: page
    //             },
    //             success: (res) => {
    //                 console.log(res)
    //                 let data = res.result
    //                 if (data.length == 0) {
    //                     if (!show) {
    //                         $(".show").show()
    //                     }
    //                 } else {
    //                     $(".show").hide()
    //                 }
    //                 for (let val of data) {
    //                     let temp = $("#commentList").html()
    //                     temp = temp.replace("{{logo}}", val.logo).replace("{{store_name}}", val.store_name).replace("{{store_id}}", val.store_id).replace("{{name}}", val.store_name)
    //                     $(".tem").append(temp)
    //                 }
    //             }
    //         })
    //     }
    //
    // })
</script>