var tempScrollTop, currentScrollTop = 0; //first set the scroll positions to 0
jQuery(window).scroll(function(){ //execute this function every time the window is scrolled
currentScrollTop = jQuery(window).scrollTop(); 
jQuery('#nav').animate({'top': currentScrollTop}, {duration:100,queue:false});

//set the current scroll to the position of the wrap element

/*if (tempScrollTop < currentScrollTop ) {
jQuery('#nav').css({'position' : 'absolute', 'width' : '180px', 'box-shadow' :'inset 0 0 12px rgba(0,0,0,.7)'});
jQuery('#nav li.top_link').css({'display':'block'});
jQuery('.menu-item').css({'width' : '95%', 'clear' : 'both'});
}
else if (currentScrollTop < 20) {
	jQuery('#nav').css({'position' : '', 'width' : '98%', 'box-shadow': 'none'});
	jQuery('.menu-item').css({'width' : 'auto', 'clear' : 'none'});
	jQuery('#nav li.top_link').css({'display':'none'});
	
	
}*/
//the current is greater then the marker, you are scrolling down
	//jQuery('#nav').animate({'top': menuPosition}, 'fast');//do this when scrolling down
//else if (tempScrollTop > currentScrollTop ) //if the current is less than the marker, you are scrolling up
//jQuery('#nav').animate({'top': menuPosition}, 'fast');//do this when scrolling up
tempScrollTop = currentScrollTop; //set the marker to the current value
});