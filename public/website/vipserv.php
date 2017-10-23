<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>尊享服务</title>
		<link rel="stylesheet" href="css/common.css">
		<link rel="stylesheet" href="css/swiper.min.css">	
	    <link rel="stylesheet" href="css/vipservice.css">
        <style>
            .odbox{
                padding: 30px 50px;
                background: #fff;
            }
            .odlist{
                margin-top: 7px;
                line-height: 38px;
                color:#575757;
                font-size: 18px;
                border-bottom: 1px dashed #c1c1c1;
                cursor: pointer;
            }
            .odlist:hover{
                color:#343434;
            }
            .odtitle{
                text-align: center;
                font-size: 26px;
                color:#21c0d5;
                margin-bottom: 32px;
            }
            .odse{
                margin:15px auto;
                width: 400px;
            }
            .odfro{
                float: left;
                color:#575757;
                font-size: 16px;
                line-height: 38px;
            }
            .sel{
                float: left;
                width: 345px;
                margin-left: 20px;
                height: 40px;
                border-radius: 5px;
                border: 1px solid #21d0c5;
                appearance:none;
                -moz-appearance:none; /* Firefox */
                -webkit-appearance:none; /* Safari 和 Chrome */
                background: #f0f0f0;

                background: url("images/vip/selectDown.png") no-repeat scroll right center whitesmoke;


                padding: 0 23px;
                color: #585858;
                font-size: 15px
            }
            .sel option{
                line-height: 40px;
                color:#585858;
                font-size: 15px;
            }
            select::-ms-expand { display: none; }
            .input2{
                float: left;
                width: 325px;
                margin-left: 20px;
                height: 40px;
                border-radius: 5px;
                border: 1px solid #B9B9B9;
                padding: 0 10px;
                box-shadow: 0px 0px 1px 0px #B9B9B9;
                color:#585858;
                font-size: 15px;
            }
            .odsub{
                float: left;
                width: 347px;
                margin-left: 20px;
                margin-top: 30px;
                height: 40px;
                border-radius: 5px;
                background: #21d0c5;
                color:#fff;
                text-align: center;
                line-height: 40px;
                font-size: 16px;
                cursor: pointer;
            }
            input::-webkit-inner-spin-button{
                position: relative;
                top:9px;
            }
            ::-webkit-datetime-edit {line-height: 40px;height: 40px;
            }
            ::-webkit-datetime-edit-text { color: #585858; padding: 1em; }
            ::-webkit-datetime-edit-year-field {
                color:#585858;
                font-size: 15px;
            }
            ::-webkit-datetime-edit-month-field {
                color:#585858;
                font-size: 15px;
            }
            ::-webkit-datetime-edit-day-field {
                color:#585858;
                font-size: 15px;
            }
            ::-webkit-inner-spin-button { visibility: hidden; }
            ::-webkit-calendar-picker-indicator {
                border: 1px solid #ccc;
                border-radius: 2px;
                box-shadow: inset 0 1px #fff, 0 1px #eee;
                color: #666;
            }
        </style>
	</head>
	<body>
		<?php include 'header.php' ?>
		<div class="container">
			<div class="banner"></div>
	    	<div class="content">
	    		<div class="chooseTab left">
	    			<ul>
                        <li class="first" item="7" onclick="orderAjax(this,7,1)">
                            <a><i class="one"></i><span>医疗服务</span></a>
                        </li>
	    				<li class="first" item="1" onclick="orderAjax(this,1,1)">
	    					<a><i class="two"></i><span>海外医疗</span></a>
	    				</li>
                        <li class="first" item="2" onclick="orderAjax(this,2,1)">
                            <a><i class="three"></i><span>健康管理</span></a>
                        </li>
                        <li class="first" item="3" onclick="orderAjax(this,3,1)">
                            <a><i class="four"></i><span>产业医生</span></a>
                        </li>
                        <li class="first" item="4" onclick="orderAjax(this,4,1)">
                            <a><i class="five"></i><span>增值服务</span></a>
                        </li>
                        <li class="first" item="5" onclick="orderAjax(this,5,1)">
                            <a><i class="six"></i><span>个性化订制</span></a>
                        </li>
                        <li class="first" item="6" onclick="orderAjax(this,6,1)">
                            <a><i class="seven"></i><span>保险经纪</span></a>
                        </li>
	    			</ul>
	    		</div>
	    		<div class="chooseShow left">
	    			<div class="odbox">

                    </div>
	    		</div>
	    		<div class="clearfix"></div>
	    	</div>
	    </div>
		
		<?php include 'footer.php' ?>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
        <script type="text/javascript" src="js/layer/layer.js"></script>
        <script type="text/javascript" src="js/city.js"></script>
        <script type="text/javascript" src="js/function.js"></script>

        <script>
        	sessionArr = getCookie('sessionArr');
	    	if(!sessionArr){
				ss = '';
			}else{
				ss = JSON.parse(sessionArr).session;
			}
            proStr = JSON.parse(proStr);
            proStrLength=proStr.length;
            if($_GET['index']){
                ind = $_GET['index'];
            }else{
                ind = 7;
            }
            $('.first').each(function(){
                item = $(this).attr('item');
                if(item==ind){
                    obj = $(this);
                }
            });
            orderAjax(obj,ind,'');
            //ajax orderlist
            function orderAjax(obj,od,show){
                $(obj).addClass('choosen');
                $(obj).siblings().removeClass('choosen');


                layer.load(2);
                if($_GET['checkid']&&show==''){
                    openCheck();
                }else{
                    $.ajax({
                        type:'POST',
                        url:commonUrl + 'api/stj/service_order/order_list'+versioninfo,
                        data:{
                            'type':od
                        },
                        success:function(data){
                            layer.closeAll();
//                          console.log(data);
                            if(data.code == 1){
                                html= '';
                                if(data.result.type == 1){
                                    //列表形式
                                    list = data.result.list;
                                    if(list.length!=0){
                                    	for(i=0;i<list.length;i++){
	                                        html +='<div class="odlist" onclick="openContent('+list[i].open_type+','+list[i].id+',\''+escape(list[i].title)+'\',\''+escape(list[i].content)+'\')">'+list[i].title+'</div>';
	                                    }
                                    }
                                }else if(data.result.type == 2){
                                    //详情形式
                                    content = data.result.list[0].content;
                                    console.log(content);
                                    html += content;
                                }
                                $('.odbox').html(html);
                            }else{
                                layer.msg(data.msg);
                            }
                        }
                    });
                }
            }
            function openCheck(){
                $.ajax({
                    type:'POST',
                    url:commonUrl + 'api/stj/service_order/order_list'+versioninfo,
                    data:{
                        'type':2
                    },
                    success:function(data){
                        layer.closeAll();
//                      console.log(data);
                        if(data.code == 1){
                            //列表形式
                            list = data.result.list;
                            if(list.length!=0){
	                            for(i=0;i<list.length;i++){
	                                if(list[i].id == $_GET['checkid']){
	                                    openContent(1,list[i].id,escape(list[i].title),escape(list[i].content));
	                                }
	                            }
	                        }
                        }else{
                            layer.msg(data.msg);
                        }
                    }
                });
            }
            function openContent(type,id,title,content){
                if(type==1){
                    //
                    //填写服务表单
                    $.ajax({
                        type:'POST',
                        url:commonUrl + 'api/stj/service_order/option_list'+versioninfo,
                        data:{
                            'sid':id,
                            'ss':ss
                        },
                        success:function(data){
                            layer.closeAll();
//                          console.log(data);
                            price='';
                            if(data.code == 1){
                                oplist = data.result.oplist;
                                optionhtml = '<option value="" disabled selected>请选择</option>';
                                for(i=0;i<oplist.length;i++){
                                    optionhtml +='<option price="'+oplist[i].price+'" value="'+oplist[i].id+'">'+oplist[i].title+'</option>';
                                }
                                if(!unescape(content)){
                                    content = '暂无';
                                }else{
                                    content = unescape(content);
                                }
                                html='';
                                html +='<div style="color:#21c0d5;font-size: 23px;margin-bottom: 20px">'+unescape(title)+'</div>';
                                html +='<div style="color:#21c0d5;font-size: 14px;margin-bottom: 12px">详细介绍</div>';
                                html +='<div style="border:1px solid #b6b6b6;min-height:90px;border-radius:5px;font-size: 14px;color:#595757;padding: 13px">'+content+'</div>';
                                html +='<div style="font-size: 14px;color:#595757;margin-top: 36px"><div style="float: left;line-height: 38px">选择服务</div><select name="" id="" class="sel server_type">'+optionhtml+'</select><div class="showprice" style="display:none;float: right;line-height: 38px"><span>需付款：<span style="color:#ff0000;margin-right:3px">¥</span></span><span style="color:#ff0000;font-size: 16px" class="pricenum"></span></div><div class="clearfix"></div></div>';
                                html +='<div style="width: 100%;height: 1px;background: #b6b6b6;margin-top: 30px;margin-bottom: 30px"></div>';
                                html +='<div>' +
                                '<div><div style="float: left;line-height: 38px;color:#595757">姓名</div><input class="input2 nametype" maxlength="6"  style="width: 100px" type="text"/><div style="float: left;line-height: 38px;margin-left: 50px;color:#595757">手机号码</div><input maxlength="11"  class="input2 mobiletype" id="uphone" style="width: 100px" type="text"/></div>' +
                                '<div class="clearfix"></div>' +
                                '<div style="margin-top: 27px"><div style="float: left;color: #595757;margin-top: 10px">备注</div><textarea name="" id="" maxlength="200" class="input2 service_content" style="padding: 10px;min-height: 4em;font-size: 15px;width: 510px;line-height: 1.5em" placeholder="选填：对本次交易的说明或者自己的要求"></textarea></div>' +
                                '<div class="clearfix"></div>' +
                                '</div>';
                                html +='<div style="background: #21c0d5;width: 150px;height: 40px;color:#fff;border-radius: 5px;text-align: center;line-height: 40px;margin: 65px auto;cursor: pointer;" onclick="odsubPay(pprice,ptext)">提交</div>';
                                $('.odbox').html(html);
                                $('.server_type').change(function () {
                                    pprice = $(".server_type option:selected").attr('price');
                                    ptext = $(".server_type option:selected").text();
                                    $('.pricenum').html(pprice);
                                    $('.showprice').show();
                                });
                                
                                $("#uphone").keyup(function(){
									phoneval=$(this).val();
									$(this).val(phoneval.replace(/\D/g,''));
								})
                                
                            }else if(data.code == "1011"){
                                layer.msg('帐号在其他设备登录，请重新登录');
                                setTimeout("removeCookie('sessionArr');location.href='login.php'",1000);
                            }else{
                                layer.msg(data.msg);
                            }
                        }
                    });

                }else if(type == 2||type==3){
                    odoption = '<option value="" disabled selected>请选择</option>';
                    province = '<option value="" disabled selected>请选择</option>';
                    if(type==2){
                        odname = '预约挂号';
                        //option
                    }else if(type==3){
                        odname = '住院治疗';
                    }
                    for(i=0;i<proStrLength;i++){
                        provinceName = proStr[i].NAME;
                        provinceId = proStr[i].ID;
                        province += '<option value="'+provinceId+'">'+provinceName+'</option>';
                    }
                    layer.load(2);
                    $.ajax({
                        type:'POST',
                        url:commonUrl + 'api/stj/getrecollection'+versioninfo,
                        data:{
                            'type':type
                        },
                        success:function(data){
//                          console.log(data);
                            if(data.code == 1){
                                //获取科室
                                recresult = data.result;
                                rec = '<option value="" disabled selected>请选择</option>';
                                for(i=0;i<recresult.length;i++){
                                    recId = recresult[i].id;
                                    recName = recresult[i].name;
                                    rec += '<option value="'+recId+'">'+recName+'</option>';
                                }

                                //获取医院服务名
                                $.ajax({
                                    type:'POST',
                                    url:commonUrl + 'api/stj/service_order/hospital_class'+versioninfo,
                                    data:{
                                        'type':type
                                    },
                                    success:function(data){
                                        layer.closeAll();
//                                      console.log(data);
                                        if(data.code == 1){
                                            for(i=0;i<data.result.length;i++){
                                                result = data.result;
                                                typename = result[i].name;
                                                typeid = result[i].id;
                                                odoption += '<option value="'+typeid+'">'+typename+'</option>';
                                            }

                                            html = '';
                                            html+='<div class="odtitle">'+odname+'</div>';
                                            html+='    <div class="odse">';
                                            html+='    <div class="odfro">类别</div>';
                                            html+='    <select name="" class="sel class_type" id="">'+odoption+'</select>';
                                            html+='    <div class="clearfix"></div>';
                                            html+='    </div>';
                                            html+='    <div class="odse">';
                                            html+='    <div class="odfro">省份</div>';
                                            html+='    <select name="" class="sel province" id="">'+province+'</select>';
                                            html+='    <div class="clearfix"></div>';
                                            html+='    </div>';
                                            html+='    <div class="odse">';
                                            html+='    <div class="odfro">市区</div>';
                                            html+='    <select name="" class="sel city" id=""><option value="" disabled selected>请选择</option></select>';
                                            html+='    <div class="clearfix"></div>';
                                            html+='    </div>';
                                            html+='    <div class="odse">';
                                            html+='    <div class="odfro">医院</div>';
                                            html+='    <select name="" class="sel hospital" id=""><option value="" disabled selected>请选择</option></select>';
                                            html+='    <div class="clearfix"></div>';
                                            html+='    </div>';
                                            html+='    <div class="odse">';
                                            html+='    <div class="odfro">科室</div>';
                                            html+='    <select name="" class="sel recollection" prompt="请选择" id="">'+rec+'</select>';
                                            html+='    <div class="clearfix"></div>';
                                            html+='    </div>';
                                            html+='    <div class="odline" style="margin: 25px auto;height: 1px;width: 397px;background: #C0C0C0"></div>';
                                            html+='    <div class="odse">';
                                            html+='    <div class="odfro">姓名</div>';
                                            html+='    <input type="text" class="input2 name" maxlength="6" />';
                                            html+='    <div class="clearfix"></div>';
                                            html+='    </div>';
                                            html+='    <div class="odse">';
                                            html+='    <div class="odfro">电话</div>';
                                            html+='    <input type="text" class="input2 phone" maxlength="11" id="rphone" />';
                                            html+='    <div class="clearfix"></div>';
                                            html+='    </div>';
                                            html+='    <div class="odse">';
                                            html+='    <div class="odfro">时间</div>';
                                            html+='    <input type="date" class="input2 time" />';
                                            html+='    <div class="clearfix"></div>';
                                            html+='    </div>';
                                            html+='    <div class="odse">';
                                            html+='    <div class="odfro" style="visibility: hidden">时间</div>';
                                            html+='    <div class="odsub" onclick="odsub()">提&nbsp;&nbsp;&nbsp;&nbsp;交</div>';
                                            html+='    <div class="clearfix"></div>';
                                            html+='</div>';
                                            $('.odbox').html(html);

											$("#rphone").keyup(function(){
												phoneval=$(this).val();
												$(this).val(phoneval.replace(/\D/g,''));
											})
											
											
                                            $('.province').change(function(){
                                                pro = $(this).val();
                                                items = '';
                                                provinceId = '';
                                                city = '<option value="" disabled selected>请选择</option>';
                                                for(i=0;i<proStrLength;i++){
                                                    provinceId = proStr[i].ID;
                                                    if(provinceId == pro){
                                                        items = proStr[i].ITEMS;
                                                        itemsLength = items.length;
                                                        for(j=0;j<itemsLength;j++){
                                                            cityName = items[j].NAME;
                                                            cityId = items[j].ID;
                                                            city += '<option value="'+cityId+'">'+cityName+'</option>';
                                                        }
                                                    }
                                                }
                                                $('.city').html(city);
                                            });
                                            //city & type change
                                            class_type='';
                                            cityVal='';
                                            $('.city').change(function(){
                                                cityVal = $(this).val();
                                                if(class_type){
                                                    showHospital(cityVal,class_type);
                                                }
                                            });
                                            $('.class_type').change(function(){
                                                class_type = $(this).val();
                                                if(cityVal){
                                                    showHospital(cityVal,class_type);
                                                }
                                            });
                                            function showHospital(city,class_type){
                                                //请求医院列表
                                                layer.load(2);
                                                $.ajax({
                                                    type:'POST',
                                                    url:commonUrl + 'api/stj/org_distribution/therr_list'+versioninfo,
                                                    data:{
                                                        'type':1,
                                                        'city':city,
                                                        'class_type':class_type
                                                    },
                                                    success:function(data){
                                                        layer.closeAll();
//                                                      console.log(data);
                                                        hospital = '';
                                                        if(data.code == 1){
                                                            if(data.result.length==0){
                                                                layer.msg("当前城市无可选医院，请重新选择");
                                                            }else{
                                                                for(i=0;i<data.result.length;i++){
                                                                    hosName = data.result[i].name;
                                                                    hosId = data.result[i].id;
                                                                    hospital += '<option value="'+hosId+'">'+hosName+'</option>';
                                                                }
                                                                $('.hospital').html(hospital);
                                                            }
                                                        }else{
                                                            layer.msg(data.msg);
                                                        }
                                                    }
                                                });
                                            }
                                        }else{
                                            layer.msg(data.msg);
                                        }
                                    }
                                });


                            }else{
                                layer.msg(data.msg);
                            }
                        }
                    });


                }
            }



            //提交
            function odsub(){
                class_type='';
                city='';
                hospital='';
                recollection='';
                name='';
                phone='';
                time='';
                class_type = $('.class_type').val();
                city = $('.city').val();
                hospital = $('.hospital').val();
                recollection = $('.recollection').val();
                name = $('.name').val();
                phone = $('.phone').val();
                time = $('.time').val();
                sessionArr = getCookie('sessionArr');
                if(!sessionArr){
                    layer.msg('请登录后提交');
                    setTimeout("location.href='login.php'",1000);
                }else{
                    if(class_type==''||city==''||hospital==''||phone==''||recollection==''||name==''||time==''){
                        layer.msg('请填写完整');
                    }else if(!testTel(phone)){
                        layer.msg('请填写正确的手机号');
                    }else{
                        time = time+' 00:00:00';
                        $.ajax({
                            type:'POST',
                            url:commonUrl + 'api/stj/service_order/sub_order'+versioninfo,
                            data:{
                                'ss':JSON.parse(sessionArr).session,
                                'department_id':recollection,
                                'hospital_class':class_type,
                                'hospital_id':hospital,
                                'mobile':phone,
                                'name':name,
                                'open_type':2,
                                'time':time
                            },
                            success:function(data){
                                layer.closeAll();
//                              console.log(data);
                                hospital = '';
                                if(data.code == 1){
                                    layer.msg('提交成功');
                                    setTimeout('location.href="personal_center.php?queue=2"',1000);
                                }else if(data.code == "1011"){
                                    layer.msg('帐号在其他设备登录，请重新登录');
                                    setTimeout("removeCookie('sessionArr');location.href='login.php'",1000);
                                }else{
                                    layer.msg(data.msg);
                                }
                            }
                        });
                    }
                }
            }



            //提交
            function odsubPay(pprice,ptext){
                mobile='';
                name='';
                option_id='';
                mobile = $('.mobiletype').val();
                name = $('.nametype').val();
                option_id = $('.server_type').val();
                service_content = $('.service_content').val();
                sessionArr = getCookie('sessionArr');
                if(!sessionArr){
                    layer.msg('请登录后提交');
                    setTimeout("location.href='login.php'",1000);
                }else{
                    if(mobile==''||name==''||option_id==null){
                        layer.msg('请填写完整');
                    }else if(!testTel(mobile)){
                        layer.msg('请填写正确的手机号');
                    }else{
                        $.ajax({
                            type:'POST',
                            url:commonUrl + 'api/stj/service_order/sub_order'+versioninfo,
                            data:{
                                'ss':JSON.parse(sessionArr).session,
                                'mobile':mobile,
                                'name':name,
                                'option_id':option_id,
                                'service_content':service_content,
                                'open_type':1
                            },
                            success:function(data){
                                layer.closeAll();
//                              console.log(data);
                                hospital = '';
                                if(data.code == 1){
                                    if(data.result.pay==1){
                                        layer.msg('提交成功，请尽快进行支付');
                                        setTimeout('location.href="paySelectPayType.php?oid='+data.result.order_id+'&name='+ptext+'&price='+pprice+'&type=1"',1000);
                                        //type=1 服务， type =2 商品
                                    }else if(data.result.pay==0){
                                        layer.msg('提交成功');
                                        setTimeout('location.href="personal_center.php?queue=2"',1000);
                                    }
                                }else if(data.code == "1011"){
                                    layer.msg('帐号在其他设备登录，请重新登录');
                                    setTimeout("removeCookie('sessionArr');location.href='login.php'",1000);
                                }else{
                                    layer.msg(data.msg);
                                }
                            }
                        });
                    }
                }
            }













		    $('#tab>li:eq(4)').addClass('active');
		    $('.chooseTab li').click(function(){
		    	$(this).addClass('choosen');
		    	$(this).siblings().removeClass('choosen');
		    	index=$(this).index();
				$('.chooseShow iframe').attr('src','personal/'+(index+1)+'.html');
		    });
		    //从导航栏进来，点击哪个进入对应的位置
            index_id=$_GET['index'];
            $('.chooseTab li').eq(index_id).addClass('choosen');
            $('.chooseTab li').eq(index_id).siblings().removeClass('choosen');

	    </script>

	</body>
</html>
