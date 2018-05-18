<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
</head>
<style>
    body{
        background: rgb(243,242,242)
    }
    .header{
        width: 100%;
        height: 150px;
        background:rgb(77,109,253)
    }
    .header>div{
        width: 92%;
        margin: auto;
        padding-top: 40px
    }
    .header p{
        color: #fff;
    }
    .header p:nth-child(1){
        font-size: 24px;
        margin-bottom: 18px
    }
    .cashPledge{
        width: 92%;
        background: #fff;
        display: flex;
        justify-content: space-between;
        padding: 15px 4%;
    }
</style>
<body>
    <div class="header">
        <div>
            <p>正在处理中</p>
            <p>7日内班里退款，请您耐心等待~</p>
        </div>
    </div>
    <p class="cashPledge">
        <span>应退押金</span>
        <span class="money">100000000元</span>
    </p>
</body>
</html>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script>
    $.ajax({
        type:"post",
        url: commonsUrl + "api/gxsc/user/profile" +versioninfos,
        data:{
           'ss':getCookie('openid')	
        },
        success:function(data){
			$(".money").text(data.result.deposit+"元")	
			
        }
    });
</script>