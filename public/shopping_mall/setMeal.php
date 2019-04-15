<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>社员</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css">
</head>
<style>
    body{
        background: #f5f5f5;
    }
    .setMeal{
        width: 90%;
        margin: 220px auto 20px;
    }
    .title{
        margin-top: 15px;
        border-left: 3px solid #fff;
        padding-left: 4px;
        color: #fff;
    }
    .meal_classify{
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        padding: 21px 13px;
        border-radius: 5px;
        margin-top: 15px;
    }
    .meal_classify>p{
        background: #e63737;
        color: #fff;
        padding: 8px 20px;
        border-radius: 3px;
    }
    .meal_classify>div p{
        font-size: 18px;
    }
    .setMealBack{
        background-image: url("./images/setMealBack.png");
        background-size: 100% 100%;
        overflow: hidden;
    }
    .flow{
        display: block;
        padding-bottom: 0;
    }
    .flow p{
        font-size: 12px!important;
        color: #d14e14;
    }
    .flow img{
        width: 100%;
        margin: 15px 0;
    }
</style>
<body>
<div class="setMealBack">
    <div class="setMeal">
        <p class="title">专区分类</p>
        <!--<div class="meal_classify">
            <div>
                <p>
                    <span style="font-size: 24px;color: #e63737;">68</span>元区
                </p>
               
            </div>
            <p onclick="location.href='member_mall_list.php?goods_first_id=109&goods_first_name=68元区'">立即查看</p>
        </div>-->

            <div class="meal_classify">
               <div>
                    <p>
                       <span style="font-size: 24px;color: #e63737;">98</span>元区
                  </p>
        <!--            <p style="font-size: 15px;color: #b0b0b0;">-->
        <!--                旅游+蔬菜卡-->
        <!--            </p>-->
              </div>
               <p onclick="location.href='member_mall_list.php?goods_first_id=110&goods_first_name=98元区'">立即查看</p>
          </div>

        <div class="meal_classify">
            <div>
                <p>
                    <span style="font-size: 24px;color: #e63737;">365</span>元区
                </p>
                <!--<p style="font-size: 15px;color: #b0b0b0;">
                    精品蔬菜卡
                </p>-->
            </div>
            <p onclick="location.href='member_mall_list.php?goods_first_id=111&goods_first_name=365元区'">立即查看</p>
        </div>
        <p class="title">活动详情</p>
        <div class="meal_classify flow">
            <div>
                <p>1.凡在98元区下单的用户，进店扫码进社群，领取 100元手机话费充值卡，也可用于购物。</p>
                <img src="./images/setmealflow.png" alt="">
                <p>2.凡在365元区下单前50名的用户，进店扫码进社群， 每月进店领取100元手机话费充值卡，共1200元12个月领完，也可用于购买。</p>
                <img src="./images/setmealflowpath.png" alt="">
            </div>
        </div>
    </div>
</div>

</body>
</html>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/config.js"></script>
<script type="text/javascript" src="js/layer/layer.js"></script>