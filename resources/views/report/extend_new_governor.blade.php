<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>服务报告</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <style>
        body{
            margin: 0;
            padding: 0;
            font-size: 14px;
        }
        .service_members{
            float: left;
            width: 33.3%;
            font-size: 13px;
        }
        .service_members img{
            width: 33px;
            height: 33px;
            -webkit-border-radius:60px;
            -moz-border-radius:60px;
            border-radius:60px;
            margin-right:4px;
        }
        .webkit-ycenter{
            display: -webkit-box;
            -webkit-box-align: center;
        }
        .webkit-center{
            display: -webkit-box;
            -webkit-box-align: center;
            -webkit-box-pack: center;
        }
        .cl{
            background: #c29f7c;color:#fff;float: left;padding: 5px 10px;border-radius: 5px;margin-bottom: 5px;
        }
        .cl-li{
            color:#999;line-height: 1.5em;padding-bottom: 10px
        }
    </style>
</head>

<body>
<div style="padding: 15px 10px ;border-bottom: 6px solid #f3f3f3">
    <div class="webkit-ycenter">
        <div style="-webkit-box-flex: 1;color:#c29f7c;font-size: 15px;">督脉正阳服务报告</div>
        <div style="color:#999;font-size: 14px;">{{ $base_info->start_time }}</div>
    </div>
    <div style="color:#999;font-size: 13px;margin-top: 8px"><span>{{ $base_info->cityName }}</span><span style="margin: 0 5px;color:#c29f7c">|</span><span>{{ $base_info->shop_name }}</span></div>
</div>
<div style="padding: 15px 10px;">
    <div style="color:#4d4d4d;">服务人员</div>
    <div style="padding: 14px 0">
        <div class="webkit-center service_members">
            <img src="{{ $base_info->gov_thumbnail_image_url }}" alt="">
            <div>
                <div style="color:#656565">{{ $base_info->gov_name }}</div>
                <div style="font-size: 12px;margin-top: 3px;color:#808080">督脉正阳师</div>
            </div>
        </div>
        {{--<div class="webkit-center service_members">
            <img src="{{ $clinic_data['health']->thumbnail_image_url }}" alt="">
            <div>
                <div style="color:#656565">{{ $clinic_data['health']->user_name }}</div>
                <div style="font-size: 12px;margin-top: 3px;color:#808080">健康代表</div>
            </div>
        </div>--}}
        <div style="clear: both;"></div>
    </div>
</div>
<div style="background: #f3f3f3;height: 28px;color:#4d4d4d;padding: 0 10px" class="webkit-ycenter"><div>主诉症状</div> <img src="http://www.kospital.com/img/record-ysbt.png" alt="" style="width: 14px;position: relative;top:1px;margin-left: 5px;"></div>
<div style="padding: 15px 10px;">
    <div class="cl-li">{{  $base_info->symptom_content }}</div>
</div>
<div style="background: #f3f3f3;height: 28px;color:#4d4d4d;padding: 0 10px" class="webkit-ycenter"><div>检查结果</div> <img src="http://www.kospital.com/img/record-ysbt.png" alt="" style="width: 14px;position: relative;top:1px;margin-left: 5px;"></div>
<div style="padding: 15px 10px;">


    <div class="cl">问诊</div>
    <div style="clear: both"></div>

    @foreach($check['inquiry'] as $v)

        <div class="cl-li">
            <?php
                 echo  $v['symptom'];
            ?>
        </div>  {{--这种写法就会转义变量中的<br>等变量，就会把数据原样输出--}}
    @endforeach

    <div class="cl">触诊</div>
    <div style="clear: both"></div>
    @foreach($check['tactus'] as $v)

        <div class="cl-li">
            <?php
                 echo  $v['symptom'];
            ?>
        </div>  {{--这种写法就会转义变量中的<br>等变量，就会把数据原样输出--}}
    @endforeach

    <div class="cl">望诊</div>
    <div style="clear: both"></div>
    @foreach($check['observation'] as $v)

        <div class="cl-li">
            <?php
                 echo  $v['symptom'];
            ?>
        </div>  {{--这种写法就会转义变量中的<br>等变量，就会把数据原样输出--}}
    @endforeach


</div>
</body>

</html>