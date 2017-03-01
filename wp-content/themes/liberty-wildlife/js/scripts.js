jQuery(function($) {
	$(window).scroll(function() {    
	    var scroll = $(window).scrollTop();

	    if (scroll >= 200) {
	        $('.av-section-bottom-logo').addClass('fixed animated slideInDown');
	    } else {
	        $('.av-section-bottom-logo').removeClass('fixed animated slideInDown');
	    }
	});
});