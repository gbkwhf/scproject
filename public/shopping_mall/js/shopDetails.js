
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
	