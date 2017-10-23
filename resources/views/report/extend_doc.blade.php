<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>督脉正阳</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <style>
        body{
            margin: 0;
            padding: 0;
        }
        .clear{
            clear: both;
        }
        .contain{
            padding: 15px;
        }
        .main{
            height: 15px;
            width: 180px;
            margin: 0 auto;
        }
        .main .p1{
            float: left;
            width: 90px;
            text-align: center;
            font-size: 15px;
            line-height: 15px;
            color:#3EC6BA;
            font-weight: 700;
        }
        .main .leftbg{
            background: -webkit-gradient(linear,0% 0%, 90% 0%, from(#FFFFFF), to(#3EC6BA));
            width: 45px;
            height: 15px;
            background-size: 45px 2px;
            background-repeat: no-repeat;
            float: left;
            background-position: center center;
        }
        .main .rightbg{
            background: -webkit-gradient(linear,90% 0%, 0% 0%, from(#FFFFFF), to(#3EC6BA));
            width: 45px;
            height: 15px;
            background-size: 45px 2px;
            background-repeat: no-repeat;
            float: left;
            background-position: center center;
        }
        .timebox{
            margin-top: 15px;
        }
        .time{
            float: left;
            width: 30px;
            height: 24px;
            background-image: url("{{asset('images/time.png')}}");
            background-size: 24px 100%;
            background-repeat: no-repeat;
        }
        .timep{
            float: left;
            font-size: 14px;
            color:#323232;
            line-height: 24px;
            border-bottom: 1px solid #e4e6e8;
            padding-bottom: 13px;
        }
        .symptombox{
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            margin-top: 15px;
        }
        .symptom{
            float: left;
            width: 30px;
            height: 24px;
            background-image: url("{{asset('images/doc.png')}}");
            background-size: 24px 100%;
            background-repeat: no-repeat;
        }
        .symptomp{
            float: left;
            font-size: 14px;
            color:#323232;
            line-height: 24px;
        }
        .symptomp2{
            float: left;
            font-size: 14px;
            color:#323232;
            line-height: 24px;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            overflow: hidden;
            -webkit-box-flex: 1;
            -webkit-flex: 1;
            -ms-flex: 1;
            flex: 1;
        }
        .symptomp2:active{
            background: #f6f6f6;
        }
        .nav{
            background: #eeeeee;
            height: 10px;
            width: 100%;
            margin-top: 5px;
        }
        .result-title{
            color:#3EC6BA;
            font-weight: 700;
            margin-top: 15px;
            font-size: 14px;
        }
        .resultbox{
            margin-top: 10px;
            border: 1px solid #3EC6BA;
            border-radius: 5px;
            padding: 10px;
            color:#464646;
            font-size: 14px;
            line-height: 20px;
            background: #f3f3f3;
        }
    </style>

</head>

<body>
<div class="contain">
    <div class="main">
        <div class="leftbg"></div><div class="p1">我的主诉</div><div class="rightbg"></div>
        <div class="clear"></div>
    </div>
    <!--
    <div class="timebox">
        <div class="time"></div><div class="timep">发病时间：已有1月多</div>
        <div class="clear"></div>
    </div>
    -->
    <div class="symptombox">
        <div class="symptom"></div>
        <div class="symptomp">主要症状：</div>
        <div class="symptomp2">{{$result['symptom_content']}}</div>
        <div class="clear"></div>
    </div>
</div>
<div class="nav"></div>

<div class="contain">
    <div class="main">
        <div class="leftbg"></div><div class="p1">诊断结果</div><div class="rightbg"></div>
        <div class="clear"></div>
    </div>
    <div class="result">
        <p>{{$result['inspect_result']}}</p>
    </div>
</div>
<script src="{{asset('js/jquery.min.js')}}"></script>
<script>
    $(function(){
        totalwidth = $('.symptombox').width();
        imgwidth = $('.symptom').width();
        pwidth = $('.symptomp').width();
        symptomp2 = $('.symptomp2');
        symptomp2.width(totalwidth-imgwidth-pwidth);
        $('.timep').width(totalwidth-imgwidth);
        symptomp2.click(function(){
            css = $(this).css('display');
            if(css == "-webkit-box"){
                $(this).css('display','block');
            }else{
                $(this).css('display','-webkit-box');
            }
        });
    });
</script>
</body>

</html>
