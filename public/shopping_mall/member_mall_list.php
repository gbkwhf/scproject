<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>商城列表</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/mui.min.css">
    <link rel="stylesheet" type="text/css" href="css/member_mall_list.css"/>
</head>
<style type="text/css">
    ::-webkit-scrollbar {
        display: none;
    }

    .mui-scrollbar {
        display: none !important;
    }
</style>

<body>
<div class="clarity"></div>
<!----------商品分类-->
<!--        <div class="moreBox">-->
<!--			<div class="moreWid">-->
<!--				<!--<div class="classify addStyleMi">食品干货</div>-->
<!--				<div class="classify">精选海鲜</div>-->
<!--				<div class="classify">茶饮酒水</div>-->
<!--				<div class="classify">休闲食品</div>-->
<!--				<div class="classify">营养滋补</div>-->
<!--				<div class="classify">精选海鲜</div>-->
<!--				<div class="classify">茶饮酒水</div>-->
<!--				<div class="classify">食品干货</div>-->
<!--			</div>
<!--		</div>
  <!--      <img src="images/bot.png" id="show" alt="">-->
<div style="position: fixed;top: 0;left: 0;background: #fff;width: 100%;z-index: 99;">
    <div class="wrapper wrapper02" id="wrapper02">
        <div class="scroller">
            <ul class="clearfix">
                <script type="text/html" id="navList">
                    <li>
                        <a href="javascript:void(0)" goods_second_id="{{goods_second_id}}">{{goods_second_name}}</a>
                    </li>
                </script>
            </ul>
        </div>
        <img src="images/bot.png" id="show" alt="">
    </div>
</div>
<!-------商品列表------>
<div id="refreshContainer" class="mui-scroll-wrapper">
    <div class="mui-scroll">
        <div style="margin-top: 38px;" class="shopBox">
            <!--<div class="shopListBox">
        <div class="shopImg"><img src="images/shop1.png" /></div>
        <div class="shopListNames">可玉可求 飘香翡翠手镯女款玉手镯 玉器玉石收手</div>
        <div class="shops">
            <span class="shopPrice">￥22000</span>
            <span class="fan">返利0.2</span>
        </div>
    </div>
    <div class="shopListBox">
        <div class="shopImg"><img src="images/shop1.png" /></div>
        <div class="shopListNames">可玉可求 飘香翡翠手镯女款玉手镯 玉器玉石收手</div>
        <div class="shops">
            <span class="shopPrice">￥22000</span>
            <span class="fan">返利0.2</span>
        </div>
    </div>-->
        </div>
    </div>
</div>
<!--购物车-->
<!--<div class="shopping-cart" onclick="location.href='newShop_cart.php'">
    <img src="images/shopping-cart.png" />
    <span></span>
</div>-->

<div class="float">
    <ul>
        <img src="images/top.png" id="hide"/>
    </ul>
</div>
<div class="popBox" style="display: none !important">
    <div class="pops">
        <p style="text-align: center;margin-top: 14px;margin-bottom: 14px;font-size: 16px;">温馨提示</p>
        <div style="text-align: center;color: #999999;padding: 0 0 15px 0;font-size: 14px;">积分兑换区商品不参与返利哦<br/> 并且不接受退换货操作~<br/>积分兑换的商品需单独进行购买
        </div>
        <div class="confirm" id="confirmId">确定</div>
        <div class="cancels" id="cancelsId">取消</div>
    </div>
</div>
</body>

</html>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script src="js/mui.min.js"></script>
<script type="text/javascript" src="js/navbar/flexible.js"></script>
<script type="text/javascript" src="js/navbar/iscroll.js"></script>
<script type="text/javascript" src="js/navbar/navbarscroll.js"></script>
<script type="text/javascript">
    $(function () {

        var winW = $(window).width();
        var winH = $(window).height();
        var first_id = $_GET['goods_first_id'];
        var goods_first_name = $_GET['goods_first_name'];
        var titleName = decodeURIComponent(goods_first_name);
        $("title").html(titleName);
        if (titleName == '积分兑换') {
            alertornot()
        } else {
            $(".popBox").hide();
        }
//		if(goods_first_name=='粮油副食'){
//			$(".popBox").css('display','');
//		}

        $(".clarity").css("height", $(this).height() + "px")
        $("#show").click(function () {
            $(".float").show()
            $(".clarity").show()
        })
        $("#hide").click(function () {
            $(".float").hide()
            $(".clarity").hide()
        })

        console.log(first_id);
        $.ajax({ //获取商品二级分类
            type: "post",
            dataType: 'json',
            url: commonsUrl + "api/gxsc/get/goods/second/list" + versioninfos,
            data: {
                "goods_first_id": first_id,
                "ss": getCookie('openid') //请求参数  openid
            },
            success: function (data) {
                console.log(data)
                if (data.code == 1) { //请求成功
                    var con = data.result;
                    if (con.length != 0) {
                        console.log(con);
                        if (con.length <= 4) {
                            $("#show").hide()
                        }
                        // var html = '';
                        // $.each(con, function(k, v) {
                        // 	var goods_second_id = con[k].goods_second_id; //二级分类id
                        // 	console.log(goods_second_id);
                        // 	var second_name = con[k].goods_second_name; //分类名称
                        // 	html += "<div class='classify' goods_second_id=" + goods_second_id + ">" + second_name + "</div>"
                        // });
                        // $('.moreWid').append(html); //动态显示分类名称
                        // $(".classify").eq(0).addClass("addStyleMi").siblings().removeClass("addStyleMi");
                        // shopList(1, $(".addStyleMi").attr("goods_second_id"));
                        // $('.shopBox').html('');

                        // 修改（开始）------------------------------------
                        for (let val of con) {
                            let temp = $("#navList").html()
                            temp = temp.replace("{{goods_second_name}}", val.goods_second_name).replace("{{goods_second_id}}", val.goods_second_id)
                            $(".clearfix").append(temp)
                            $(".float ul").append("<li id=" + val.goods_second_id + '>' + val.goods_second_name + "</li>")
                        }
                        $('.wrapper').navbarscroll();
                        // （结束）------------------------------------
                        // var html = '';
                        // $.each(con, function(k, v) {
                        // 	var goods_second_id = con[k].goods_second_id; //二级分类id
                        // 	console.log(con[k]);
                        // 	var second_name = con[k].goods_second_name; //分类名称
                        // 	html += "<div class='classify' goods_second_id=" + goods_second_id + ">" + second_name + "</div>"
                        //    $(".float ul").append("<li id=" + con[k].goods_second_id + '>' +  con[k].goods_second_name + "</li>")
                        // });
                        // $('.moreWid').append(html); //动态显示分类名称
                        // $(".classify").eq(0).addClass("addStyleMi").siblings().removeClass("addStyleMi");
                        // 修改（开始）------------------------------------
                        shopList(1, $(".cur a").attr("goods_second_id"));
                        // $('.shopBox').html('');
                        $('.clearfix li').click(function () {
                            setTimeout(function () {
                                mui('#refreshContainer').pullRefresh().refresh(true);
                            }, 300);

                            $('.popBox').hide();
                            $(this).addClass('cur').siblings().removeClass('cur');
                            $('.shopBox').html('');
                            shopList(1, $(".cur a").attr("goods_second_id"));
                            pageNum = 1;
                        })
                    } else {
                        layer.msg(data.msg);
                    }

                }


                // 修改（开始）------------------------------------
                $(".float li").click(function () {
                    let index = $(this).index()
                    let nums = -parseInt(index) / 0.02
                    console.log(nums)
                    $(".scroller").attr("style", "width: " + con.length * 98 + "px;transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1);transition-duration: 0ms;transform: translate(" + nums + "px, 0px) translateZ(0px);")
                    $('.shopBox').html('');
                    shopList(1, $(this).attr("id"));
                    $(".float").hide()
                    $(".clarity").hide()
                    $(".clearfix li").each(function () {
                        if (index == $(this).index()) {
                            $(this).addClass("cur").siblings().removeClass("cur")
                        }
                    })
                })
                // （结束）------------------------------------
            }
        });
    });
    pageNum = 1;
    // shopList(pageNum, 1);
    //专区切换
    function shopList(pageNum, t) {
        var goods_second_id = $(".addStyleMi").attr("goods_second_id");
        layer.ready(function () {
            layer.load(2);
        })
        console.log(goods_second_id);
        var winH = $(window).height();
        var html = '';
        var con = "";
        $.ajax({ //获取商品列表
            type: "post",
            dataType: 'json',
            url: commonsUrl + "api/gxsc/get/goods_class/list" + versioninfos,
            data: {
                "goods_second_id": t, //
                "page": pageNum,
                "ss": getCookie('openid') //请求参数  openid
            },
            success: function (data) {
                layer.closeAll();
                console.log(data)
                if (data.code == 1) { //请求成功

                    var content = data.result;
                    con = content;
                    if (con.length == 0 && pageNum == 1) {

                        layer.closeAll();
                        $('.shopBox').html('<p>暂无商品,敬请期待!</p>');
                        $('.shopBox p').css({
                            'line-height': winH - 51 + 'px',
                            'text-align': 'center',
                            'color': '#c6bfbf'
                        });
                        mui('#refreshContainer').pullRefresh().endPullupToRefresh(true);
                    } else {
                        console.log(con)
                        $.each(con, function (k, v) {
                            var goods_id = con[k].goods_id; //商品id
                            console.log(goods_id);
                            var goods_name = con[k].goods_name; //商品名称
                            var images = con[k].image; //商品图片
                            var price1 = con[k].price; //商品价格
                            var ext_id = con[k].ext_id; //商品扩展表id
                            var market_price = con[k].market_price; //原价
                            var goods_gift = con[k].goods_gift; //商品类别
//							if(goods_gift == 1) {
//								$(".popBox").hide();
//							} else {
//								alertornot()
//								$(".popBox").show();
//								$(".confirm,.cancels").click(function() {
//									$(".popBox").hide();
//								});
//							};
                            //							console.log("这是普通商品"+goods_gift);
                            var show = isShowImg(con[k].goods_gift);
                            var shows = isShow(con[k].goods_gift);
                            var use_score = con[k].use_score; //可用积分
                            if (price1 == null || price1 == undefined) {
                                price1 = '0';
                            }
                            if (market_price == null || market_price == undefined) {
                                market_price = '0';
                            }
                            html += '<div class="shopListBox" goods_id=' + goods_id + ' ext_id=' + ext_id + ' >' +
                                '<div class="shopImg"><img src=' + images + ' /></div>' +
                                '<div class="shopListNames">' + goods_name + '</div>' +
                                '<div class="shops"><span class="shopPrice">￥' + price1 + '</span> <span class="useScore" style="display:' + show + '">需' + use_score + '积分</span>' +
                                '<span class="fan" style="display:' + shows + ';"><span style="text-decoration: line-through;">￥' + market_price + '</span></span></div>' +
                                '</div>';
                        });
                        //console.log(html);
                        $('.shopBox').append(html); //动态商品列表
                        if (data.result.length > 0) {
                            mui('#refreshContainer').pullRefresh().refresh(true);
                            //							mui('#refreshContainer').pullRefresh().endPullupToRefresh(false);
                        } else {
                            layer.msg("已经到底了");
                            mui('#refreshContainer').pullRefresh().endPullupToRefresh(true);
                        }
                    }

                } else {
                    layer.msg(data.msg);
                }

            }
        });
    };

    function isShowImg(goods_gift) {
        if (goods_gift == 1) {
            return 'none';
        } else {
            return 'block'
        }
    };

    function isShow(goods_gift) {
        if (goods_gift == 1) {
            return 'block';
        } else {
            return 'none'
        }
    };
    var once_per_session = 1

    function get_cookie(Name) {
        var search = Name + "="
        var returnvalue = "";
        if (document.cookie.length > 0) {
            offset = document.cookie.indexOf(search)
            if (offset != -1) { // if cookie exists
                offset += search.length
                end = document.cookie.indexOf(";", offset);
                if (end == -1)
                    end = document.cookie.length;
                returnvalue = unescape(document.cookie.substring(offset, end))
            }
        }
        return returnvalue;
    }

    function alertornot() {
        if (get_cookie('alerted') == '') {
            loadalert()
            document.cookie = "alerted=yes"
        }
    }

    function loadalert() {
        $(".popBox").show();
        $(".confirm,.cancels").click(function () {
            $(".popBox").hide();
        });
    }

    mui.init({
        pullRefresh: {
            container: '#refreshContainer', //待刷新区域标识，querySelector能定位的css选择器均可，比如：id、.class等
            auto: true, // 可选,默认false.自动上拉加载一次
            height: 50,
            up: {
                callback: function () {
                    pageNum++;
                    shopList(pageNum, $(".cur a").attr("goods_second_id"));
                    mui('#refreshContainer').pullRefresh().endPullupToRefresh();
                    //						mui('#refreshContainer').pullRefresh().refresh(true);
                } //必选，刷新函数，根据具体业务来编写，比如通过ajax从服务器获取新数据；
            }

        }
    });
    //获取购物车中的商品数量
    var tarr = [];
    var numberShop = 0;
    //获取购物车的数量
    $.ajax({
        type: "post",
        url: commonsUrl + '/api/gxsc/v2/get/goods/car/info' + versioninfos,
        data: {
            "ss": getCookie('openid')
        },
        success: function (data) {
            if (data.code == 1) { //请求成功
                console.log(data);
                var arr = data.result.info;
                $.each(arr, function (k, v) {
                    $.each(v.others, function (key, value) {
                        //console.log(value.number);
                        numberShop += parseInt(value.number)
                    })
                })
                console.log(numberShop + 'iiiiiii')
                $('.shopping-cart span').html(numberShop);

            } else {
                layer.msg(data.msg);
            }
        }
    });

    //	function goDetail(goods_id) {
    //		//		console.log(goods_id);
    //		location.href = "newShop_details.php?goods_id=" + goods_id;
    //	}
    mui('body').on('tap', '.shopListBox', function () {
        var ext_id = $(this).attr('ext_id');
        console.log(ext_id);
        mui.openWindow({
            url: "newShop_details.php?ext_id=" + ext_id
        })
    })
</script>