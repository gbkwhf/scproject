<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>编辑病历</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" type="text/css" href="css/common.css"/>
		<link rel="stylesheet" type="text/css" href="css/mobiscroll/mobiscroll.core-2.5.2.css" />
		<link rel="stylesheet" type="text/css" href="css/mobiscroll/mobiscroll.animation-2.5.2.css" />
		<link rel="stylesheet" type="text/css" href="css/mobiscroll/mobiscroll.android-ics-2.5.2.css" />
		<link rel="stylesheet" type="text/css" href="css/illness_details.css" />
		<style>
	        .upload-box{
	        	overflow: hidden;
	        	border-bottom: 1px solid #ccc;
	        	padding: 0px 15px;
	        	margin-bottom: 20px;
	        }
        	.upload-box .upload{
        		width: 89px;
			    height: 89px;
			    background-image: url('image/upload-add.png');
			    background-size: contain;
			    background-repeat: no-repeat;
			    position: relative;
			    margin: 10px 5px 5px 0px;
			    border: 1px solid #fff;
			    border-radius: 3px;
			    transition: all 0.3s;
			    -webkit-transition: all 0.3s;
			    overflow: hidden;
			    float: left;
        	}
        	.upload-box input{
        		width: 100%;
        		height: 100%;
        		opacity: 0;
        		position: absolute;
        		left: 0px;
        		top: 0px;
        		z-index: 4;
        	}
        	.upload .del,.picbox .del{
        		display: block;
        		position: absolute;
        		right: 0;
        		top: 0;
        		z-index: 5;
        		width: 25px;
        	}
        </style>
	</head>
	<body>
		<div class="container">
			<div id="illness">
				<!--<p class="sub-title">个人病历</p>
				<div class="border-box">
					<label for="diagnosis">病情诊断</label>
					<input type="text" name="diagnosis" id="diagnosis" value="" />
				</div>
				<div class="border-box">
					<label for="build_time">就诊日期</label>
					<input type="text" placeholder="请填写就诊日期" name="build_time" id="build_time" />
				</div>
				<div class="border-box">
					<label for="hospital">就诊医院</label>
					<input type="text" placeholder="例如：陕西省第一人民医院" name="hospital" id="hospital" />
				</div>-->
			</div>
			<p class="grayline"></p>
			<div id="describe">
				<!--<p class="sub-title">病情描述</p>
				<textarea rows="6" placeholder="请您在这里仔细的描述您的病情"></textarea>-->
			</div>
			<p class="grayline"></p>
			<div id="conclusion">
				<!--<p class="sub-title">医生结论</p>
				<textarea rows="6" placeholder="请您将医生的诊断认真的填写在此处"></textarea>-->
			</div>
			<p class="grayline"></p>
			<div id="picture">
				<p class="sub-title">图片附件</p>
				<div class="upload-box">
            		<!--<div class="upload">
            			<input type="file" accept="image/*" class="filetest" onchange="changefile(this)"/>     			
            		</div>-->
            	</div>
			</div>
			<a href="javascript:;" class="submit">提交</a>
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/common.js"></script>
		<script src="js/layer/layer.js" ></script>
		<script src="js/mobiscroll/mobiscroll.core-2.5.2.js"></script>
		<script src="js/mobiscroll/mobiscroll.core-2.5.2-zh.js"></script>
		<script src="js/mobiscroll/mobiscroll.datetime-2.5.1.js"></script>
		<script src="js/mobiscroll/mobiscroll.datetime-2.5.1-zh.js"></script>
		<script src="js/mobiscroll/mobiscroll.android-ics-2.5.2.js"></script>
		<script src="js/lib/exif.js" type="text/javascript"></script>
        <script src="js/lib/mobileFix.mini.js" type="text/javascript"></script>
		<script src="js/lrz.js" type="text/javascript"></script>
		<script>
		
		session = getCookie('session');
		session=session.substr(1,session.length-2);
		
		editid=$_GET['id'];
		console.log(editid);
		$.ajax({
			type:"post",
			url:commonUrl + 'api/stj/user_case/case_list'+versioninfo,
			async:false,
			data:{
				'ss':session,
				'case_id':editid
			},
			success:function(data){
				if(data.code==1){
					console.log(data);
					zdhtml="";
					zdhtml+='<p>'+data.result.title+'</p>';
					$(".sub-tit").html(zdhtml);
					html="";
					html+='<p class="sub-title">个人病历</p>';
					html+='<div class="border-box">';
					html+='	<label for="diagnosis">病情诊断</label>';
					html+='	<input type="text" name="diagnosis" id="diagnosis" value="'+data.result.title+'" />';
					html+='</div>';
					html+='<div class="border-box">';
					html+='	<label for="build_time">就诊日期</label>';
					html+='	<input type="text" name="build_time" id="build_time" />';
					html+='</div>';
					html+='<div class="border-box">';
					html+='	<label for="hospital">就诊医院</label>';
					html+='	<input type="text" value="'+data.result.hospital+'" name="hospital" id="hospital" />';
					html+='</div>';
					$("#illness").html(html);
					mshtml="";
					mshtml+='<p class="sub-title">病情描述</p>';
					mshtml+='<textarea rows="6" >'+data.result.user_desc+'</textarea>';
					$("#describe").html(mshtml);
					jlhtml="";
					jlhtml+='<p class="sub-title">医生结论</p>';
					jlhtml+='<textarea rows="6" >'+data.result.doctor_desc+'</textarea>';
					$("#conclusion").html(jlhtml);
					fjhtml="";
					imgnum=data.result.img.length;					
					for(var i=0;i<imgnum;i++){
						fjhtml+='<div class="picbox">';
						fjhtml+='	<img src="'+data.result.img[i].url+'" class="dqpic" />';
						fjhtml+='	<img class="del" src="image/delete.png" onclick="delImgs('+data.result.img[i].id+')" delimg="'+data.result.img[i].id+'" >';
						fjhtml+='</div>';
					}											
	            	fjhtml+='	<div class="upload">';
	            	fjhtml+='		<input type="file" accept="image/*" class="filetest" onchange="changefile(this)"/>';     			
	            	fjhtml+='	</div>';
					$(".upload-box").html(fjhtml);
					picT=$("#picture .picbox").text();
					
					if(picT==""){
						$("#picture .picbox").append('<p style="text-align: center;color: #4d4d4d;font-size: 15px;">暂无</p>');
					}
					time=data.result.time.substr(0,10);
					//时间
					$(function() {			
							var currYear = (new Date()).getFullYear();
							var opt = {};
							opt.date = {
								preset: 'date'
							};
							//opt.datetime = { preset : 'datetime', minDate: new Date(2012,3,10,9,22), maxDate: new Date(2014,7,30,15,44), stepMinute: 5  };
							opt.datetime = {
								preset: 'datetime'
							};
							opt.time = {
								preset: 'time'
							};
							opt.default = {
								theme: 'android-ics light', //皮肤样式
								display: 'modal', //显示方式 
								mode: 'scroller', //日期选择模式
								lang: 'zh',
								startYear: currYear - 10, //开始年份
								endYear: currYear + 10 //结束年份
							};   
							$("#build_time").val(time).scroller('destroy').scroller($.extend(opt['date'], opt['default']));
							var optDateTime = $.extend(opt['datetime'], opt['default']);
							var optTime = $.extend(opt['time'], opt['default']);
					});					
				}else if(data.code==1011){
					layer.msg('该用户登陆数据已过期，请重新登陆');
                	setTimeout("location.href='sign_in.php'",1000);
				}else{
					layer.msg(data.msg)
				}
			}
		});
		
		
		//压缩图片2
			function changefile(obj) {
                filetest = obj;
                layer.load(2, {time: 7*1000});
                lrz(filetest.files[0], {width: 400,quality:1}, function (result) {
                    // 你需要的数据都在这里，可以以字符串的形式传送base64给服务端转存为图片。
//                  console.log(result);
                    submitData={
                        base64_string:result.base64
                    };
                    //提交
                    $.ajax({
                        type: "POST",
                        url: commonUrl+'wxsite/upload.php',
                        data: submitData,
                        dataType: 'json',
                        success: function(data){
//                          console.log(data);
                            if(data.code==1){
                                layer.closeAll();
                                delhtml = '<img class="del" src="image/delete.png" onclick="delImg(this)">';
                                //插入图片 追加删除图片
                                $(filetest).parent(".upload").css({
                                    "background-image":"url('http://"+data.data+"')",
                                    "background-size":"contain"
                                }).append(delhtml).attr("org_img",'http://'+data.data);
                                $(filetest).remove();                               
                                html='';
                                html+='<div class="upload" org_img="">';
                                html+='		<input class="filetest"  onchange="changefile(this)" type="file" accept="image/*"/>';
                                html+='</div>';
                                $('.upload-box').append(html);
                            }else{
                            	clearInterval(setint);
//                              alert(data.msg);
                            }
                        }
                    });
                    
                    setint=setInterval(returns,7000);
                     function returns(){
                     	return false;
                     }
                });
            }
			//点击删除图片，删除图
            ifdel3 = 0;//防止出现2次del
            function delImg(obj){
            	lengthhh=$(".filetest").length;
                $(obj).parent(".upload").remove();
                if(ifdel3==0&&lengthhh==0){
                    html='';
                    html+='<div class="upload" org_img="">';
                    html+='		<input class="filetest"  onchange="changefile(this)" type="file" accept="image/*"/>';
                    html+='</div>';
                    $('.upload-box').append(html);
                    ifdel3 = 1;
                }else{
                    ifdel3 = 0;
                }
            }
		
			function delImgs(imgid){
				$.ajax({
					type:"post",
					url:commonUrl + 'api/stj/user_case/del_img'+versioninfo,
					data:{
						'id':imgid,
						'ss':session
					},
					success:function(ret){
						if(ret.code==1){
							console.log(ret);
							$(".picbox").each(function(){
								
								del=$(this).children(".del").attr("delimg");
								
								if(del==imgid){
									console.log(del);
									$(this).remove();
								}
							})
						}else if(ret.code==1011){
							layer.msg('该用户登陆数据已过期，请重新登陆');
		                	setTimeout("location.href='sign_in.php'",1000);
						}else{
							layer.msg(ret.msg)
						}
					}
				});
			}
		
		winW=$(window).width();
		bpW=$(".border-box label").width();
		$(".border-box input").width(winW-bpW-45);
		
		$(".submit").click(function(imgid){
			
			console.log(imgid);
			
			var diagnosis=$("#diagnosis").val();
			var build_time=$("#build_time").val();
			build_time=build_time+" 00:00:00";
			var hospital=$("#hospital").val();
			var describe=$("#describe textarea").val();
			var conclusion=$("#conclusion textarea").val();
			uplength=$(".upload").length;
			
			if(uplength==1){
				var img_id="";
			}else{
				var img_id="";				
				for(var i=0;i<uplength-1;i++){
					
					org_imgs=$(".upload").eq(i).attr("org_img");
					console.log(org_imgs);
					orgimg=$(".upload").eq(i);
					$.ajax({
						type:"post",
						url:commonUrl+'api/stj/user_case/add_img'+versioninfo,
						async:false,
						data:{
							'img_url':org_imgs,
							'ss':session
						},
						success:function(data){
							if(data.code==1){
//								console.log(data);
								img_ids=data.result.id;
								img_id+=img_ids+",";							
							}else if(data.code==1011){
								layer.msg('该用户登陆数据已过期，请重新登陆');
			                	setTimeout("location.href='sign_in.php'",1000);
							}else{
								layer.msg(data.msg)
							}	
							
						}
					});            
				}
			}
			
			var dels="";
			if(diagnosis==""||build_time==""||hospital==""||describe==""||conclusion==""){	                
					layer.msg("请填写完整");
			}else{
				img_id=img_id.substring(0,img_id.length-1);
				$.ajax({
					type:"post",
					url:commonUrl+'api/stj/user_case/add_case'+versioninfo,
					data:{
						'doctor_desc':conclusion,
						'hospital':hospital,
						'id':editid,
						'img_id':img_id,
						'ss':session,
						'time':build_time,
						'title':diagnosis,
						'user_desc':describe
					},
					success:function(ret){
						if(ret.code==1){
							console.log(ret);
							layer.msg("编辑成功");
							listnext=function(){
                    				location.href='illness_list.php';
                    			}
                    		setInterval(listnext,2000);
						}else{
							layer.msg(ret.msg)
						}
					}
				});
			}
		})
		
		</script>
		<style>
	        .layui-layer{
	            left:0;
	        }
	        .ui-loader{
	            display: none;
	        }
	    </style>
	</body>
</html>
	