<?php
// Start the engine
require_once(TEMPLATEPATH.'/lib/init.php');

// Setup the child theme
add_action('after_setup_theme','child_theme_setup');
function child_theme_setup() {
	
	// ** Backend **
	// Remove Unused Backend Pages
	add_action('admin_menu', 'be_remove_menus');
	
	/** Add support for custom background */
	add_custom_background();
	
	
	
	// Remove Unused Page Layouts
	//genesis_unregister_layout( 'full-width-content' );
	//genesis_unregister_layout( 'content-sidebar' );	
	//genesis_unregister_layout( 'sidebar-content' );
	//genesis_unregister_layout( 'content-sidebar-sidebar' );
	//genesis_unregister_layout( 'sidebar-sidebar-content' );
	//genesis_unregister_layout( 'sidebar-content-sidebar' );
	
	// Set up Post Types
	add_action( 'init', 'be_create_my_post_types' );	
	
	//Add post format support
	add_theme_support( 'post-formats', array( 'image', 'video', 'link' ) );
	//remove elements on certain post formats
		// Set up Taxonomies
	
	//add_action( 'init', 'be_create_my_taxonomies' );

	// Set up Meta Boxes
	add_action( 'init' , 'be_create_metaboxes' );

	// Setup Sidebars
	//unregister_sidebar('sidebar-alt');
	//genesis_register_sidebar(array('name' => 'Blog Sidebar', 'id' => 'blog-sidebar'));
	
	// Setup Shortcodes
	include_once( CHILD_DIR . '/lib/functions/shortcodes.php');
	
	// ** Frontend **		
	//Post Format templates 
	require_once('post_format_loop.php');
	// Load JavaScript Libraries
	add_action('get_header', 'creature_load_scripts');
	function creature_load_scripts() {
	    wp_enqueue_script('cycle', CHILD_URL.'/scripts/jquery.cycle.all.js', array('jquery'), '1', TRUE);
     wp_enqueue_script('scrollto', CHILD_URL.'/scripts/jquery.scrollTo.js', array('jquery'),'1', TRUE);
     
      wp_enqueue_script('localscroll', CHILD_URL.'/scripts/jquery.localscroll-1.2.7-min.js', array('jquery', 'scrollto'),'1', TRUE);
       wp_enqueue_script('menuscroll', CHILD_URL.'/scripts/menu_scroll.js', array('jquery'),'1', TRUE);	
      wp_enqueue_script('slider_box', CHILD_URL.'/scripts/slider_box.js', array('jquery'),'1', TRUE);  
      wp_enqueue_script('dynamic_content', CHILD_URL.'/scripts/dynamic_content.js', array('jquery'),'1', TRUE);    
      wp_enqueue_script('disable_links', CHILD_URL.'/scripts/disable_links.js', array('jquery'),'1', TRUE);    
      wp_enqueue_script('fade_in', CHILD_URL.'/scripts/fade_in.js', array('jquery'),'1', TRUE);    
	    
	}
	//Image Sizes 
	add_image_size('slider', '960px', '600px', true); //Image size for the slider box
	// Remove Edit link
	add_filter( 'edit_post_link', 'be_edit_post_link' );

	// Remove Breadcrumbs
	remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
}

// ** Backend Functions ** //

function be_remove_menus () {
	global $menu;
	$restricted = array(__('Links'));
	// Example:
	//$restricted = array(__('Dashboard'), __('Posts'), __('Media'), __('Links'), __('Pages'), __('Appearance'), __('Tools'), __('Users'), __('Settings'), __('Comments'), __('Plugins'));
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
	}
}

function be_create_my_post_types() {
	register_post_type( 'project',
		array(
			'labels' => array(
				'name' => __( 'Projects' ),
				'singular_name' => __( 'Project' )
			),
			'public' => true,
			'supports' => array('title', 'excerpt', 'editor', 'thumbnail'),
		)
	);
}

function be_create_my_taxonomies() {
	/*register_taxonomy( 
		'poc', 
		'post', 
		array( 
			'hierarchical' => true, 
			'labels' => array(
				'name' => 'Points of Contact',
				'singlular_name' => 'Point of Contact'
			),
			'query_var' => true, 
			'rewrite' => true 
		) 
	);*/
}


function be_create_metaboxes() {
	$prefix = 'be_';
	$meta_boxes = array();

	$meta_boxes[] = array(
    	'id' => 'project-options',
	    'title' => 'Project Options',
	    'pages' => array('project'), // post type
		'context' => 'normal',
		'priority' => 'low',
		'show_names' => true, // Show field names left of input
		'fields' => array(
			array(
				'name' => 'Instructions',
				'desc' => 'In the right column upload a featured image. Make sure this image is at least 900x360px wide. Then fill out the information below.',
				'type' => 'title',
			),
			array(
		        'name' => 'Display Info',
		        'desc' => 'Show Title and Excerpt from above',
	    	    'id' => 'show_info',
	        	'type' => 'checkbox'
			)		),
	);
$meta_boxes[] = array(
		'id' => 'video-box',
	    'title' => 'Video Attachments',
	    'pages' => array('project', 'post'), // post type
		'context' => 'side',
		'priority' => 'high',
		'show_names' => true, // Show field names left of input
		'fields' => array(
			array(
				'name' => 'Video URLs',
				'desc' => 'Copy and Paste the URLs from the Youtube.com or Vimeo.com Video pages.',
				'type' => 'title',
			),
			array(
		        'name' => 'Youtube',
		        'desc' => 'be sure to include the full URL, begining with http://',
	    	    'id' => 'youtube_video',
	        	'type' => 'text'
			),
			array(
			    'name' => 'Vimeo',
			    'desc' => 'be sure to include the full URL, begining with http://',
			    'id' => 'vimeo_video',
				'type' => 'text'
			)		),
	);
	 	 	
 	require_once(CHILD_DIR . '/lib/metabox/init.php'); 
}



// ** Frontend Functions ** //

function be_edit_post_link($link) {
	return '';
}

// ** Unhooked Functions ** //
