$(function() {
	//swiper插件实现轮播图
	var mySwiper = new Swiper('.swiper-container', {
		//						paginationType: 'fraction',//分页器
		autoplay: 1500, //可选选项，自动滑动
		loop: true,
		pagination: '.swiper-pagination',
	});
})