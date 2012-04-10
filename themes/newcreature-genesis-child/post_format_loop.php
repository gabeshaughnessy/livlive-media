<?php 
//from nick the geek http://designsbynickthegeek.com/tutorials/changing-the-gallery-post-format-output
add_action( 'genesis_before_post', 'creatures_remove_elements' );
 
/**
 * If post has post format, remove the title, post info, and post meta.
 * If post does not have post format, then it is a default post. Add
 * title, post info, and post meta back.
 *
 * @since 1.0
 */
 function creature_link(){ 
global $post;
 ?>
 <a href="<?php echo get_the_excerpt($post->ID); ?>" rel="shadow_box"><?php the_title(); ?></a><?php
 }
function creatures_remove_elements() {
 
    // Setup Gallery Post Format
    if ( 'gallery' == get_post_format() && ! is_single() ) {
        remove_action( 'genesis_post_title', 'genesis_do_post_title' );
        remove_action( 'genesis_before_post_content', 'genesis_post_info' );
        remove_action( 'genesis_after_post_content', 'genesis_post_meta' );
        remove_action( 'genesis_post_content', 'genesis_do_post_content' );
        remove_action( 'genesis_post_content', 'the_content' );
        add_action( 'genesis_post_content', 'genesis_do_post_image' );
    } 
    elseif ( 'link' == get_post_format() && ! is_single() ) {
        remove_action( 'genesis_post_title', 'genesis_do_post_title' );
        remove_action( 'genesis_before_post_content', 'genesis_post_info' );
        remove_action( 'genesis_after_post_content', 'genesis_post_meta' );
        remove_action( 'genesis_post_content', 'genesis_do_post_image' );
        remove_action( 'genesis_post_content', 'genesis_do_post_content' );
        remove_action( 'genesis_post_content', 'the_content' );
        add_action( 'genesis_post_title', 'creature_link' );
      
    
           } 
	elseif ( 'video' == get_post_format() && ! is_single() ) {
	   //remove_action( 'genesis_post_title', 'genesis_do_post_title' );
	    remove_action( 'genesis_before_post_content', 'genesis_post_info' );
	    remove_action( 'genesis_after_post_content', 'genesis_post_meta' );
	   remove_action( 'genesis_post_title', 'creature_link' );
	  add_action( 'genesis_post_content', 'genesis_do_post_content' );
	  add_action( 'genesis_post_content', 'the_content' );
	   
	} 
	elseif ( 'video' == get_post_format() && ! is_single() ) {
	    remove_action( 'genesis_post_title', 'genesis_do_post_title' );
	    remove_action( 'genesis_before_post_content', 'genesis_post_info' );
	    remove_action( 'genesis_after_post_content', 'genesis_post_meta' );
	    remove_action( 'genesis_post_content', 'genesis_do_post_content' );
	    remove_action( 'genesis_post_content', 'the_content' );
	    add_action( 'genesis_post_content', 'genesis_do_post_image' );
	} 
    // Remove if post has format
    elseif ( get_post_format() && ! is_single() ) {
        remove_action( 'genesis_post_title', 'genesis_do_post_title' );
        remove_action( 'genesis_before_post_content', 'genesis_post_info' );
        remove_action( 'genesis_after_post_content', 'genesis_post_meta' );
        remove_action( 'genesis_post_content', 'genesis_do_post_image' );
        remove_action( 'genesis_post_content', 'genesis_do_post_content' );
        add_action( 'genesis_post_content', 'the_content' );
    }
    elseif (is_page('splash-page')){
    remove_action('genesis_post_title', 'genesis_do_post_title');//We're not using the title for this post type
    remove_action( 'genesis_before_post_content', 'genesis_post_info' );
    }
 
    // Add back, as post has no format
    else{
        add_action( 'genesis_post_title', 'genesis_do_post_title' );
        //add_action( 'genesis_before_post_content', 'genesis_post_info' );
        //add_action( 'genesis_after_post_content', 'genesis_post_meta' );
        add_action( 'genesis_post_content', 'genesis_do_post_image' );
        add_action( 'genesis_post_content', 'genesis_do_post_content' );
       // remove_action( 'genesis_post_content', 'the_content' );
         remove_action( 'genesis_post_title', 'creature_link' );
    }
}