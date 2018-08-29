<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>订单列表</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" type="text/css" href="css/myOrderList.css"/>
</head>
<body>
<div id="body">
    <!--顶部-->
    <div class="orderHeader">
        <div class="ordeBox">
            <div class="quanbu commClick getStyle" id="0">全部</div>
        </div>
        <div class="ordeBox">
            <div class="fukuan commClick" id="1">待付款</div>
        </div>
        <div class="ordeBox">
            <div class="fukuan1 commClick" id="2">待收货</div>
        </div>
        <div class="ordeBox">
            <div class="fukuan2 commClick" id="3">待评价</div>
        </div>
    </div>
    <!--下面的内容-->
    <div class="boxContent">

        <div class="orderInfo">

            <!--标题部分-->
            <div class="orGe"></div>
            <!--下面的产品-->
            <div class="shopInfoBox">
                <script type="text/html" id="commentList">
                    <div class="shopBoxCon" id="{{id}}" data-goods_id="{{goods_id}}">
                        <!--图片-->
                        <div class="imgInfo"><img src="{{image}}" alt=""/></div>
                        <!--标题信息-->
                        <div class="titleInfoBox">
                            <!--商品标题-->
                            <div class="orderTitle">{{goods_name}}</div>
                            <!--标签-->
                            <div class="orderBiaoQian1">
                                <span class="biao1">{{spec_name}}</span>
                            </div>
                            <!--价格 以及数量-->
                            <div class="orderPriceBox">
                                <span class="orderPrice">￥{{price}}元</span>
                                <span class="orderNum">+{{num}}</span>
                            </div>
                        </div>
                    </div>
                </script>
            </div>
        </div>
    </div>
    <div class="popBox" style="display: none !important">
        <div class="pops">
            <p style="text-align: center;margin-top: 14px;margin-bottom: 14px;font-size: 16px;">特别提醒</p>
            <div class="content" style="width: 70%;margin: auto;text-align: center;color: #999999;padding: 0 0 15px 0;">点击确认收货后将不能退换货操作请您三思哦~</div>
            <div class="confirm" id="confirmId">确定</div>
            <div class="cancels" id="cancelsId">取消</div>
        </div>
    </div>
    <p style="line-height: 616px; text-align: center; color: rgb(198, 191, 191);display:none" class="show">你还没有相关的订单</p>

</div>
</body>
</html>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script type="text/javascript">
    let page = 1
    var orderId = $_GET['orderId'];
    let URL = "api/gxsc/v2/get/order/info/all/list"
    let id = 0
    console.log(orderId + '******');
    if (orderId == 0) { //全部
        $('.quanbu').addClass("getStyle").parent().siblings().find(".commClick").removeClass("getStyle")
        id = 0
        packaging(URL, page)
    } else if (orderId == 1) { //待付款
        $(".orGe").show()
        $('.fukuan').addClass("getStyle").parent().siblings().find(".commClick").removeClass("getStyle")
        id = 1
        URL = "api/gxsc/v2/get/order/info/obligation/list"
        packaging(URL, page)

    } else if (orderId == 2) {//待收货
        $('.fukuan1').addClass("getStyle").parent().siblings().find(".commClick").removeClass("getStyle")
        id = 2
        URL = "api/gxsc/v2/get/order/info/list"
        packaging(URL, page)
    } else {//待评价
        $('.fukuan2').addClass("getStyle").parent().siblings().find(".commClick").removeClass("getStyle")
        id = 3
        evaluate(page)
    }

    $(".commClick").click(function () {
        page = 1
        $(".shopBoxCon").remove();
        $(".shopNumSum").remove();
        $(".wuliuConter").remove();
        $(".orderHea").remove()

        $(this).addClass("getStyle").parent().siblings().find(".commClick").removeClass("getStyle")
        id = $(this).attr("id")
        switch (id) {
            case "0": //全部
                $(".orGe").hide()
                orderId, id = 0
                URL = "api/gxsc/v2/get/order/info/all/list"
                packaging(URL, page)
                break;
            case "1": //带付款
                $(".orGe").show()
                orderId, id = 1
                URL = "api/gxsc/v2/get/order/info/obligation/list"
                packaging(URL, page)

                break;
            case "2": //带收货
                $(".orGe").hide()
                orderId, id = 2
                URL = "api/gxsc/v2/get/order/info/list"
                packaging(URL, page)
                break;
            case "3": //带评价
                $(".orGe").hide()
                orderId, id = 3
                evaluate(page)
                break;
        }
    });


    function packaging(URL, page) {
        $.ajax({
            type: "post",
            url: commonsUrl + URL + versioninfos,
            data: {
                page: page,
                ss: getCookie('openid')
            },
            success: (res) => {
                console.log(res)
                try {
                    if (res.code == "1") {
                        let data = res.result
                        if (page == 1) {
                            if (data.length == 0) {
                                $(".show").show()
                            } else {
                                $(".show").hide()
                            }
                        } else {
                            if (data.length == 0) {
                                layer.msg("没有更多了！")
                            }
                        }

                        if (id == 0) {
                            for (let val of data) {
                                let order_status
                                if (val.order_status == 0) {
                                    order_status = "待发货"
                                } else if (val.order_status == 1) {
                                    order_status = "待收货"
                                } else if (val.order_status == 2) {
                                    order_status = "待评价"
                                } else {
                                    order_status = "交易完成"
                                }
                                $(".shopInfoBox").append('<div class="orderHea"><div class="orderStore">' + val.supplier_name + '</div><div class="orderStatus">' + order_status + '</div></div>')
                                    let temp = $("#commentList").html()
                                temp = temp.replace("{{image}}", val.image)
                                    .replace("{{goods_name}}", val.goods_name)
                                    .replace("{{spec_name}}", val.spec_name == null ? "" : val.spec_name)
                                    .replace("{{price}}", val.goods_price)
                                    .replace("{{num}}", val.num)
                                    .replace("{{goods_id}}", val.ext_id)
                                $(".shopInfoBox").append(temp)
                                if (val.order_status == 2) {
                                    let aa = '<div class="shopNumSum"><span class="sumShop">共' + val.num + '件商品</span><span class="hejiCon">合计' + val.goods_price + '元</span></div><div class="wuliuConter"><div class="wuliBox"><span class="checkcont evaluate" style="float:right;margin-right:10px;" data-id="' + val.buy_goods_id + '" data-img="' + val.image + '">立即评价</span></div>'
                                    $(".shopInfoBox").append(aa)
                                }
                            }
                        } else {
                            for (let val = 0; val < data.length; val++) {

                                if (val >= 0) {
                                    if (id == 2) {
                                        $(".shopInfoBox").append('<div class="orderHea"><div class="orderStore">' + data[val].supplier_name + '</div><div class="orderStatus">待收货</div></div>')
                                    }
                                }



                                let num
                                for (let vals = 0; vals < data[val].goods_list.length; vals++) {
                                    num = data[val].goods_list.length
                                    let temp = $("#commentList").html()
                                    temp = temp.replace("{{image}}", data[val].goods_list[vals].image)
                                        .replace("{{goods_name}}", data[val].goods_list[vals].goods_name)
                                        .replace("{{spec_name}}", data[val].goods_list[vals].spec_name)
                                        .replace("{{price}}", data[val].goods_list[vals].price)
                                        .replace("{{num}}", data[val].goods_list[vals].num)
                                        .replace("{{id}}", data[val].base_order_id ? data[val].base_order_id + "&id=0" : data[val].sub_id + "&id=1")

                                    $(".shopInfoBox").append(temp)
                                }
                                if (val >= 0) {
                                    if (id == 1) {
                                        let aa = '<div class="shopNumSum" ><span class="sumShop">共' + num + '件商品</span><span class="hejiCon">合计' + data[val].require_amount + '元(含运费' + data[val].shipping_price + '元)</span></div><div class="wuliuConter" style="border-bottom: 7px solid #f3f2f2;"><span class="checkcont cancellation" style="float:right;margin-right:20px;" onclick="cancellation('+ data[val].base_order_id + ')" id="' + data[val].base_order_id + '">取消订单</span><span class="checkcont pay" style="float:right;margin-right:20px;" id="' + data[val].base_order_id + '">立即支付</span></div></div>'
                                        $(".shopInfoBox").append(aa)
                                    } else if (id == 2) {
                                        let pay = parseFloat(data[val].price) + parseFloat(data[val].shipping_price)
                                        if(data[val].back_state == 0 && data[val].can_back == 0 && data[val].goods_list[0].goods_gift == 1){
                                            let aa = '<div class="shopNumSum"><span class="sumShop">共' + num + '件商品</span><span class="hejiCon">合计' + pay + '元(含运费' + data[val].shipping_price + '元)</span></div><div class="wuliuConter"><span class="checkcont phy" id=' + data[val].goods_list[0].sub_id + '>查看物流</span><span class="checkcont aff" id="' + data[val].goods_list[0].sub_id + '"  onclick="affirm(' + data[val].goods_list[0].sub_id + ')">确定收货</span><span class="checkcont" id="' + data[val].goods_list[0].sub_id + '" onclick="apply(' + data[val].base_id + ')">申请退货</span></div></div>'
                                            $(".shopInfoBox").append(aa)
                                        }else{
                                            let aa = '<div class="shopNumSum"><span class="sumShop">共' + num + '件商品</span><span class="hejiCon">合计' + pay + '元(含运费' + data[val].shipping_price + '元)</span></div><div class="wuliuConter"><span class="checkcont phy" id=' + data[val].goods_list[0].sub_id + '>查看物流</span><span class="checkcont aff" id="' + data[val].goods_list[0].sub_id + '"  onclick="affirm(' + data[val].goods_list[0].sub_id + ')">确定收货</span></div></div>'
                                            $(".shopInfoBox").append(aa)
                                        }
                                    }
                                }
                            }
                        }
                    }
                } catch (e) {
                    console.log(e)
                }

                tabSwitchover()
            }
        })
    }

    function tabSwitchover() {
        setTimeout(() => {

            $(".shopBoxCon").click(function () {
                let thisId = $(this).attr("id")
                if (id == 0) {
                    location.href = 'newShop_details.php?ext_id=' + $(this).attr("data-goods_id")
                } else if (id == 1) {
                    location.href = 'orderDetail.php?base_order_id=' + thisId
                } else if (id == 2) {
                    location.href = 'orderDetail.php?sub_order_id=' + thisId
                } else if (id == 3) {
                    location.href = 'newShop_details.php?ext_id=' + thisId
                }
            })



            $(".phy").click(function () {
                location.href = 'logistical.php?sub_order_id=' + $(this).attr("id")
            })

            // $(".aff").click(function () {
            //     $.ajax({
            //         type: "post",
            //         dataType: "json",
            //         url: commonsUrl + 'api/gxsc/v2/ack/receive/goods' + versioninfos,
            //         data: {
            //             sub_order_id: $(this).attr("id"),
            //             ss: getCookie('openid')
            //         },
            //         success: (res) => {
            //             console.log(res)
            //             if (res.code == 1) {
            //                 layer.msg("确认成功")
            //                 $('.fukuan2').addClass("getStyle").parent().siblings().find(".commClick").removeClass("getStyle")
            //                 id = 3
            //                 evaluate(page)
            //             } else {
            //                 layer.msg(res.msg)
            //             }
            //         }
            //     })
            // })


            $(".pay").click(function () {
                let base_order_id = $(this).attr("id")
                $.ajax({
                    type: "post",
                    url: commonsUrl + "api/gxsc/pay/goods" + versioninfos,
                    data: {
                        'base_order_id': base_order_id,
                        'filling_type': 3,
                        'open_id': getCookie('openid'),
                        'ss': getCookie('openid')
                    },
                    success: function (data) {
                        if (data.code == 1) {
                            console.log(data);
                            data.result.timeStamp = data.result.timeStamp.toString();
                            retStr = data.result;
                            callpay();

                            //调用微信JS api 支付
                            function jsApiCall() {
                                WeixinJSBridge.invoke(
                                    'getBrandWCPayRequest',
                                    retStr,
                                    function (res) {
                                        if (res.err_msg == "get_brand_wcpay_request:ok") {
                                            //支付成功
                                            location.href = 'myOrderList.php';
                                        } else {
                                            //												             alert(res.err_msg);
                                        }
                                    }
                                );
                            }

                            function callpay() {
                                if (typeof WeixinJSBridge == "undefined") {
                                    if (document.addEventListener) {
                                        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                                    } else if (document.attachEvent) {
                                        document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                                        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                                    }
                                } else {
                                    jsApiCall();
                                }
                            }
                        } else {
                            layer.msg(data.msg);
                        }
                    }
                })
            })


            $(".evaluate").click(function () {
                location.href = 'newEvaluate.php?buy_goods_id=' + $(this).attr("data-id") + "&img=" + $(this).attr("data-img")
            })

        }, 500);
    }


    function evaluate(page) {
        $.ajax({
            type: "post",
            url: commonsUrl + "api/gxsc/v2/get/order/info/comment/list" + versioninfos,
            data: {
                page: page,
                ss: getCookie('openid')
            },
            success: (res) => {
                console.log(res)
                try {
                    if (res.code == "1") {
                        let data = res.result
                        if (data.length == 0 && page == 1) {
                            $(".show").show()
                        } else {
                            $(".show").hide()
                        }
                        for (let val = 0; val < data.length; val++) {
                            $(".shopInfoBox").append(' <div class="orderHea"><div class="orderStore">' + data[val].supplier_name + '</div><div class="orderStatus">交易成功</div></div>')
                            let temp = $("#commentList").html()
                            temp = temp.replace("{{image}}", data[val].image)
                                .replace("{{goods_name}}", data[val].goods_name)
                                .replace("{{spec_name}}", data[val].spec_name)
                                .replace("{{price}}", data[val].goods_price)
                                .replace("{{num}}", data[val].num)
                                .replace("{{id}}", data[val].ext_id)
                            $(".shopInfoBox").append(temp)
                            let aa = '<div class="shopNumSum"><span class="sumShop">共' + data[val].num + '件商品</span><span class="hejiCon">合计' + data[val].goods_price * data[val].num + '元</span></div><div class="wuliuConter"><div class="wuliBox"><span class="checkcont evaluate" style="float:right;margin-right:10px;" data-id="' + data[val].buy_goods_id + '" data-img="' + data[val].image + '">立即评价</span></div>'
                            $(".shopInfoBox").append(aa)
                        }

                    }
                } catch (e) {
                    console.log(e)
                }
                tabSwitchover()
            }
        })
    }


    $(this).scroll(function () {
        tabSwitchover()
        var viewHeight = $(this).height();//可见高度  
        var contentHeight = $("#body").get(0).scrollHeight;//内容高度  
        var scrollHeight = $(this).scrollTop();//滚动高度  
        if ((contentHeight - viewHeight) / scrollHeight <= 1) {
            page++
            if (orderId == 3 || id == 3) {
                evaluate(page)
            } else {
                packaging(URL, page)
            }

        }
    })

    function affirm(options) {
        $(".popBox").show()
        $('.content').text('点击确认收货后将不能退换货操作请您三思哦~')
        $(".confirm").click(function () {
            $(".popBox").hide()
            $.ajax({
                type: "post",
                dataType: "json",
                url: commonsUrl + 'api/gxsc/v2/ack/receive/goods' + versioninfos,
                data: {
                    sub_order_id: options,
                    ss: getCookie('openid')
                },
                success: (res) => {
                    console.log(res)
                    if (res.code == 1) {
                        layer.msg("确认成功")
                        $('.fukuan2').addClass("getStyle").parent().siblings().find(".commClick").removeClass("getStyle")
                        id = 3
                        evaluate(page)
                    } else {
                        layer.msg(res.msg)
                    }
                }
            })
        })

        $(".cancels").click(function () {
            $(".popBox").hide()
        })

    }

    function cancellation(id) {
        page=1
        $(".popBox").show()
        $('.content').text('确定取消订单吗？')
        $(".confirm").click(function () {
            $(".popBox").hide()
            $.ajax({
                type: "post",
                dataType: "json",
                url: commonsUrl + 'api/gxsc/v2/ack/receive/goods' + versioninfos,
                data: {
                    sub_order_id: options,
                    ss: getCookie('openid')
                },
                success: (res) => {
                    console.log(res)
                    if (res.code == 1) {
                        layer.msg("确认成功")
                        $('.fukuan2').addClass("getStyle").parent().siblings().find(".commClick").removeClass("getStyle")
                        id = 3
                        evaluate(page)
                    } else {
                        layer.msg(res.msg)
                    }
                }
            })
        })

        $(".cancels").click(function () {
            $(".popBox").hide()
        })
    }

    function apply(options) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: commonsUrl + 'api/gxsc/user/return/goods' + versioninfos,
            data: {
                base_order_id: options,
                ss: getCookie('openid')
            },
            success: (res) => {
                console.log(res)
                try {
                    if (res.code == 1){
                        layer.msg("申请成功")
                    } else {
                        layer.msg(res.msg)
                    }
                }catch (e) {
                    console.log(e)
                }
                // if (res.code == 1) {
                //     layer.msg("确认成功")
                //     $('.fukuan2').addClass("getStyle").parent().siblings().find(".commClick").removeClass("getStyle")
                //     id = 3
                //     evaluate(page)
                // } else {
                //     layer.msg(res.msg)
                // }
            }
        })
    }
</script>