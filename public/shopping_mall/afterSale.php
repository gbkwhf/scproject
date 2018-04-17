<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/afterSale.css">
    <meta charset="UTF-8">
    <title>售后交流</title>
</head>

<body>
    <div id="body">
        <div class="entire">
            <p style="height: 50px;line-height:50px">关于商品咨询 (
                <span id="num"></span> 条)</p>
            <div id="after"></div>
        </div>
        <script type='text/html' id="commentList">
            <div style="margin:0 0 40px 15px;overflow: hidden">
                <div class="user">
                    <img src="images/name.png" alt="">
                    <span>{{name}}}</span>
                </div>
                <div class="reply">
                    <img src="images/ask.png" alt="">
                    <span>{{problem}}</span>
                </div>
                <div class="ask">
                    <img src="images/reply.png" alt="">
                    <span>{{commentContent}}</span>
                </div>
                <p style="float: right;margin-top: 15px;color: #ccc;">{{createTime}}</p>
            </div>
    </script>
    </div>
    <div class="form">
        <div>
            <input type="text" placeholder="请输入您想提出的问题" class="aa" />
            <p id="submit">提问</p>
        </div>
    </div>
    </div>
</body>

</html>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/config.js"></script>
<script type="text/javascript">
    let page = 1
    $(function () {

        // 获取售后信息
        $.ajax({
            type: "POST",
            dataType: "json",
            url: commonsUrl + 'api/gxsc/get/after/consult/list' + versioninfos,
            data: {
                page: page,
                ss: getCookie('openid')
            },
            success: (res) => {
                console.log(res)
                $("#num").text(res.result.num)

                try {
                    if (res.code == "1") {
                        let data = res.result.data
                        for (let val of data) {
                            var content = val.merchant_reply;

                            let temp = $("#commentList").html()
                            temp = temp.replace("{{createTime}}", val.created_at)
                                .replace("{{name}}", val.name)
                                .replace("{{commentContent}}", content)
                                .replace("{{problem}}", val.user_problem);
                            $("#after").append(temp)
                            if (!content) {
                                content = '';
                                $(".ask").hide()
                            }
                        }

                    }
                } catch (e) {
                    console.log(e)
                }



            },
            error: (res) => {
                console.log(res)
            }
        })


        // 添加提问
        $("#submit").click(function () {
            let msg = $(".aa").val()
            if (msg == "") {
                alert("填写内容")
            } else {
                $.ajax({
                    type: 'POST',
                    dataType: "json",
                    url: commonsUrl + '/api/gxsc/publish/after/consult' + versioninfos,
                    data: {
                        problem: msg,
                        ss: getCookie('openid')
                    },
                    success: (res) => {
                        console.log(res)
                        $(".aa").val("")
                    },
                    error: (res) => {
                        console.log(res)
                    }
                })
            }
        })




    })


    $(this).scroll(function () {
        var viewHeight = $(this).height();//可见高度  
        var contentHeight = $("#body").get(0).scrollHeight;//内容高度  
        var scrollHeight = $(this).scrollTop();//滚动高度  
        if ((contentHeight - viewHeight) / scrollHeight <= 1) {
            page++
            $.ajax({
                type: "POST",
                dataType: "json",
                url: commonsUrl + 'api/gxsc/get/after/consult/list' + versioninfos,
                data: {
                    page: 1,
                    ss: getCookie('openid')
                },
                success: (res) => {
                    console.log(res)
                    $("#num").text(res.result.num)

                    try {
                        if (res.code == "1") {
                            let data = res.result.data
                            for (let val of data) {
                                var content = val.merchant_reply;
                                let temp = $("#commentList").html()
                                temp = temp.replace("{{createTime}}", val.created_at)
                                    .replace("{{name}}", val.name)
                                    .replace("{{commentContent}}", content)
                                    .replace("{{problem}}", val.user_problem);
                                $("#after").append(temp)
                                if (!content) {
                                    content = '';
                                    $(".ask").hide()
                                }
                            }

                        }
                    } catch (e) {
                        console.log(e)
                    }



                },
                error: (res) => {
                    console.log(res)
                }
            })
        }
    })

</script>