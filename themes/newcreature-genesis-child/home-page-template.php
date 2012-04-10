<?php
/*
Template Name: Home Page
*/
/** Remove the post meta function */
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );
 
// Remove Breadcrumbs
remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');

 
// Remove Footer
remove_action('genesis_footer', 'genesis_do_footer');
remove_action('genesis_footer', 'genesis_footer_markup_open', 5);
remove_action('genesis_footer', 'genesis_footer_markup_close', 15);

//add_action('genesis_before_content', 'creature_slider');
function creature_slider(){
get_template_part('slider');
} 
add_action('genesis_after_content', 'project_grid');
function project_grid(){
get_template_part('grid', 'project');
}
add_action('genesis_after_content', 'creature_post_grid');
function creature_post_grid(){
get_template_part('grid');
}
//add_action('genesis_after_content','creature_authorbox');
function creature_authorbox(){
get_template_part('authorbox');
}
add_action('genesis_after_content', 'page_block');
function page_block(){
get_template_part('content_block', 'page');
}
add_action('genesis_after', 'footer_image');
function footer_image(){
get_template_part('footer_image');
}
 
 
    genesis();