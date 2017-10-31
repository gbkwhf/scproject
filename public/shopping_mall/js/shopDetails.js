$(function(){
    var mySwiper = new Swiper('.swiper-container', {
        autoplayDisableOnInteraction : false,
//      autoplay: 1500,//可选选项，自动滑动
        paginationClickable:true,
        paginationType : 'fraction',
        loop: true,
        pagination: '.swiper-pagination'

    });
});
$(window).scroll(function(){
		var top = $(document).scrollTop();
        var h = $(window).height();
		$('.aa').each(function(i,v){
			console.log($(this));
			var height = $(this).offset().top; 
			console.log(i);
			if(top + h/2> height){
			
				$('.shopTitle a:eq('+i+')').addClass('dd');
				$('.shopTitle a:eq('+i+')').siblings().removeClass('dd');
			}
			
		});
		
	});