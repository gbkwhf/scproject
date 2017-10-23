<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>搜索授权码</title>
		<link rel="stylesheet" href="css/common.css">
		<link rel="stylesheet" href="css/jquery.page.css">
		<link rel="stylesheet" href="css/illness_cases.css">
        <style>
            .content{
                text-align: center;
            }
            .bg{
                background: url("images/bg1.png");
                border-top: 1px solid #b2bbbe;
            }
            .title{
                padding:169px 0 106px 0;
                color:#21c0d5;
                font-size: 39px;
            }
            .searchBox{
                width: 880px;
                margin: 0 auto;
            }
            .inputCode{
                float: left;
                width: 713px;
                border: 1px solid #21c0d5;
                height: 56px;
                line-height: 56px;
                font-size: 22px;
                padding:0  20px;
                color:#585858;
            }
            .searchBtn{
                float: left;
                width: 79px;
                line-height: 58px;
                color:#fff;
                padding-left: 40px;
                font-size: 22px;
                background: url("images/searchbtn.png") 24px 19px no-repeat #21c0d5;
                cursor: pointer;
            }
            .showErr{
                padding:116px 0 122px 0;
                color:#ff0000;
                font-size: 13px;
                visibility: hidden;
            }
        </style>
	</head>
	<body>
		<?php include 'header.php' ?>
        <div class="bg">
            <div class="content" id="searchPage">
                <div class="title">查询病历</div>
                <div class="searchBox">
                    <input class="inputCode" placeholder="请输入授权码" maxlength="10" type="text"/>
                    <div class="searchBtn">搜索</div>
                    <div class="clearfix"></div>
                </div>
                <div class="showErr"><img src="images/warn.png" alt="" style="position: relative;top:3px;left: -5px"/><span style="color:#ff0000" class="warnp">请输入正确的授权码</span></div>
            	
            </div>
            <!--搜索之后的页面-->
            <div class="content" id="listPage">
        		<div class="showlist"></div>
        		<div id="page" style="visibility: hidden;"></div>
            </div>
        </div>
		<?php include 'footer.php' ?>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
        <script type="text/javascript" src="js/layer/layer.js"></script>
        <script type="text/javascript" src="js/function.js"></script>
        <script type="text/javascript" src="js/jquery.page.js"></script>
		<script>
			$('.searchBtn').click(function(){
				inputCode=$('.inputCode').val();
				if(inputCode){
					layer.load(2);
					$('#searchPage').css('display','none');
					$('#listPage').css('display','block');
					medlist_page=0;
					//加载首页数据
					$.ajax({
				    	type:'POST',
				    	url:commonUrl + 'api/stj/user_case/case_list'+versioninfo,
				    	async:false,
				    	data:{
				    		'code':inputCode,
                            'home':1
				    	},
				    	success:function(data){
					    	layer.closeAll();
//					    	console.log(data);
		                  	if(data.code == 1){
                                if(data.result.list.length!=0){
		                    		medlist_page=Math.ceil(data.result.count/10);
		                    		$('#page').css('visibility','visible');
		                    		html='';
		                    		html+='<table class="medRecordList" cellpadding="0" cellspacing="0">';
			                    	html+='<tr>';
			                    	html+='	<td class="first">病情</td>';
			                    	html+='	<td class="second">就诊医院</td>';
			                    	html+='	<td class="third">就诊时间</td>';
			                    	html+='	<td>详情</td>';
			                    	html+='</tr>';
			                    	for(var i=0;i<data.result.list.length;i++){
			                    		id=data.result.list[i].id;
			                    		html+='<tr>';
				                    	html+='	<td class="first">'+data.result.list[i].title+'</td>';
				                    	html+='	<td class="second">'+data.result.list[i].hospital+'</td>';
				                    	html+='	<td class="third">'+(data.result.list[i].time).slice(0,10)+'</td>';
				                    	html+='	<td><a href="report_detail.php?id='+id+'&code='+inputCode+'">查看</a></td>';
				                    	html+='</tr>';
			                    	}
			                    	html+='</table>';
			                    	$('.showlist').html(html);
			                    	$('.medRecordList tr td:last-child').css('color',"#21c0d5");
							    	$('.medRecordList tr:even').css('background','#e8e9e9');
							    	$('.medRecordList tr:first').css('background','#21c0d5');
							    	$('.medRecordList tr:first td').css('color',"#fff");
		                    	}else{
		                    		layer.msg('暂无病例');
		                    	}
		                	}else{
                                $('.warnp').html('请输入正确的授权码');
                                $('.showErr').css('visibility','visible');
                                setTimeout("$('.showErr').css('visibility','hidden')",2000);
		                	}
		              	}
		          	});
		          	$("#page").Page({
				        totalPages: medlist_page,
				        liNums: 7,
				        activeClass: 'activP', 
				        callBack : function(page){
				          	console.log(page);
				          	ajaxrequest(page);
				        }
		      		});
				}else{
                    $('.warnp').html('请填写授权码');
                    $('.showErr').css('visibility','visible');
                    setTimeout("$('.showErr').css('visibility','hidden')",2000);
                }

			});
			
            function ajaxrequest(num){
				$.ajax({
			    	type:'POST',
			    	url:commonUrl + 'api/stj/user_case/case_list'+versioninfo,
			    	data:{
			    		'code':inputCode,
			    		'page':num
			    	},
			    	success:function(data){
//			    		console.log(data);
	                    if(data.code == 1){
	                    	shows(data);
	                  	}else{
	                  		layer.msg(data.msg);
	                  	}
	                }
	           	});
			}
			function shows(obj){
				html='';
				html+='<table class="medRecordList" cellpadding="0" cellspacing="0">';
            	html+='<tr>';
            	html+='	<td class="first">病情</td>';
            	html+='	<td class="second">就诊医院</td>';
            	html+='	<td class="third">就诊时间</td>';
            	html+='	<td>详情</td>';
            	html+='</tr>';
            	for(var i=0;i<obj.result.length;i++){
            		id=obj.result[i].id;
            		html+='<tr>';
                	html+='	<td class="first">'+obj.result[i].title+'</td>';
                	html+='	<td class="second">'+obj.result[i].hospital+'</td>';
                	html+='	<td class="third">'+(obj.result[i].time).slice(0,10)+'</td>';
                	html+='	<td><a href="report_detail.php?id='+id+'&code='+inputCode+'">查看</a></td>';
                	html+='</tr>';
            	}
            	html+='</table>';
            	$('.showlist').html(html);
            	$('.medRecordList tr td:last-child').css('color',"#21c0d5");
		    	$('.medRecordList tr:even').css('background','#e8e9e9');
		    	$('.medRecordList tr:first').css('background','#21c0d5');
		    	$('.medRecordList tr:first td').css('color',"#fff");
			}
		</script>
	</body>
</html>
