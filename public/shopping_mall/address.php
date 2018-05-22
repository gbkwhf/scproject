<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/address.css">
    <meta charset="UTF-8">
    <title>收货地址</title>
</head>
<style>
    .zdy-icon-radio{padding-left: 1.5rem;background:url(images/checkRight.jpg) no-repeat;background-size: 1rem 1rem;;}
    .bjsc-rt{float: right;}
    .zdy-icon-radio.active{background: url(images/checked.png) no-repeat;background-size: 1rem 1rem;color: #e63737;}
</style>
<body>


<div class="xp-content02">
    <script type="text/html" id="commentList">
        <div class="bianji-dizhi">
            <div class="tt09">
                <span>{{name}}</span>
                <span>{{mobile}}</span>
            </div>
            <div class="your-adress02" id="{{skipid}}">
                地址:{{address}}
            </div>
            <div class="bianji-shanchu mui-clearfix">
                <div class="bjsc-lf mui-clearfix">
                    <span class="zdy-icon-radio{{is_default}}" id="{{address_id}}">默认地址</span>
                </div>
                <div class="bjsc-rt">
                    <p id="compile"><img src="images/edito.png" alt=""><span data-id="{{compileid}}">编辑</span></p>
                    <p id="del"><img src="images/deleCon.png" alt=""><span data-id="{{deladdress_id}}">删除</span></p>
                </div>
            </div>
        </div>
    </script>   
</div>   
   
    <div style="height: 40px;"></div>
    <a href="newAddress.php" class="newAdd">新增地址</a>
</body>
</html>
<script src="js/jquery.min.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script src="js/layer/layer.js"></script>
<script>
    $(function(){
        if($_GET["id"]==3){
            $(".newAdd").attr("href","newAddress.php?id=3")
        }
        // 获取地址信息
        $.ajax({
                type: "post",
                url: commonsUrl + "/api/gxsc/get/delivery/goods/address" + versioninfos,
                data: {
                    ss: getCookie('openid')
                },
                success: function(data) {
					console.log(data)
                    try{
                        for(let val of data.result){
                            let temp=$("#commentList").html()
                            temp=temp.replace("{{address}}",val.address)
                                    .replace("{{mobile}}",val.mobile)
                                    .replace("{{name}}",val.name) 
                                    .replace("{{is_default}}",val.is_default==1?" active":"") 
                                    .replace("{{address_id}}",val.address_id)
                                    .replace("{{deladdress_id}}",val.address_id)
                                    .replace("{{skipid}}",val.address_id)
                                    .replace("{{compileid}}",val.address_id)
                                $(".xp-content02").append(temp)



                                  setTimeout(() => {
                                    //跳转
                                    $(".your-adress02").click(function(){
                                        if($_GET["id"]==3){
                                            let id=$(this).attr("id")
                                            $.ajax({
                                                type: "post",
                                                url: commonsUrl + "/api/gxsc/handle/delivery/goods/default/address" + versioninfos,
                                                data: {
                                                    address_id:id,
                                                    ss: getCookie('openid')
                                                },
                                                success: function(data) {
                                                    console.log(data)
                                                }
                                            });
                                                location.href="formOrder.php"
                                            }
                                    })
                                    

                                    //改变样式同时改变右边的文字
                                    $("span.zdy-icon-radio").click(function(){
                                        $(this).addClass("active").text("默认地址")
                                            .parent().parent().parent().siblings().find("span.zdy-icon-radio").removeClass("active").text("设为默认");
                                    
                                        let id=$(this).attr("id")

                                        $.ajax({
                                            type: "post",
                                            url: commonsUrl + "/api/gxsc/handle/delivery/goods/default/address" + versioninfos,
                                            data: {
                                                address_id:id,
                                                ss: getCookie('openid')
                                            },
                                            success: function(data) {
                                                console.log(data)
                                            }
                                        });
                                    });


                                    $("#del span").click(function(){
                                        let id=$(this).attr("data-id")
                                        $.ajax({
                                            type: "post",
                                            url: commonsUrl + "/api/gxsc/delete/delivery/goods/address" + versioninfos,
                                            data: {
                                                address_id:id,
                                                ss: getCookie('openid')
                                            },
                                            success: function(data) {
                                                console.log(data)
                                                layer.msg("删除成功")
                                            }
                                        });
                                        $(this).parents(".bianji-dizhi").remove()
                                    })

                                    // 编辑
                                    $("#compile span").click(function(){
                                        let id=$(this).attr("data-id")
                                        location.href='newAddress.php?id=1&address_id='+id
                                    })

                                }, 200);
                    }
                    }catch(e){
                        console.log(e)
                    }
                    
                }
        });

      
    })


</script>