jQuery(document).ready(function () {

	jQuery("#thoora-wrapper .thoora-scroll").scrollTo(jQuery("#thoora-wrapper .thoora0"));
	var thooraScroll = 0;
	var itemsPerView = 3;//number of boxes you see on the page at once
	var currentCount = jQuery("#thoora-wrapper .th-custom-container").length;
	var maxScroll = currentCount - itemsPerView;
	
	if (currentCount > itemsPerView) {
	
		var arrowUp = jQuery("#thoora-wrapper .thoora-up");
		var arrowDown = jQuery("#thoora-wrapper .thoora-down");
		
	
		
		function showArrow(myObj) {
			myObj.toggleClass('thoora-arrowHide');
			myObj.css('opacity','1');
			
		}
		
		function hideArrow(myObj) {
			myObj.toggleClass('thoora-arrowHide').animate({opacity:0},100);
		}
		
		
		if (maxScroll > 0){	
			showArrow(arrowDown);
		}

		
		jQuery("#thoora-wrapper .thoora-down").click(function(){
			
			if (thooraScroll != maxScroll) {
				thooraScroll ++;
				jQuery("#thoora-wrapper .thoora-scroll").scrollTo(jQuery("#thoora-wrapper .thoora"+thooraScroll), 400);
				if (thooraScroll == maxScroll)hideArrow(arrowDown);
				if (arrowUp.hasClass('thoora-arrowHide')){
					showArrow(arrowUp);
				} 
			}
			
				
		});
		jQuery("#thoora-wrapper .thoora-up").click(function(){
			if (thooraScroll > 0) {
				thooraScroll --;
				jQuery("#thoora-wrapper .thoora-scroll").scrollTo(jQuery("#thoora-wrapper .thoora"+thooraScroll), 400);
				if (thooraScroll == 0) hideArrow(arrowUp);
				if (arrowDown.hasClass('thoora-arrowHide')){
					showArrow(arrowDown);
				} 
			}
				
				
		});
		
		jQuery("#thoora-wrapper .thoora-button").mousedown(function(){
			jQuery(this).toggleClass('pressed');
		}).mouseup(function(){
			jQuery(this).toggleClass('pressed');
		})
	}
});
