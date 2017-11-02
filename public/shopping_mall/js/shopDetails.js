$(function(){
    var mySwiper = new Swiper('.swiper-container', {
        autoplayDisableOnInteraction : false,
//      autoplay: 1500,//可选选项，自动滑动
        paginationClickable:true,
        paginationType : 'fraction',
        loop: true,
        pagination: '.swiper-pagination',
        observer:true,//修改swiper自己或子元素时，自动初始化swiper 
		observeParents:false,//修改swiper的父元素时，自动初始化swiper 
		onSlideChangeEnd: function(swiper){ 
　　　	swiper.update();  
　　	　	mySwiper.startAutoplay();
　　 	 	mySwiper.reLoop();  
		}

        

    });
    
});
$(window).scroll(function(){
		var top = $(document).scrollTop();
        var h = $(window).height();
		$('.aa').each(function(i,v){
		
			var height = $(this).offset().top; 
			
			if(top + h/2> height){
			
				$('.shopTitle a:eq('+i+')').addClass('dd');
				$('.shopTitle a:eq('+i+')').siblings().removeClass('dd');
			}
			
		});
		
	});
	