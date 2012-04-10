<?php
/*
Plugin Name: Genesis Multiple Column Content Classes
Plugin URI: http://icustomizethesis.com/content-column-classes
Description: With this plugin you can create upto 6 column content on your WordPress blog/website.
Version: 1.1
Author: Puneet Sahalot
Author URI: http://icustomizethesis.com/
*/

function content_column_classes(){
    $content_column_classes = get_option('content_column_classes');
    if($content_column_classes=='1'){
        if ( !defined('WP_CONTENT_URL') ) define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
        $plugin_url = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__));
        echo '<link rel="stylesheet" href="'.$plugin_url.'/content-column-classes.css"'.' type="text/css" media="screen" />';
    }
}

function activate_content_column_classes(){
        add_option('content_column_classes','1','Activate the Plugin');
}

function deactivate_content_column_classes(){
    delete_option('content_column_classes');
}

add_action('wp_head', 'content_column_classes');

register_activation_hook(__FILE__,'activate_content_column_classes');
register_deactivation_hook(__FILE__,'deactivate_content_column_classes');

?>