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
            background: #4dbbaa;color:#fff;float: left;padding: 5px 10px;border-radius: 5px;margin-bottom: 7px;margin-top: 15px;
        }
        .cl-li{
            color:#999;line-height: 1.5em;
        }
    </style>
</head>

<body>
<div style="padding: 15px 10px ;border-bottom: 6px solid #f3f3f3">
    <div class="webkit-ycenter">
        <div style="-webkit-box-flex: 1;color:#4dbbaa;font-size: 15px;">私人医生服务报告</div>
        <div style="color:#999;font-size: 14px;">{{ $result['baseInfo']['date'] }}</div>
    </div>
    <div style="color:#999;font-size: 13px;margin-top: 8px"><span>{{ $result['baseInfo']['city'] }}</span><span style="margin: 0 5px;color:#4dbbaa">|</span><span>{{ $result['baseInfo']['name'] }}</span></div>
</div>
<div style="padding: 15px 10px;">
    <div style="color:#4d4d4d;">服务人员</div>
    <div style="padding: 14px 0">
        <div class="webkit-center service_members">
            <img src=" {{ $result['servicePeople']['privateDoc']['thumbnail_image_url'] }} " alt="">
            <div>
                <div style="color:#656565">{{ $result['servicePeople']['privateDoc']['user_name'] }}</div>
                <div style="font-size: 12px;margin-top: 3px;color:#808080">私人医生</div>
            </div>
        </div>
        <div class="webkit-center service_members">
            <img src="{{ $result['servicePeople']['governor']['thumbnail_image_url'] }}" alt="">
            <div>
                <div style="color:#656565">{{ $result['servicePeople']['governor']['user_name'] }}</div>
                <div style="font-size: 12px;margin-top: 3px;color:#808080">督脉正阳师</div>
            </div>
        </div>
        <div class="webkit-center service_members">
            <img src="{{ $result['servicePeople']['health']['thumbnail_image_url'] }}" alt="">
            <div>
                <div style="color:#656565">{{ $result['servicePeople']['health']['user_name'] }}</div>
                <div style="font-size: 12px;margin-top: 3px;color:#808080">健康代表</div>
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>
<div style="background: #f3f3f3;height: 28px;color:#4dbbaa;padding: 0 10px" class="webkit-ycenter">
    <div>主诉症状</div>
    <img src="http://www.kospital.com/img/record.png" alt="" style="width: 14px;position: relative;top:1px;margin-left: 5px;">
</div>
<div style="padding: 15px 10px;">
    <div class="cl-li">{{ $result['symptom_content']  }}</div>
</div>
<div style="background: #f3f3f3;height: 28px;color:#4dbbaa;padding: 0 10px" class="webkit-ycenter">
    <div>检查结果</div>
    <img src="http://www.kospital.com/img/record.png" alt="" style="width: 14px;position: relative;top:1px;margin-left: 5px;">
</div>
<div style="padding: 15px 10px;">
    <div class="cl-li" style="border-bottom: 1px solid #f3f3f3;margin-bottom: 15px;padding-bottom: 10px">{{ $result['inspect_result']  }}</div>
    <div style="color:#656565;font-size: 13px">背诊结果：</div>

    @foreach($result['backDiagnosis'] as $v)

        <div class="cl">{{ $v['body'] }}</div>
        <div style="clear: both"></div>
        <div class="cl-li">{!!$v['symptom']!!}</div>  {{--这种写法就会转义变量中的<br>等变量，就会把数据原样输出--}}

    @endforeach


   {{-- <div class="cl">骶髂关节</div>
    <div style="clear: both"></div>
    <div class="cl-li">颈5椎体缘骨质增生，颈5/6椎间盘退变，颈t*侧钩*变大变尖，颈曲存在中段颈椎钩突,前后缘稍增生,中段颈椎间隙稍窄,中段颈椎椎</div>
--}}
</div>


<div style="background: #f3f3f3;height: 28px;color:#4dbbaa;padding: 0 10px" class="webkit-ycenter">
    <div>健康评估</div>
    <img src="http://www.kospital.com/img/record2.png" alt="" style="width: 14px;position: relative;top:1px;margin-left: 5px;">
</div>
<div class="cl-li" style="margin: 15px 10px;">{{ $result['h_estimate'] }}</div>



<div style="background: #f3f3f3;height: 28px;color:#4dbbaa;padding: 0 10px" class="webkit-ycenter">
    <div>健康计划</div>
    <img src="http://www.kospital.com/img/record3.png" alt="" style="width: 14px;height:15px;position: relative;top:1px;margin-left: 5px;">
</div>
<div class="webkit-ycenter" style="margin-top:15px;padding: 0 10px;color:#4d4d4d;font-size: 13px">
    <img src="http://www.kospital.com/img/plan1.png" alt="" style="width: 14px;position: relative;top:1px;margin-right: 5px;">
    <div>饮食营养计划</div>
</div>
<div class="cl-li" style="margin: 15px 10px ;">{{ $result['healthPlan']['plan_nutrition'] }}</div>
<div class="webkit-ycenter" style="padding: 0 10px;color:#4d4d4d;font-size: 13px">
    <img src="http://www.kospital.com/img/plan2.png" alt="" style="width: 14px;position: relative;top:1px;margin-right: 5px;">
    <div>运动保健计划</div>
</div>
<div class="cl-li" style="margin: 15px 10px ;">{{ $result['healthPlan']['plan_sports'] }}</div>
<div class="webkit-ycenter" style="padding: 0 10px;color:#4d4d4d;font-size: 13px">
    <img src="http://www.kospital.com/img/plan3.png" alt="" style="width: 14px;position: relative;top:1px;margin-right: 5px;">
    <div>心理保健计划</div>
</div>
<div class="cl-li" style="margin: 15px 10px ;">{{ $result['healthPlan']['plan_psychology'] }}</div>
<div class="webkit-ycenter" style="padding: 0 10px;color:#4d4d4d;font-size: 13px">
    <img src="http://www.kospital.com/img/plan4.png" alt="" style="width: 14px;position: relative;top:1px;margin-right: 5px;">
    <div>社会健康计划</div>
</div>
<div class="cl-li" style="margin: 15px 10px ;">{{ $result['healthPlan']['plan_society'] }}</div>
<div class="webkit-ycenter" style="padding: 0 10px;color:#4d4d4d;font-size: 13px">
    <img src="http://www.kospital.com/img/plan5.png" alt="" style="width: 14px;position: relative;top:1px;margin-right: 5px;">
    <div>脊柱保健计划</div>
</div>
<div class="cl-li" style="margin: 15px 10px ;">{{ $result['healthPlan']['plan_spine'] }}</div>


</body>

</html>
