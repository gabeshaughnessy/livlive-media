jQuery(document).ready(function($){
	$(window).load(function(){
		$('.project_content').toggle();//hide the project content 
		
		$('.project_thumb_link').click(function(){
		
				
		$(this).next('.project_content').slideDown('slow');
		
		$(this).children('.project_description').fadeOut('slow');
		
		$(this).parent().siblings().children('.project_content').slideUp('slow');
		
		$(this).parent().siblings().children().children('.project_description').fadeIn('slow');
				return false;
		});
			});
});