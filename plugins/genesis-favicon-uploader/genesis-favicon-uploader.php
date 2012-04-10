<?php
/*
Plugin Name: Genesis Favicon uploader
Plugin URI: http://genesistutorials.com/plug-ins/genesis-post-teasers/
Description: Upload your own favicon!
Version: .1.1b
Author: Christopher Cochran
Author URI: http://christophercochran.me
*/

register_activation_hook(__FILE__, 'favicon_up_activation_check');
function favicon_up_activation_check() {
		
		$theme_info = get_theme_data(TEMPLATEPATH.'/style.css');
		
			// need to find a way to check active themes is MultiSites	- This does not work in new 3.1 network panel.	
			if( basename(TEMPLATEPATH) != 'genesis' ) {
				deactivate_plugins(plugin_basename(__FILE__)); // Deactivate ourself
				wp_die('Sorry, you can\'t activate unless you have installed <a href="http://www.studiopress.com/themes/genesis">Genesis</a>');
			}
			
}

add_action('admin_menu', 'favicon_up_settings_init', 15);
function favicon_up_settings_init() {
	add_submenu_page('genesis', __('Upload Favicon','favicon_up'), __('Upload Favicon','favicon_up'), 'manage_options', 'upload-favicon', 'favicon_upload_settings_admin');
}

add_action('wp', 'favicon_up_genesis_option_logic');
function favicon_up_genesis_option_logic() {
global $blog_id;

	if ( !is_multisite() ) {
	$favicon_name = 'favicon.ico';
	} else {
	$favicon_name = 'favicon-'.$blog_id.'.ico';
	}
	
$favicon_DIR = WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) . 'favicons/';
$favicon_check = $favicon_DIR . $favicon_name;
	
$check = $favicon_check ;

	if ( file_exists($check) ) {
		add_filter('genesis_favicon_url', 'favicon_up_favicon_url');     
	}
}

function favicon_up_favicon_url() {
global $blog_id;
$favicon_path = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) . 'favicons/';

	if ( !is_multisite() ) {
	$favicon = $favicon_path .'favicon.ico';
	} else {
	$favicon = $favicon_path .'favicon-'.$blog_id.'.ico';
	}

    return $favicon;
}


function favicon_upload_settings_admin() {
global $blog_id;

$favicon_DIR = WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) . 'favicons/';
$favicon_check = $favicon_DIR . basename( $_FILES['uploadedfile']['name']);
$favicon_path = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) . 'favicons/';

$extensions = array("ico"); 

$check = $favicon_check;

	if ( !is_multisite() ) {
		$favicon = $favicon_path . 'favicon.ico';
		$favicon_file = $favicon_DIR . 'favicon.ico';
	} else {
		$favicon = $favicon_path . 'favicon-'.$blog_id.'.ico';
		$favicon_file = $favicon_DIR . 'favicon-'.$blog_id.'.ico';
	}

$maybe_ico = pathinfo( $check, PATHINFO_EXTENSION );
?>

<div class="wrap">

	<?php screen_icon('tools'); ?>	
	<h2><?php _e('Genesis - Favicon Uploader', 'favicon_up'); ?></h2>

	<?php

	if ( is_writable( $favicon_DIR ) == false ) {
		echo  '<p style="color:red;"><strong>Sorry, "<u>' . $favicon_DIR . '</u>" is not writeable on the server.</strong></p>';
	}
	
	if( move_uploaded_file( $_FILES['uploadedfile']['tmp_name'], $check ) ) {
	
		if ( $maybe_ico != 'ico' ) {
			echo  '<p style="color:red;"><strong>Sorry, you tried to upload a ".' . $maybe_ico . '" file. <br />Currently at this time only file type supported is ".ico."</strong></p>';
			unlink ($check); 
		} elseif ( $maybe_ico == 'ico' && is_multisite() ) {
			rename($favicon_DIR .'favicon.ico', $favicon_DIR .'favicon-'.$blog_id.'.ico');
			echo '<p style="color:green;"><img src="'.$favicon.'" /> <-- Awesome Check it out! It worked. I hope so anyways. You should see your uploaded favicon beside this text.</p>';
		} else {
			echo '<p style="color:green;"><img src="'.$favicon.'" /> <-- Awesome Check it out! It worked. I hope so anyways. You should see your uploaded favicon beside this text.</p>';
		} 
	
	} elseif ( file_exists($favicon_file) ) {
		echo '<p><img src="'.$favicon.'" /> <-- Awesome Check it out! It worked. I hope so anyways. You should see your uploaded favicon beside this text. <br />If you are tired of the current favicon upload another!</p>';
	} else {	   
	   echo '<p>Upload your favicon below. If you only have a .png, .gif, or .jpg check out <a href="http://converticon.com/">http://converticon.com/</a> to convert it to an .ico file.</p>';	
	}
	?>
	
	<form enctype="multipart/form-data" method="post" action="<?php echo admin_url('admin.php?page=upload-favicon'); ?>">
		<?php wp_nonce_field('favicon-upload'); ?>
		<input type="hidden" name="favicon-upload" value="1" />
		<label for="uploadedfile"><?php echo sprintf( __('Upload File: <span class="description">(Maximum Size: %s)</span>', 'favicon_up'), ini_get('post_max_size') ); ?></label>
		<input type="file" id="uploadedfile" name="uploadedfile" size="30" />
		<input type="submit" class="button" value="<?php _e('Upload Favicon', 'favicon_up'); ?>" />
	</form>
	
	<p class="description">*NOTE Make sure your file name is favicon.ico before uploading.</p>

</div>

<?php
}