jQuery(document).ready(function($){
$(window).load(function(){
$('#project_slider').cycle({ 
    fx:      'fade', 
    speed:  'fast', 
        timeout: 3000, 
        containerResize: 1,
        //pager:  '#project_pager', 
                 });
                   $('#wrap').localScroll();
                 });
                 });
