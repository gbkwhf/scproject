<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>家居家装分类列表</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/common.css"/>
    <link rel="stylesheet" href="css/classification.css">
</head>
<body>
 <div class="content">
     <ul class="left_nav">
         <li class="curr_tab">日用家电</li>
         <li> 品质家电</li>
         <li> 家居百货</li>
         <li class="last_li"> 养生旅游</li>
        
     </ul>
     <div class="right_content">
         <ul style="display: block;">
             <li>
                 <img src="images/rice.png" alt="">
                 <em>进口大米</em>
                 <i>¥18.00</i>
             </li>
             <li>
                 <img src="images/rice.png" alt="">
                 <em>澳大利亚大米</em>
                 <i>¥18.00</i>
             </li>
             <li>
                 <img src="images/rice.png" alt="">
                 <em>澳大利亚大米</em>
                 <i>¥18.00</i>
             </li>
             <li>
                 <img src="images/rice.png" alt="">
                 <em>澳大利亚大米</em>
                 <i>¥18.00</i>
             </li>
         </ul>
         <ul>
             <li>
                 <img src="images/rice.png" alt="">
                 <em>马来西亚</em>
                 <i>¥18.00</i>
             </li>
             <li>
                 <img src="images/rice.png" alt="">
                 <em>马来西亚</em>
                 <i>¥18.00</i>
             </li>
             <li>
                 <img src="images/rice.png" alt="">
                 <em>马来西亚</em>
                 <i>¥18.00</i>
             </li>
             <li>
                 <img src="images/rice.png" alt="">
                 <em>马来西亚</em>
                 <i>¥18.00</i>
             </li>
            
         </ul>
         <ul>
             <li>
                 <img src="images/rice.png" alt="">
                 <em>澳大利亚大米</em>
                 <i>¥18.00</i>
             </li>
             <li>
                 <img src="images/rice.png" alt="">
                 <em>澳大利亚大米</em>
                 <i>¥18.00</i>
             </li>
             <li>
                  <img src="images/rice.png" alt="">
                 <em>澳大利亚大米</em>
                 <i>¥18.00</i>
             </li>
             <li>
                 <img src="images/rice.png" alt="">
                 <em>澳大利亚大米</em>
                 <i>¥18.00</i>
             </li>
           
         </ul>
         <ul>
             <li>
                  <img src="images/rice.png" alt="">
                 <em>马来西亚</em>
                 <i>¥18.00</i>
             </li>
             <li>
                 <img src="images/rice.png" alt="">
                 <em>马来西亚</em>
                 <i>¥18.00</i>
             </li>
             <li>
                  <img src="images/rice.png" alt="">
                 <em>马来西亚</em>
                 <i>¥18.00</i>
             </li>
             <li>
                  <img src="images/rice.png" alt="">
                 <em>马来西亚</em>
                 <i>¥18.00</i>
             </li>
            

         </ul>
        
     </div>
 </div>

</body>
	<script src="js/config.js"></script>
	<script src="js/jquery.min.js"></script>
	<script>
	     var winH=$(window).width();
	     var leftnavH=$(".left_nav").width();
	     $(".right_content").width(winH-leftnavH-24);
	     $(".left_nav>li").click(function(){
	        $(".left_nav>li").not(".last_li").css("border-bottom","none");
	         $(this).prev("li").css("border-bottom","1px solid #e6e6e6");
	         var index=$(this).index();
	         $(this).addClass("curr_tab").siblings("li").removeClass("curr_tab");
	         $(".right_content").children("ul").eq(index).show().siblings("ul").hide();
	     });
	     //分类项目过长，内容滚动到顶部
	    $(".left_nav>li").click(function(){ 	
	    	$("html,body").animate({scrollTop:0},800);
	    	
	    });
	 </script>
</html>