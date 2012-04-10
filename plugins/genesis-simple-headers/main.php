<?php
/*
Plugin Name: Genesis Simple Headers
Plugin URI: http://9seeds.com/plugins/
Description: Use the WordPress header functionality to upload custom logos or headers
Version: 1.0.1
Author: 9seeds, LLC
Author URI: http://9seeds.com/
*/

// Define our constants
define('SIMPLEHEADERS_PLUGIN_DIR', dirname(__FILE__));

require_once ( SIMPLEHEADERS_PLUGIN_DIR.'/functions.php' );

// grab theme info
$theme_info = get_theme_data(get_bloginfo('stylesheet_url'));

$current_theme = $theme_info['Name'];


switch ($current_theme) {
	case 'The New Creatures';
		DEFINE ( 'HEADER_IMAGE_WIDTH', '960' );
		DEFINE ( 'HEADER_IMAGE_HEIGHT', '600' );
		DEFINE ( 'THEME_CSS_VALUE', '.header-image #header #title-area' );
		DEFINE ( 'BACKGROUND_TYPE', 'background' );
	break;

	case 'Manhattan Child Theme';
	case 'Sleek Child Theme';
		DEFINE ( 'HEADER_IMAGE_WIDTH', '400' );
		DEFINE ( 'HEADER_IMAGE_HEIGHT', '70' );
		DEFINE ( 'THEME_CSS_VALUE', '.header-image #header #title-area' );
		DEFINE ( 'BACKGROUND_TYPE', 'background' );
	break;

	case 'Landscape Child Theme';
	case 'Platinum Child Theme';
	case 'Serenity Child Theme';
	case 'Streamline Child Theme';
	case 'Sample Child Theme';
		DEFINE ( 'HEADER_IMAGE_WIDTH', '400' );
		DEFINE ( 'HEADER_IMAGE_HEIGHT', '80' );
		DEFINE ( 'THEME_CSS_VALUE', '.header-image #header #title-area' );
		DEFINE ( 'BACKGROUND_TYPE', 'background' );
	break;

	case 'Magazine Child Theme';
		DEFINE ( 'HEADER_IMAGE_WIDTH', '400' );
		DEFINE ( 'HEADER_IMAGE_HEIGHT', '90' );
		DEFINE ( 'THEME_CSS_VALUE', '.header-image #header #title-area' );
		DEFINE ( 'BACKGROUND_TYPE', 'background' );
	break;

	case 'Agency Child Theme';
	case 'Amped Child Theme';
	case 'Going Green Child Theme';
	case 'Venture Child Theme';
		DEFINE ( 'HEADER_IMAGE_WIDTH', '400' );
		DEFINE ( 'HEADER_IMAGE_HEIGHT', '100' );
		DEFINE ( 'THEME_CSS_VALUE', '.header-image #header #title-area' );
		DEFINE ( 'BACKGROUND_TYPE', 'background' );
	break;

	case 'Education Child Theme';
	case 'Freelance Child Theme';
	case 'Lexicon Child Theme';
		DEFINE ( 'HEADER_IMAGE_WIDTH', '400' );
		DEFINE ( 'HEADER_IMAGE_HEIGHT', '110' );
		DEFINE ( 'THEME_CSS_VALUE', '.header-image #header #title-area' );
		DEFINE ( 'BACKGROUND_TYPE', 'background' );
	break;

	case 'Delicious Child Theme';
	case 'Education Child Theme';
		DEFINE ( 'HEADER_IMAGE_WIDTH', '400' );
		DEFINE ( 'HEADER_IMAGE_HEIGHT', '115' );
		DEFINE ( 'THEME_CSS_VALUE', '.header-image #header #title-area' );
		DEFINE ( 'BACKGROUND_TYPE', 'background' );
	break;

	case 'Crystal Child Theme';
	case 'Executive Child Theme';
	case 'Metric Child Theme';
	case 'Mocha Child Theme';
	case 'Outreach Child Theme';
		DEFINE ( 'HEADER_IMAGE_WIDTH', '400' );
		DEFINE ( 'HEADER_IMAGE_HEIGHT', '120' );
		DEFINE ( 'THEME_CSS_VALUE', '.header-image #header #title-area' );
		DEFINE ( 'BACKGROUND_TYPE', 'background' );
	break;

	case 'Enterprise Child Theme';
		DEFINE ( 'HEADER_IMAGE_WIDTH', '400' );
		DEFINE ( 'HEADER_IMAGE_HEIGHT', '125' );
		DEFINE ( 'THEME_CSS_VALUE', '.header-image #header #title-area' );
		DEFINE ( 'BACKGROUND_TYPE', 'background' );
	break;

	case 'Pixel Happy Child Theme';
		DEFINE ( 'HEADER_IMAGE_WIDTH', '500' );
		DEFINE ( 'HEADER_IMAGE_HEIGHT', '120' );
		DEFINE ( 'THEME_CSS_VALUE', '.header-image #title-area, .header-image #title-area #title, .header-image #title-area #title a' );
		DEFINE ( 'BACKGROUND_TYPE', 'background' );
	break;

	case 'AgentPress Child Theme';
		DEFINE ( 'HEADER_IMAGE_WIDTH', '570' );
		DEFINE ( 'HEADER_IMAGE_HEIGHT', '115' );
		DEFINE ( 'THEME_CSS_VALUE', '.header-image #header #title-area' );
		DEFINE ( 'BACKGROUND_TYPE', 'background' );
	break;

	case 'News Child Theme';
		DEFINE ( 'HEADER_IMAGE_WIDTH', '940' );
		DEFINE ( 'HEADER_IMAGE_HEIGHT', '80' );
		DEFINE ( 'THEME_CSS_VALUE', '#header' );
		DEFINE ( 'BACKGROUND_TYPE', 'background' );
	break;

	case 'Bee Crafty Child Theme';
		DEFINE ( 'HEADER_IMAGE_WIDTH', '960' );
		DEFINE ( 'HEADER_IMAGE_HEIGHT', '180' );
		DEFINE ( 'THEME_CSS_VALUE', '#header' );
		DEFINE ( 'BACKGROUND_TYPE', 'background' );
	break;

	case 'Pretty Young Thing Child Theme';
		DEFINE ( 'HEADER_IMAGE_WIDTH', '960' );
		DEFINE ( 'HEADER_IMAGE_HEIGHT', '240' );
		DEFINE ( 'THEME_CSS_VALUE', '#header' );
		DEFINE ( 'BACKGROUND_TYPE', 'background' );
	break;

	case 'Church Child Theme';
	case 'Expose Child Theme';
	case 'Focus Child Theme';
	case 'Lifestyle Child Theme';
		DEFINE ( 'HEADER_IMAGE_WIDTH', '960' );
		DEFINE ( 'HEADER_IMAGE_HEIGHT', '100' );
		DEFINE ( 'THEME_CSS_VALUE', '#header' );
		DEFINE ( 'BACKGROUND_TYPE', 'background' );
	break;

	case 'Corporate Child Theme';
		DEFINE ( 'HEADER_IMAGE_WIDTH', '960' );
		DEFINE ( 'HEADER_IMAGE_HEIGHT', '120' );
		DEFINE ( 'THEME_CSS_VALUE', '#header' );
		DEFINE ( 'BACKGROUND_TYPE', 'background' );
	break;

	case 'Social Eyes Child Theme';
		DEFINE ( 'HEADER_IMAGE_WIDTH', '960' );
		DEFINE ( 'HEADER_IMAGE_HEIGHT', '150' );
		DEFINE ( 'THEME_CSS_VALUE', '#header' );
		DEFINE ( 'BACKGROUND_TYPE', 'background' );
	break;
}

// Don't spit out CSS unless we've set a width/height
if (defined('HEADER_IMAGE_WIDTH') && defined('HEADER_IMAGE_HEIGHT')) {
	add_custom_image_header('simpleheader_style', 'admin_simpleheader_style');
	
	if(!defined( 'HEADER_TEXTCOLOR' )) {
		DEFINE ( 'HEADER_TEXTCOLOR', '#000' );
	}
}
?>