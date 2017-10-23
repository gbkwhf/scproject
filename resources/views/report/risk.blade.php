<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>风险评估</title>
	<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
    <style>
        .contain{
            padding: 10px;
        }
        .contain .riskbox{
            padding: 10px 16px;
            background-image: url("{{asset('images/bg.png')}}");
            background-size: 100% 100%;
            padding-bottom: 16px;
            margin-bottom: 10px;
        }
        .riskbox .p1{
            float: left;
            font-size: 15px;
            color:#333;
            line-height: 35px;

        }
        .riskbox .p2{
            float: right;
            font-size: 14px;
            color:#ff9849;
            padding-right: 19px;
            line-height: 35px;
        }
        .riskbox .p3{
            color:#787878;
            font-size: 13px;
            line-height: 27px;
            word-wrap: break-word;
        }
    </style>
</head>

<body>
    <div class="contain">
        <div class="riskbox">
            <div class="p1">心脑血管疾病</div><div class="p2"></div>
            <div style="clear: both;"></div>
            <div class="p3">{{$result['cardiovascular']}}</div>
        </div>

        <div class="riskbox">
            <div class="p1">癌症</div><div class="p2"></div>
            <div style="clear: both;"></div>
            <div class="p3">{{$result['cancer']}}</div>
        </div>

        <div class="riskbox">
            <div class="p1">慢性病</div><div class="p2"></div>
            <div style="clear: both;"></div>
            <div class="p3">{{$result['chronic_disease']}}</div>
        </div>

        <div class="riskbox">
            <div class="p1">脊柱疾病</div><div class="p2"></div>
            <div style="clear: both;"></div>
            <div class="p3">{{$result['spinal_diseases']}}</div>
        </div>
    </div>
</body>

</html>
