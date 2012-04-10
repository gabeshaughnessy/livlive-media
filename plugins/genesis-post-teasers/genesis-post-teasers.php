<?php
/*
Plugin Name: Genesis Post Teasers
Plugin URI: http://genesistutorials.com/plug-ins/genesis-post-teasers/
Description: Add theme settings for enabling teasers for the Genesis Theme Framework by studiopress. Uses genesis_grid_loop();
Version: 1.0.3.2
Author: Christopher Cochran
Author URI: http://christophercochran.me
*/

register_activation_hook(__FILE__, 'gPostTeaz_activation_check');
function gPostTeaz_activation_check() {
		
		$theme_info = get_theme_data(TEMPLATEPATH.'/style.css');
	
        if ( basename(TEMPLATEPATH) != 'genesis' ) {
	        deactivate_plugins(plugin_basename(__FILE__)); // Deactivate ourself
            wp_die('Sorry, you can\'t activate unless you have installed <a href="http://www.studiopress.com/themes/genesis">Genesis</a>');
		}

}

add_filter('genesis_theme_settings_defaults', 'define_post_teasers_default_settings', 15, 2);
function define_post_teasers_default_settings($defaults) {
    $defaults = array(
    	'teasers_enable' => 'Home Page',
        'numof_posts_per_page' => 10,
        'use_css' => 'pluginstyles',
        'numof_full_posts' => 2,
        'readmore_link_on_teasers' => '[Continue reading...]'
    );
    return $defaults;
}


$gPostTeaz_full = false;
// All the logic to make it happen.
add_action('get_header', 'enable_post_teaser_logic');
function enable_post_teaser_logic() {
	if ( function_exists('genesis') ) {
	global $gPostTeaz_full;
	
		if ( genesis_get_option('teasers_enable') == 'Home Page' )
			$enableteaserson = is_home();
		if ( genesis_get_option('teasers_enable') == 'Blog Template' )
			$enableteaserson = is_page_template( 'page_blog.php' );	
		if ( genesis_get_option('teasers_enable') == 'Archives' )
			$enableteaserson = is_archive();
			
		$gPostTeaz_full = genesis_get_option('numof_full_posts');
			
		if ( $enableteaserson ) {
			remove_action( 'genesis_loop', 'genesis_do_loop' );
			remove_action( 'genesis_loop', 'focus_grid_loop_helper' );
			remove_action( 'genesis_loop', 'pretty_grid_loop_helper' );
			add_action( 'genesis_loop', 'gpt_grid_loop' );
			add_action('genesis_before_post','post_teaser_do_open', 10, 2);
			if ( genesis_get_option('no_pair_teasers') == 0 ) {
				add_action('genesis_before_post','post_teaser_pertwo_wrap_open', 15, 2);
				add_action('genesis_after_post','post_teaser_pertwo_wrap_openclose');
				add_action('genesis_after_endwhile','post_teaser_pertwo_wrap_close', 5, 2);
			}
			add_action('genesis_after_endwhile','post_teaser_do_close', 6, 2);
			if ( genesis_get_option('no_pair_teasers') == 1 || genesis_get_option('use_css') == 'pluginstyles'  ) {
				$genesis = $gPostTeaz_full;
				$genesisopt_full_width = genesis_get_option('no_pair_teasers');
				$genesisopt_width = genesis_get_option('post_teaser_width');
				$genesisopt_height = genesis_get_option('post_teaser_height');
				$genesisopt_enable_custom_styles = genesis_get_option('enable_custom_teaser_styles');
				wp_enqueue_style('teaserstyles', WP_PLUGIN_URL . "/genesis-post-teasers/css/teaserstyles.php?genesis=$genesis&genesisopt_width=$genesisopt_width&genesisopt_height=$genesisopt_height&genesisopt_no_pair=$genesisopt_full_width&genesisopt_enable_custom_styles=$genesisopt_enable_custom_styles");
			}
			if ( genesis_get_option('disable_teaser_meta') == 1 ) {
				remove_action('genesis_after_post_content', 'genesis_post_meta');
				add_filter('genesis_after_post_content', 'genesis_post_meta_teaser_logic');
			}
			if ( genesis_get_option( 'disable_teaser_info' ) == 1 ) {
				remove_action( 'genesis_before_post_content', 'genesis_post_info' );
				add_filter( 'genesis_before_post_content', 'genesis_post_info_teaser_logic' );
			}
		}
	}
}

// This will remove post meta on teaser posts
function genesis_post_meta_teaser_logic() {
global $loop_counter, $gPostTeaz_full, $post;
	if ( $loop_counter >= $gPostTeaz_full || is_paged() >= 2 )
		return; // don't do post-meta on teasers

	genesis_post_meta();
}

// This will remove post info (byline) on teaser posts
function genesis_post_info_teaser_logic() {
global $loop_counter, $gPostTeaz_full, $post;
	if ( $loop_counter >= $gPostTeaz_full || is_paged() >= 2 )
		return; // don't do post-info on teasers
	
	genesis_post_info();
}

// Wraps the post teaser area with its own div.
function post_teaser_do_open() {
global $loop_counter, $gPostTeaz_full;
    if ( !is_paged() && $loop_counter == $gPostTeaz_full || is_paged() >= 2 && $loop_counter == 0) {
        echo '<div id="post-teasers">';      
    }
}
//Opens the div for the first pair of teasers. This helps to keep the area of two
//the same height so can have a flexible height for the teasers.
function post_teaser_pertwo_wrap_open() {
global $loop_counter, $gPostTeaz_full;

    if ( !is_paged() && $loop_counter == $gPostTeaz_full || is_paged() >= 2 && $loop_counter == 0) {
        echo '<div class="post-teasers-pair">';
    }
}

// Wraps the a pair of teasers with their own div. This helps to keep the area of two
//the same height so can have a flexible height for the teasers.
function post_teaser_pertwo_wrap_openclose() {
global $loop_counter, $gPostTeaz_full, $posts;
	if ( $loop_counter == get_option('posts_per_page' )-1 || $loop_counter == sizeof($posts)-1 || $loop_counter == genesis_get_option('numof_posts_per_page') ) return; 
    if ( !is_paged() && $loop_counter > $gPostTeaz_full && ( $loop_counter - genesis_get_option('numof_full_posts') ) % 2 || is_paged() >= 2 && $loop_counter % 2 ) {
        echo '</div><div class="post-teasers-pair">';
    }
}

// Closing div to teaser pairs.
function post_teaser_pertwo_wrap_close() {
global $loop_counter, $gPostTeaz_full, $posts;
$sizeOfTeasers = sizeof($posts) - genesis_get_option('numof_full_posts');

	if ( $sizeOfTeasers == 0 && !is_paged() )
		return; // Do not close since there is no open! Props @GK.

	if ( $loop_counter >= $gPostTeaz_full || is_paged() >= 2 ) {
		echo '</div>';
	}
}
// Closing div to post teaser area.
function post_teaser_do_close() {
global $loop_counter, $gPostTeaz_full, $posts;
$sizeOfTeasers = sizeof($posts) - genesis_get_option('numof_full_posts');

	if ( $sizeOfTeasers == 0 && !is_paged() )
		return; // Do not close since there is no open! Props @GK.
		
	if ( $loop_counter >= $gPostTeaz_full || is_paged() >= 2 ) {
		echo '</div>';
	}
}

// Using The Grid Loop Now.
function gpt_grid_loop() {
$feat_image_size = '';
$grid_image_size = '';
$postperpage = get_option('posts_per_page' );
$continuereading = __( '[Continue reading...]', 'genesis' );
if ( genesis_get_option('gpt_enable_featured_thumbnail') )
	$feat_image_size = genesis_get_option('gpt_thumbnail_featured_size');
if ( genesis_get_option('enable_teaser_thumbnail') )
	$grid_image_size = genesis_get_option('teaser_thumbnail_size');
if ( genesis_get_option('readmore_link_on_teasers') )   
 	$continuereading = genesis_get_option('readmore_link_on_teasers');
if ( genesis_get_option('numof_posts_per_page') )
	$postperpage = genesis_get_option('numof_posts_per_page');

$cat_id = '';
if ( genesis_get_option('teasers_enable') == 'Blog Template' ) {
	if ( genesis_get_custom_field('query_args') )
		$cat_id = genesis_get_custom_field('query_args');
}
if ( genesis_get_option('teasers_enable') == 'Archives' )
	$cat_id = get_query_var('cat');


    if ( function_exists( 'genesis_grid_loop' ) ) {

       genesis_grid_loop( array(
            'features' => genesis_get_option('numof_full_posts'),
            'feature_image_size' => $feat_image_size,
            'feature_content_limit' => genesis_get_option('featured_post_content_limit'),
            'grid_image_size' => $grid_image_size,
            'grid_content_limit' => genesis_get_option('teaser_post_content_limit'),
            'more' => $continuereading,
            'posts_per_page' => $postperpage,
            'cat' => $cat_id
        ) );
	
    } else {
        genesis_standard_loop();
    }
}

// Code below adds the setting box in Genesis Settings.
// This box allows you to set how many full posts to display
// and to use teaser or not.
// If enable teasers is not checked none the previous code will run.
add_action('genesis_init', 'add_gPostTeaz_settings_init', 15 );
function add_gPostTeaz_settings_init() {
add_action('admin_menu', 'gPostTeaz_settings_init' );
}

function gPostTeaz_settings_init() {
global $_genesis_theme_settings_pagehook;

	add_action('load-'.$_genesis_theme_settings_pagehook, 'gPostTeaz_settings_boxes');
	add_action('load-'.$_genesis_theme_settings_pagehook, 'gPostTeaz_settings_scripts');
}
	

function gPostTeaz_settings_scripts() {	
	wp_enqueue_script('settingslide', WP_PLUGIN_URL . '/genesis-post-teasers/js/settingslide.js');
}

function gPostTeaz_settings_boxes() {
global $_genesis_theme_settings_pagehook;

	add_meta_box('gPostTeaz-settings-box', 'Teaser Boxes Settings', 'gPostTeaz_settings_box', $_genesis_theme_settings_pagehook, 'column2', 'high' );
}

function gPostTeaz_settings_box() { ?>
<p>
	<label>Enable teasers on <select name="<?php echo GENESIS_SETTINGS_FIELD; ?>[teasers_enable]">
		<?php
		echo '<option style="padding-right: 10px;" value="Home Page" '.selected( 'Home Page', genesis_get_option('teasers_enable'), FALSE).'>Home Page</option>';
		echo '<option style="padding-right: 10px;" value="Blog Template" '.selected( 'Blog Template', genesis_get_option('teasers_enable'), FALSE).'>Blog Template</option>';
		echo '<option style="padding-right: 10px;" value="Archives" '.selected( 'Archives', genesis_get_option('teasers_enable'), FALSE).'>Archives</option>';
		?>
	</select></lable>
</p>
<p><input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[no_pair_teasers]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[no_pair_teasers]" value="1" <?php checked(1, genesis_get_option('no_pair_teasers')); ?> /> <label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[no_pair_teasers]"><?php _e('Do not show teasers in pairs.', 'genesis'); ?></label></p>
<p><input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[disable_teaser_info]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[disable_teaser_info]" value="1" <?php checked(1, genesis_get_option('disable_teaser_info')); ?> /> <label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[disable_teaser_info]"><?php _e('Disable post info (byline) on teasers?', 'genesis'); ?></label></p>
<p><input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[disable_teaser_meta]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[disable_teaser_meta]" value="1" <?php checked(1, genesis_get_option('disable_teaser_meta')); ?> /> <label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[disable_teaser_meta]"><?php _e('Disable post meta on teasers?', 'genesis'); ?></label></p>
<p>
	<label><input type="radio" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[use_css]" value="pluginstyles" <?php checked('pluginstyles', genesis_get_option('use_css')); ?> />
	<?php echo 'Plugin CSS' ?></label><br />
	<label><input type="radio" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[use_css]" value="stylesheet" <?php checked('stylesheet', genesis_get_option('use_css')); ?> />
	<?php echo 'Child Theme CSS' ?></label>
</p>
<p>
	<span class="description">NOTE: "Plugin CSS" will allow you to change the teasers height and width. "Child Theme CSS" uses the styles in child theme for teasers widths and height.</span>
</p>
<div class="css-opts <?php if ( genesis_get_option('use_css') === 'stylesheet' ) echo 'hidden' ?>">
	<p><input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[enable_custom_teaser_styles]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[enable_custom_teaser_styles]" value="1" <?php checked(1, genesis_get_option('enable_custom_teaser_styles')); ?> /> <label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[enable_custom_teaser_styles]"><?php _e('Edit teaser styles?', 'genesis'); ?></label></p>
	<div class="custom-css-opts <?php if ( genesis_get_option('enable_custom_teaser_styles') != 1 ) echo 'hidden' ?>">
	  <hr class="div">
	  <p><?php echo "Enter width for teaser boxes: "; ?>
	  <input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[post_teaser_width]" value="<?php echo esc_attr( genesis_get_option('post_teaser_width') ); ?>" size="3" />px</p>
	  <p><span class="description">Depending on your layout or child theme you may want to change the width of your teasers. By Default it will auto adjust. </span></p>

	  <p><?php echo "Enter height for teaser boxes: "; ?>
	  <input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[post_teaser_height]" value="<?php echo esc_attr( genesis_get_option('post_teaser_height') ); ?>" size="3" />px</p>
	  <p><span class="description">If you want a defined height place here. By default the height will auto adjust. </span></p>
	  <p><span class="description">( NOTE: When teasers are not show in pairs you cannot edit the height or width from the plugin. ) </span></p>

	</div>
</div>
<hr class="div">
<p>Featured posts settings:</p>	
<p>
	<?php echo "How many Featured posts before teasers?"; ?>
	<input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[numof_full_posts]" value="<?php echo esc_attr( genesis_get_option('numof_full_posts') ); ?>" size="1" />
</p>
<p><label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[featured_post_content_limit]">Limit featured post content to</label> <input type="text" size="3" value="<?php echo esc_attr( genesis_get_option('featured_post_content_limit') ); ?>" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[featured_post_content_limit]" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[featured_post_content_limit]"> <label for="genesis-settings[featured_post_content_limit]">characters</label></p>	
<p class="enable-feat-thumbnail"><input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[gpt_enable_featured_thumbnail]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[gpt_enable_featured_thumbnail]" value="1" <?php checked(1, genesis_get_option('gpt_enable_featured_thumbnail')); ?> /> <label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[gpt_enable_featured_thumbnail]"><?php _e('Include featured image on featured posts?', 'genesis'); ?></label></p>
<p style="padding-left: 20px;" class="select-feat-image-size <?php if ( genesis_get_option('gpt_enable_featured_thumbnail') == '' ) echo 'hidden' ?>">
	<?php _e("Image Size", 'genesis'); ?>:
	<?php $sizes = genesis_get_additional_image_sizes(); ?>
	<select name="<?php echo GENESIS_SETTINGS_FIELD; ?>[gpt_thumbnail_featured_size]">
		<option style="padding-right:10px;" value="thumbnail">thumbnail (<?php echo get_option('thumbnail_size_w'); ?>x<?php echo get_option('thumbnail_size_h'); ?>)</option>
		<?php
		foreach((array)$sizes as $name => $size) :
			echo '<option style="padding-right: 10px;" value="'.$name.'" '.selected($name, genesis_get_option('teaser_thumbnail_size'), FALSE).'>'.$name.' ('.$size['width'].'x'.$size['height'].')</option>';
		endforeach;
		?>
	</select>
</p>
<hr class="div">
<p>Teaser settings:</p>
<p><label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[teaser_post_content_limit]">Limit teaser post content to</label> <input type="text" size="3" value="<?php echo esc_attr( genesis_get_option('teaser_post_content_limit') ); ?>" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[teaser_post_content_limit]" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[teaser_post_content_limit]"> <label for="genesis-settings[teaser_post_content_limit]">characters</label></p>	
<p class="enable-thumbnail"><input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[enable_teaser_thumbnail]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[enable_teaser_thumbnail]" value="1" <?php checked(1, genesis_get_option('enable_teaser_thumbnail')); ?> /> <label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[enable_teaser_thumbnail]"><?php _e('Include featured image on teasers?', 'genesis'); ?></label></p>
<p style="padding-left: 20px;" class="select-image-size <?php if ( genesis_get_option('enable_teaser_thumbnail') == '' ) echo 'hidden' ?>">
	<?php _e("Image Size", 'genesis'); ?>:
	<?php $sizes = genesis_get_additional_image_sizes(); ?>
	<select name="<?php echo GENESIS_SETTINGS_FIELD; ?>[teaser_thumbnail_size]">
		<option style="padding-right:10px;" value="thumbnail">thumbnail (<?php echo get_option('thumbnail_size_w'); ?>x<?php echo get_option('thumbnail_size_h'); ?>)</option>
		<?php
		foreach((array)$sizes as $name => $size) :
			echo '<option style="padding-right: 10px;" value="'.$name.'" '.selected($name, genesis_get_option('teaser_thumbnail_size'), FALSE).'>'.$name.' ('.$size['width'].'x'.$size['height'].')</option>';
		endforeach;
		?>
	</select>
</p>
<hr class="div">
<p>
	<?php echo "How many posts per page?"; ?>
	<input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[numof_posts_per_page]" value="<?php echo esc_attr( genesis_get_option('numof_posts_per_page') ); ?>" size="1" />
</p>
<?php /* <p><input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[readmore_on_teasers]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[readmore_on_teasers]" value="1" <?php checked(1, genesis_get_option('readmore_on_teasers')); ?> /> <label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[readmore_on_teasers]"><?php _e('Enable "Read More" link on teasers?', 'genesis'); ?></label></p> */ ?>	
<p><?php echo 'Custom "[Continue reading...]" text.'; ?>
	<input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[readmore_link_on_teasers]" value="<?php echo esc_attr( genesis_get_option('readmore_link_on_teasers') ); ?>" size="15" />
</p>

<?php /*<p><label><input type="radio" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[show_advance_opts]" value="no" <?php checked('pluginstyles', genesis_get_option('use_css')); ?> />
		<?php echo 'Use default values for post info, meta and read more.' ?><br />
	<label><input type="radio" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[show_advance_opts]" value="yes" <?php checked('stylesheet', genesis_get_option('use_css')); ?> />
		<?php echo 'Filter text for post info, meta and the read more link.' ?></label>
	</p>
	<?php echo "Custom read more text."; ?>
	<input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[readmore_link_on_teasers]" value="<?php echo esc_attr( genesis_get_option('readmore_link_on_teasers') ); ?>" size="15" /></p>
	</div>
*/ ?>

<?php }