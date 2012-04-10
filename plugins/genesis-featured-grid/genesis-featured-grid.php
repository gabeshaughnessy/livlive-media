<?php

/*

Plugin Name: Genesis Featured Grid

Plugin URI: http://www.heavydigitalcreative.com/plugins/genesis-featured-grid

Description: Genesis Featured Grid is a widget based on a modified version of the Genesis Featured Posts widget by StudioPress

Version: 1.0

Author: David Benedetti

Author URI: http://www.HeavyDigitalCreative.com/



    This program is free software; you can redistribute it and/or modify

    it under the terms of the GNU General Public License as published by

    the Free Software Foundation; either version 2 of the License, or

    (at your option) any later version.



    This program is distributed in the hope that it will be useful,

    but WITHOUT ANY WARRANTY; without even the implied warranty of

    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

    GNU General Public License for more details.



    You should have received a copy of the GNU General Public License

    along with this program; if not, write to the Free Software

    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/



// require Genesis 1.5 upon activation

register_activation_hook(__FILE__, 'featuredgrid_activation_check');

function featuredgrid_activation_check() {



		$latest = '1.5';

		

		$theme_info = get_theme_data(TEMPLATEPATH.'/style.css');

	

        if( basename(TEMPLATEPATH) != 'genesis' ) {

	        deactivate_plugins(plugin_basename(__FILE__)); // Deactivate ourself

            wp_die('Sorry, you can\'t activate unless you have installed <a href="http://www.studiopress.com/themes/genesis">Genesis</a>');

		}



		if( version_compare( $theme_info['Version'], $latest, '<' ) ) {

                deactivate_plugins(plugin_basename(__FILE__)); // Deactivate ourself

                wp_die('Sorry, you can\'t activate without <a href="http://www.studiopress.com/support/showthread.php?t=19576">Genesis '.$latest.'</a> or greater');

        }



}



// Define Plugin Dir

define( 'FEATURED_GRID_URL', plugin_dir_url( __FILE__ ) );



// Add stylesheet to page if the grid is present

if ( is_active_widget(false, false, 'featured-grid', true) ) add_action('wp_print_styles', 'gfg_add_style');

function gfg_add_style() {

     $theme  = get_theme( get_current_theme() );

     wp_register_style( 'featured-grid', FEATURED_GRID_URL.'/style.css', false, $theme['Version'] );

     wp_enqueue_style( 'featured-grid' );

} 



// Initialize Widget

add_action('widgets_init', create_function('', "register_widget('Genesis_Featured_Grid');"));

class Genesis_Featured_Grid extends WP_Widget {



	function Genesis_Featured_Grid() {

		$widget_ops = array( 'classname' => 'featuredgrid', 'description' => __('Displays featured grid with thumbnails', 'genesis') );

		$control_ops = array( 'width' => 505, 'height' => 350, 'id_base' => 'featured-grid' );

		$this->WP_Widget( 'featured-grid', __('Genesis - Featured Grid', 'genesis'), $widget_ops, $control_ops );

	}



	function widget($args, $instance) {

		extract($args);

		

		// defaults

		$instance = wp_parse_args( (array)$instance, array(

			'title' => '',

			'posts_cat' => '',

			'posts_num' => 1,

			'posts_offset' => 0,

			'orderby' => '',

			'order' => '',

			'show_image' => 0,

			'image_alignment' => '',

			'image_size' => '',

			'show_gravatar' => 0,

			'gravatar_alignment' => '',

			'gravatar_size' => '',

			'show_title' => 0,

			'show_byline' => 0,

			'post_info' => '[post_date] ' . __('By', 'genesis') . ' [post_author_posts_link] [post_comments]',

			'show_content' => 'excerpt',

			'content_limit' => '',

			'more_text' => __('[Read More...]', 'genesis'),

			'extra_num' => '',

			'extra_title' => '',

			'more_from_category' => '',

			'more_from_category_text' => __('More Posts from this Category', 'genesis')

		) );

		

		echo $before_widget;



			// Set up the author bio

			if (!empty($instance['title']))

				echo $before_title . apply_filters('widget_title', $instance['title']) . $after_title;



			$featured_posts = new WP_Query(array('post_type' => 'post', 'cat' => $instance['posts_cat'], 'showposts' => $instance['posts_num'],'offset' => $instance['posts_offset'], 'orderby' => $instance['orderby'], 'order' => $instance['order']));

                  

                        $cnt=1; //Counter for grid even/odd



			if($featured_posts->have_posts()) : while($featured_posts->have_posts()) : $featured_posts->the_post();

				

                                if ($cnt & 1) { 

 					$gridclass="genesis-grid-odd"; 

				} else {

					$gridclass="genesis-grid-even";

				}

				echo '<div '; post_class($gridclass); echo '>';

                                $cnt++;



				if(!empty($instance['show_image'])) :

					printf( '<a href="%s" title="%s" class="%s">%s</a>', get_permalink(), the_title_attribute('echo=0'), esc_attr( $instance['image_alignment'] ), genesis_get_image( array( 'format' => 'html', 'size' => $instance['image_size'] ) ) );

				endif;

				

				if(!empty($instance['show_gravatar'])) :

					echo '<span class="'.esc_attr($instance['gravatar_alignment']).'">';

					echo get_avatar( get_the_author_meta('ID'), $instance['gravatar_size'] );

					echo '</span>';

				endif;

				

				if(!empty($instance['show_title'])) :

					printf( '<h2><a href="%s" title="%s">%s</a></h2>', get_permalink(), the_title_attribute('echo=0'), the_title_attribute('echo=0') );

				endif;

				

				if ( !empty( $instance['show_byline'] ) && !empty( $instance['post_info'] ) ) :

					printf( '<p class="byline post-info">%s</p>', do_shortcode( esc_html( $instance['post_info'] ) ) );

				endif;

				

				if(!empty($instance['show_content'])) :

				

					if($instance['show_content'] == 'excerpt') :

						the_excerpt();

					elseif($instance['show_content'] == 'content-limit') :

						the_content_limit( (int)$instance['content_limit'], esc_html( $instance['more_text'] ) );

					else :

						the_content( esc_html( $instance['more_text'] ) );

					endif;

					

				endif;

				

				echo '</div><!--end post_class()-->'."\n\n";

					

			endwhile; endif;

			

			// The EXTRA Posts (list)

			if ( !empty( $instance['extra_num'] ) ) :



					if ( !empty($instance['extra_title'] ) )

						echo $before_title . esc_html( $instance['extra_title'] ) . $after_title;



					$offset = intval($instance['posts_num']) + intval($instance['posts_offset']);

					$extra_posts = new WP_Query( array( 'cat' => $instance['posts_cat'], 'showposts' => $instance['extra_num'], 'offset' => $offset ) );

					

					$listitems = '';

					if ( $extra_posts->have_posts() ) :

					

						while ( $extra_posts->have_posts() ) :

							

							$extra_posts->the_post();

							$listitems .= sprintf( '<li><a href="%s" title="%s">%s</a></li>', get_permalink(), the_title_attribute('echo=0'), get_the_title() );



						endwhile;

						

						if ( strlen($listitems) > 0 ) {

							printf( '<ul>%s</ul>', $listitems );

						}



					endif;



			endif;

			

			if(!empty($instance['more_from_category']) && !empty($instance['posts_cat'])) :

			

				echo '<p class="more-from-category"><a href="'.get_category_link($instance['posts_cat']).'" title="'.get_cat_name($instance['posts_cat']).'">'.esc_html($instance['more_from_category_text']).'</a></p>';

			

			endif;

		

		echo $after_widget;

		wp_reset_query();

	}



	function update($new_instance, $old_instance) {

		return $new_instance;

	}



	function form($instance) { 

		

		// ensure value exists

		$instance = wp_parse_args( (array)$instance, array(

			'title' => '',

			'posts_cat' => '',

			'posts_num' => 0,

			'posts_offset' => 0,

			'orderby' => '',

			'order' => '',

			'show_image' => 0,

			'image_alignment' => '',

			'image_size' => '',

			'show_gravatar' => 0,

			'gravatar_alignment' => '',

			'gravatar_size' => '',

			'show_title' => 0,

			'show_byline' => 0,

			'post_info' => '[post_date] ' . __('By', 'genesis') . ' [post_author_posts_link] [post_comments]',

			'show_content' => 'excerpt',

			'content_limit' => '',

			'more_text' => __('[Read More...]', 'genesis'),

			'extra_num' => '',

			'extra_title' => '',

			'more_from_category' => '',

			'more_from_category_text' => __('More Posts from this Category', 'genesis')

		) );

		

?>

			

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'genesis'); ?>:</label>

		<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:99%;" /></p>

		

	<div style="float: left; width: 250px;">

		

		<div style="background: #f1f1f1; border: 1px solid #DDD; padding: 10px 10px 0px 10px;">

		

		<p><label for="<?php echo $this->get_field_id('posts_cat'); ?>"><?php _e('Category', 'genesis'); ?>:</label>

		<?php wp_dropdown_categories(array('name' => $this->get_field_name('posts_cat'), 'selected' => $instance['posts_cat'], 'orderby' => 'Name' , 'hierarchical' => 1, 'show_option_all' => __("All Categories", 'genesis'), 'hide_empty' => '0')); ?></p>

		

		<p><label for="<?php echo $this->get_field_id('posts_num'); ?>"><?php _e('Number of Posts to Show', 'genesis'); ?>:</label>

		<input type="text" id="<?php echo $this->get_field_id('posts_num'); ?>" name="<?php echo $this->get_field_name('posts_num'); ?>" value="<?php echo esc_attr( $instance['posts_num'] ); ?>" size="2" /></p>

		

		<p><label for="<?php echo $this->get_field_id('posts_offset'); ?>"><?php _e('Number of Posts to Offset', 'genesis'); ?>:</label>

		<input type="text" id="<?php echo $this->get_field_id('posts_offset'); ?>" name="<?php echo $this->get_field_name('posts_offset'); ?>" value="<?php echo esc_attr( $instance['posts_offset'] ); ?>" size="2" /></p>

		

		<p><label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Order By', 'genesis'); ?>:</label>

		<select id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>">

			<option style="padding-right:10px;" value="date" <?php selected('date', $instance['orderby']); ?>><?php _e('Date', 'genesis'); ?></option>

			<option style="padding-right:10px;" value="title" <?php selected('title', $instance['orderby']); ?>><?php _e('Title', 'genesis'); ?></option>

			<option style="padding-right:10px;" value="parent" <?php selected('parent', $instance['orderby']); ?>><?php _e('Parent', 'genesis'); ?></option>

			<option style="padding-right:10px;" value="ID" <?php selected('ID', $instance['orderby']); ?>><?php _e('ID', 'genesis'); ?></option>

			<option style="padding-right:10px;" value="comment_count" <?php selected('comment_count', $instance['orderby']); ?>><?php _e('Comment Count', 'genesis'); ?></option>

			<option style="padding-right:10px;" value="rand" <?php selected('rand', $instance['orderby']); ?>><?php _e('Random', 'genesis'); ?></option>

		</select></p>

		

		<p><label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Sort Order', 'genesis'); ?>:</label>

		<select id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">

			<option style="padding-right:10px;" value="DESC" <?php selected('DESC', $instance['order']); ?>><?php _e('Descending (3, 2, 1)', 'genesis'); ?></option>

			<option style="padding-right:10px;" value="ASC" <?php selected('ASC', $instance['order']); ?>><?php _e('Ascending (1, 2, 3)', 'genesis'); ?></option>

		</select></p>

		

		</div>

		<div style="background: #f1f1f1; border: 1px solid #DDD; padding: 10px 10px 0px 10px; margin-top: 10px;">

		

		<p><input id="<?php echo $this->get_field_id('show_gravatar'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_gravatar'); ?>" value="1" <?php checked(1, $instance['show_gravatar']); ?>/> <label for="<?php echo $this->get_field_id('show_gravatar'); ?>"><?php _e('Show Author Gravatar', 'genesis'); ?></label></p>

		

		<p><label for="<?php echo $this->get_field_id('gravatar_size'); ?>"><?php _e('Gravatar Size', 'genesis'); ?>:</label>

		<select id="<?php echo $this->get_field_id('gravatar_size'); ?>" name="<?php echo $this->get_field_name('gravatar_size'); ?>">

			<option style="padding-right:10px;" value="45" <?php selected(45, $instance['gravatar_size']); ?>><?php _e('Small (45px)', 'genesis'); ?></option>

			<option style="padding-right:10px;" value="65" <?php selected(65, $instance['gravatar_size']); ?>><?php _e('Medium (65px)', 'genesis'); ?></option>

			<option style="padding-right:10px;" value="85" <?php selected(85, $instance['gravatar_size']); ?>><?php _e('Large (85px)', 'genesis'); ?></option>

			<option style="padding-right:10px;" value="125" <?php selected(105, $instance['gravatar_size']); ?>><?php _e('Extra Large (125px)', 'genesis'); ?></option>

		</select></p>

		

		<p><label for="<?php echo $this->get_field_id('gravatar_alignment'); ?>"><?php _e('Gravatar Alignment', 'genesis'); ?>:</label>

		<select id="<?php echo $this->get_field_id('gravatar_alignment'); ?>" name="<?php echo $this->get_field_name('gravatar_alignment'); ?>">

			<option style="padding-right:10px;" value="">- <?php _e('None', 'genesis'); ?> -</option>

			<option style="padding-right:10px;" value="alignleft" <?php selected('alignleft', $instance['gravatar_alignment']); ?>><?php _e('Left', 'genesis'); ?></option>

			<option style="padding-right:10px;" value="alignright" <?php selected('alignright', $instance['gravatar_alignment']); ?>><?php _e('Right', 'genesis'); ?></option>

		</select></p>

		

		</div>

		<div style="background: #f1f1f1; border: 1px solid #DDD; padding: 10px 10px 0px 10px; margin-top: 10px;">

		

		<p><input id="<?php echo $this->get_field_id('show_image'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_image'); ?>" value="1" <?php checked(1, $instance['show_image']); ?>/> <label for="<?php echo $this->get_field_id('show_image'); ?>"><?php _e('Show Featured Image', 'genesis'); ?></label></p>



		<p><label for="<?php echo $this->get_field_id('image_size'); ?>"><?php _e('Image Size', 'genesis'); ?>:</label>

		<?php $sizes = genesis_get_additional_image_sizes(); ?>

		<select id="<?php echo $this->get_field_id('image_size'); ?>" name="<?php echo $this->get_field_name('image_size'); ?>">

			<option style="padding-right:10px;" value="thumbnail">thumbnail (<?php echo get_option('thumbnail_size_w'); ?>x<?php echo get_option('thumbnail_size_h'); ?>)</option>

			<?php

			foreach((array)$sizes as $name => $size) :

			echo '<option style="padding-right: 10px;" value="'.esc_attr($name).'" '.selected($name, $instance['image_size'], FALSE).'>'.esc_html($name).' ('.$size['width'].'x'.$size['height'].')</option>';

			endforeach;

			?>

		</select></p>	

		

		<p><label for="<?php echo $this->get_field_id('image_alignment'); ?>"><?php _e('Image Alignment', 'genesis'); ?>:</label>

		<select id="<?php echo $this->get_field_id('image_alignment'); ?>" name="<?php echo $this->get_field_name('image_alignment'); ?>">

			<option style="padding-right:10px;" value="">- <?php _e('None', 'genesis'); ?> -</option>

			<option style="padding-right:10px;" value="alignleft" <?php selected('alignleft', $instance['image_alignment']); ?>><?php _e('Left', 'genesis'); ?></option>

			<option style="padding-right:10px;" value="alignright" <?php selected('alignright', $instance['image_alignment']); ?>><?php _e('Right', 'genesis'); ?></option>

		</select></p>

		

		</div>

		

	</div>

	

	<div style="float: left; width: 250px; margin-left: 10px;">

		

		<div style="background: #f1f1f1; border: 1px solid #DDD; padding: 10px 10px 0px 10px;">

		

		<p><input id="<?php echo $this->get_field_id('show_title'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_title'); ?>" value="1" <?php checked(1, $instance['show_title']); ?>/> <label for="<?php echo $this->get_field_id('show_title'); ?>"><?php _e('Show Post Title', 'genesis'); ?></label></p>

		

		<p><input id="<?php echo $this->get_field_id('show_byline'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_byline'); ?>" value="1" <?php checked(1, $instance['show_byline']); ?>/> <label for="<?php echo $this->get_field_id('show_byline'); ?>"><?php _e('Show Post Info', 'genesis'); ?></label>

		

		<input type="text" id="<?php echo $this->get_field_id('post_info'); ?>" name="<?php echo $this->get_field_name('post_info'); ?>" value="<?php echo esc_attr($instance['post_info']); ?>" style="width: 99%;" />

			

		</p>

		

		<p><label for="<?php echo $this->get_field_id('show_content'); ?>"><?php _e('Content Type', 'genesis'); ?>:</label>

		<select id="<?php echo $this->get_field_id('show_content'); ?>" name="<?php echo $this->get_field_name('show_content'); ?>">

			<option value="content" <?php selected('content' , $instance['show_content'] ); ?>><?php _e('Show Content', 'genesis'); ?></option>

			<option value="excerpt" <?php selected('excerpt' , $instance['show_content'] ); ?>><?php _e('Show Excerpt', 'genesis'); ?></option>

			<option value="content-limit" <?php selected('content-limit' , $instance['show_content'] ); ?>><?php _e('Show Content Limit', 'genesis'); ?></option>

			<option value="" <?php selected('' , $instance['show_content'] ); ?>><?php _e('No Content', 'genesis'); ?></option>

		</select>

		

		<br /><label for="<?php echo $this->get_field_id('content_limit'); ?>"><?php _e('Limit content to', 'genesis'); ?></label> <input type="text" id="<?php echo $this->get_field_id('image_alignment'); ?>" name="<?php echo $this->get_field_name('content_limit'); ?>" value="<?php echo esc_attr(intval($instance['content_limit'])); ?>" size="3" /> <?php _e('characters', 'genesis'); ?></p>

		

		<p><label for="<?php echo $this->get_field_id('more_text'); ?>"><?php _e('More Text (if applicable)', 'genesis'); ?>:</label>

		<input type="text" id="<?php echo $this->get_field_id('more_text'); ?>" name="<?php echo $this->get_field_name('more_text'); ?>" value="<?php echo esc_attr($instance['more_text']); ?>" /></p>

		

		</div>

		<div style="background: #f1f1f1; border: 1px solid #DDD; padding: 10px 10px 0px 10px; margin-top: 10px;">

		

		<p><?php _e('To display an unordered list of more posts from this category, please fill out the information below', 'genesis'); ?>:</p>

		

		<p><label for="<?php echo $this->get_field_id('extra_title'); ?>"><?php _e('Title', 'genesis'); ?>:</label>

		<input type="text" id="<?php echo $this->get_field_id('extra_title'); ?>" name="<?php echo $this->get_field_name('extra_title'); ?>" value="<?php echo esc_attr($instance['extra_title']); ?>" style="width:95%;" /></p>

		

		<p><label for="<?php echo $this->get_field_id('extra_num'); ?>"><?php _e('Number of Posts to Show', 'genesis'); ?>:</label>

		<input type="text" id="<?php echo $this->get_field_id('extra_num'); ?>" name="<?php echo $this->get_field_name('extra_num'); ?>" value="<?php echo esc_attr($instance['extra_num']); ?>" size="2" /></p>

		

		</div>

		<div style="background: #f1f1f1; border: 1px solid #DDD; padding: 10px 10px 0px 10px; margin: 10px 0;">

		

		<p><input id="<?php echo $this->get_field_id('more_from_category'); ?>" type="checkbox" name="<?php echo $this->get_field_name('more_from_category'); ?>" value="1" <?php checked(1, $instance['more_from_category']); ?>/> <label for="<?php echo $this->get_field_id('more_from_category'); ?>"><?php _e('Show Category Archive Link', 'genesis'); ?></label></p>

		

		<p><label for="<?php echo $this->get_field_id('more_from_category_text'); ?>"><?php _e('Link Text', 'genesis'); ?>:</label>

		<input type="text" id="<?php echo $this->get_field_id('more_from_category_text'); ?>" name="<?php echo $this->get_field_name('more_from_category_text'); ?>" value="<?php echo esc_attr($instance['more_from_category_text']); ?>" style="width:95%;" /></p>

		

		</div>

		

	</div>

			

	<?php 

	}

}