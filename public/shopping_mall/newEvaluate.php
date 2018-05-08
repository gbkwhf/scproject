<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/newEvaluate.css">
    <meta charset="UTF-8">
    <title>立即评价</title>
</head>

<body>
<div class="form">
    <div>
        <div class="header">
            <div class="header_first">
                <img src="" alt="">
            </div>
            <div class="header_last">
                <p>商品评分</p>
                <!--<div>
                    <img src="images/solid.png" alt="">
                    <img src="images/solid.png" alt="">
                    <img src="images/sky.png" alt="">
                    <img src="images/sky.png" alt="">
                    <img src="images/sky.png" alt="">
                </div>-->
            </div>
        </div>
        <div class="input">
            <textarea placeholder="有宝贝心得吗？分享给想买的他们吧" name="msg"></textarea>
        </div>
        <div class="camera">
        	<p id="addImg"></p>
            <div class="upflie">
                <form id="uploadForm" enctype='multipart/form-data'>
                    <img src="images/camera.png" alt="">
                    <input type="file" name="uploadFile">
                </form>
                <p>晒上传实拍图，帮忙想买的他们</p>
            </div>
        </div>
    </div>
    <!--<p></p>
    <div class="physical">
        <div>
            <img src="images/physical.png" alt="">
            <span>物流服务评价</span>
        </div>
        <p>满意请给五星哦</p>
    </div>
    <p></p>
    <div class="service">
        <div>
            <p>物流发货速度</p>
            <div>
                <img src="images/sky.png" alt="">
                <img src="images/sky.png" alt="">
                <img src="images/sky.png" alt="">
                <img src="images/sky.png" alt="">
                <img src="images/sky.png" alt="">
            </div>
        </div>
        <div>
            <p>配货员服务态度</p>
            <div>
                <img src="images/sky.png" alt="">
                <img src="images/sky.png" alt="">
                <img src="images/sky.png" alt="">
                <img src="images/sky.png" alt="">
                <img src="images/sky.png" alt="">
            </div>
        </div>
    </div>-->

    <button>确定</button>
</div>
</body>

</html>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script>
    let flieimg=new FormData(); 
    $(".header_first img").attr("src",$_GET["img"])
    $("input[type=file]").change(function (e) {
        let file=getObjectURL(this.files[0])
        flieimg.append("image",this.files[0])
        $("#addImg").append("<img src=''/>")
        $("#addImg img:last").attr("src",file)
        $(".upflie").hide()
    })

    //建立一個可存取到該file的url
    function getObjectURL(file) {
        var url = null ;
        if (window.createObjectURL!=undefined) { // basic
            url = window.createObjectURL(file) ;
        } else if (window.URL!=undefined) { // mozilla(firefox)
            url = window.URL.createObjectURL(file) ;
        } else if (window.webkitURL!=undefined) { // webkit or chrome
            url = window.webkitURL.createObjectURL(file) ;
        }
        return url ;
    }

        $("button").click(function () {
            if($("textarea[name='msg']").val()==""){
                layer.msg("评论为空")
                return false
            }else if($("textarea[name='msg']").val().length<=5){
                layer.msg("给点面子，在写的点吧！")
                return false
            }else {
                flieimg.append("ss",getCookie('openid'))
                flieimg.append("buy_goods_id",$_GET["buy_goods_id"])
                flieimg.append("contents",$("textarea[name='msg']").val())
                $.ajax({
                    url: commonsUrl + "/api/gxsc/publish/goods/comment" + versioninfos,
                    type: "post",
                    dataType: 'JSON',    
                    cache: false,    
                    processData: false,    
                    contentType: false,
                    async:false,
                    data: flieimg,
                    success: function(res) {
                        console.log(res)
                        if(res.code==1){
                            layer.msg("评价成功")
                            location.href='myOrderList.php?orderId=3'
                        }else{
                            layer.msg(res.msg)
                        }
                    }
                });
            }
        })

    
</script>