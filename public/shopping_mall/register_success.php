<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" type="text/css" href="css/common.css"/>
    <title>注册成功</title>
</head>
<style>
    .register_success{
        width: 100%;
        background: url("./images/register_success.png");
        background-size: 100% 100%;
        overflow: hidden;
    }
    img{
        width: 80%;
        margin: 70px auto;
        display: block;
    }
</style>
<body>
<div class="register_success">
    <img src="./images/packs.png" alt="">
</div>
</body>
</html>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/config.js"></script>
<script type="text/javascript" src="js/layer/layer.js"></script>
<script>
    $('.register_success').css('height',$(this).height());
</script>