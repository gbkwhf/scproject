$(function () {
    var page = 1;
    var store_second_id = '';
    if ($_GET["store_first_id"] == 1) {
        $("title").text("商超")
    } else if ($_GET["store_first_id"] == 2) {
        $("title").text("精品馆")
    } else {
        $("title").text("土特产")
    }
    $(".clarity").css("height", $(this).height() + "px")
    $("#show").click(function () {
        $(".float").show()
        $(".clarity").show()
    })
    $("#hide").click(function () {
        $(".float").hide()
        $(".clarity").hide()
    })
    var store_first_id = $_GET['store_first_id'];
    // alert(store_first_id)
    // // 导航分类
    $.ajax({
        type: "post",
        dataType: "json",
        url: commonsUrl + 'api/gxsc/get/second/info/list' + versioninfos,
        data: {
            'ss': getCookie('openid'),
            'store_first_id': store_first_id
        },
        success: function(res) {
            console.log(res)
            try {
                var data = res.result;
                if (data.length <= 4) {
                    $("#show").hide()
                }
                store_second_id = data[0].store_second_id

                $.each(data,function (j,index) {
                    console.log(data[j])
                    var temp = $("#navList").html()
                    temp = temp.replace("{{goods_second_name}}", data[j].store_second_name).replace("{{goods_second_id}}", data[j].store_second_id)
                    $(".tab-head").append(temp)
                    $(".float ul").append("<li id=" + data[j].store_second_id + '>' + data[j].store_second_name + "</li>")
                })
                $(".tab-head li").eq(0).addClass("select").siblings().removeClass("select");
                shop(store_second_id, page);
                $(".tab-head li").click(function () {
                    console.log($(this).attr('id'))
                    store_second_id = $(this).attr('id')
                    var index = $(this).index()
                    $(this).addClass("select").siblings().removeClass("select");
                    page = 1
                    $(".tem li").remove()

                    shop(store_second_id, page)
                })


                $(".float li").click(function () {
                    var store_second_id = $(this).attr("id")
                    var index = $(this).index()
                    $(".tem li").remove()
                    shop(store_second_id, page)
                    $(".float").hide()
                    $(".clarity").hide()
                    $('.tab-head li').each(function () {
                        if (index == $(this).index()) {
                            var moveX = $(this).position().left + $(this).parent().scrollLeft();
                            var pageX = document.documentElement.clientWidth;//设备的宽度
                            var blockWidth = $(this).width();
                            var left = moveX - (pageX / 2) + (blockWidth / 2);
                            $(".tab-head").animate({scrollLeft: left}, 400);
                            $(this).addClass("select").siblings().removeClass("select");
                        }
                    })
                })

            } catch (e) {
                console.log(e);
            }

        },
        error:function (err) {
            console.log(err);
        }
    })


    $(this).scroll(function () {
        var viewHeight = document.documentElement.clientHeight;//可见高度
        var contentHeight = $("#body").get(0).scrollHeight;//内容高度
        var scrollHeight = $(this).scrollTop();//滚动高度
        console.log(viewHeight+'可见高度')
        console.log(contentHeight+'内容高度')
        console.log(scrollHeight+'滚动高度')
        if ((contentHeight - viewHeight) / scrollHeight <= 1) {
            page++
            shop(store_second_id, page, show)
        }
    })





    // 门店
    function shop(id, page, show) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: commonsUrl + 'api/gxsc/get/store/list' + versioninfos,
            data: {
                ss: getCookie('openid'),
                store_second_id: id,
                page: page
            },
            success: function(res) {
                console.log(res)
                var data = res.result
                if (data.length == 0) {
                    if (!show) {
                        $(".show").show()
                    }
                } else {
                    $(".show").hide()
                }
                $.each(data,function (val,index) {
                    var temp = $("#commentList").html()
                    temp = temp.replace("{{logo}}", data[val].logo).replace("{{store_name}}", data[val].store_name).replace("{{store_id}}", data[val].store_id).replace("{{name}}", data[val].store_name)
                    $(".tem").append(temp)
                })
            }
        })
    }

})