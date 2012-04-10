<?php
/*
Plugin Name: Genesis Simple Menus
Plugin URI: http://www.studiopress.com/plugins/simple-menus
Description: Genesis Simple Menus allows you to select a WordPress menu for secondary navigation on individual posts/pages.
Version: 0.1.3.2
Author: Ron Rennick
Author URI: http://ronandandrea.com/
*/
/* Copyright:	(C) 2010 Ron Rennick, All rights reserved.

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
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/* Sample implementation of adding support for custom taxonomy

add_filter( 'genesis_simple_menus_taxonomies', 'gsm_sample_taxonomy' );
function gsm_sample_taxonomy( $taxonomies ) {
	$taxonomies[] = 'taxonomy-slug';
	return array_unique( $taxonomies );
}
*/
class Genesis_Simple_Menus {
	var $handle = 'gsm-post-metabox';
	var $nonce_key = 'gsm-post-metabox-nonce';
	var $field_name = '_gsm_menu';
	var $menu = null;
	var $taxonomies=null;
/*
 * constructors - hook into Genesis if this is WP 3.0 or greater
 */
	function Genesis_Simple_Menus() {
		return $this->__construct();
	}
	function  __construct() {
		if( function_exists( 'wp_nav_menu' ) )
			add_action( 'genesis_init', array( &$this, 'init' ), 11 );

        	load_plugin_textdomain( 'genesis-simple-menus', false, 'genesis-simple-menus/languages' );
	}
/*
 * add all our base hooks into WordPress
 */
	function init() {
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
		add_action( 'save_post', array( &$this, 'save_post' ) );
		add_action( 'wp_head', array( &$this, 'wp_head' ) );
		
		$this->taxonomies = apply_filters( 'genesis_simple_menus_taxonomies', array( 'category', 'post_tag' ) );
		if( !empty( $this->taxonomies ) && is_array( $this->taxonomies ) ) {
			foreach( $this->taxonomies as $tax )
				add_action( "{$tax}_edit_form", array( &$this, 'term_edit' ), 9, 2 );
		}
	}
/*
 * Add the post metaboxes to the supported post types
 */
	function admin_menu() {
		foreach( (array) get_post_types( array( 'public' => true ) ) as $type ) {
			if( $type == 'post' || $type == 'page' || post_type_supports( $type, 'genesis-simple-menus' ) )
				add_meta_box( $this->handle, __( 'Secondary Navigation', 'genesis' ), array( &$this, 'metabox' ), $type, 'side', 'low' );
		}
	}
/*
 * Does the metabox on the post edit page
 */
	function metabox() {
		$this->print_nonce();
?>	<p>
<?php		$this->print_menu_select( $this->field_name, genesis_get_custom_field( $this->field_name ), 'width: 99%;' ); ?>
	</p>
<?php	}
/*
 * Does the metabox on the term edit page
 */
	function term_edit( $tag, $taxonomy ) {
		// Merge Defaults to prevent notices
		$tag->meta = wp_parse_args( $tag->meta, array( $this->field_name => '' ) );
?>
	<h3><?php _e( 'Secondary Navigation', 'genesis' ); ?></h3>
	<table class="form-table">
	<tr class="form-field">
		<th scope="row" valign="top">
<?php		$this->print_menu_select( "meta[{$this->field_name}]", $tag->meta[$this->field_name], '', 'padding-right: 10px;', '</th><td>' ); ?>
		</td>
	</tr>
	</table>
<?php	}
/*
 * Support function for the metaboxes, outputs the menu dropdown
 */
	function print_menu_select( $field_name, $selected, $select_style = '', $option_style = '', $after_label = '' ) {
		if( $select_style )
			$select_style = sprintf(' style="%s"', esc_attr( $select_style ) );
		if( $option_style )
			$option_style = sprintf(' style="%s"', esc_attr( $option_style ) );
?>
		<label for="<?php echo $fieldname; ?>"><span><?php _e( 'Secondary Navigation', 'genesis' ); ?><span></label>
<?php		echo $after_label; ?>
		<select name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>"<?php echo $select_style; ?>>
			<option value=""<?php echo $option_style; ?>><?php _e( 'Genesis Default', 'genesis-simple-menus' ); ?></option>
<?php		$menus = wp_get_nav_menus( array('orderby' => 'name') );
		foreach ( $menus as $menu )
			printf( '<option value="%d" %s>%s</option>', $menu->term_id, selected( $menu->term_id, $selected, false ), esc_html( $menu->name ) );
?>		</select>
<?php	}
/*
 * Handles the post save & stores the menu selection in the post meta
 */
	function save_post( $post_id ) {
		if ( !$this->verify_nonce() )
			return $post_id;

		//	don't try to save the data under autosave, ajax, or future post.
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
		if ( defined('DOING_AJAX') && DOING_AJAX ) return;
		if ( defined('DOING_CRON') && DOING_CRON ) return;
		if ( $post->post_type == 'revision' ) return;

		$perm = 'edit_' . ( 'page' == $_POST['post_type'] ? 'post' : $_POST['post_type'] );
		if ( current_user_can( $perm, $post_id ) ) {
			if( empty( $_POST[$this->field_name] ) )
				delete_post_meta( $post_id, $this->field_name );
			else
				update_post_meta( $post_id, $this->field_name, $_POST[$this->field_name] );
		}
		return $post_id;
	}
	function print_nonce() { ?>
		<input type="hidden" name="<?php echo $this->nonce_key; ?>" value="<?php echo wp_create_nonce( $this->handle ); ?>" />
<?php	}
	function verify_nonce() {
		return ( !isset($_POST[$this->nonce_key]) || wp_verify_nonce( $_POST[$this->nonce_key], $this->handle ) );
	}
/*
 * Once we hit wp_head, the WordPress query has been run, so we can determine if this request uses a custom subnav
 */
	function wp_head() {
		$term = false;

		if( is_singular() ) {
			$obj = get_queried_object();
			$this->menu = get_post_meta( $obj->ID, $this->field_name, true );
		}
		elseif( is_category() && in_array( 'category', $this->taxonomies ) )
			$term = get_term( get_query_var( 'cat' ), 'category' );
		elseif( is_tag() && in_array( 'post_tag', $this->taxonomies ) )
			$term = get_term( get_query_var( 'tag_id' ), 'post_tag' );
		elseif( is_tax() ) {
			foreach( $this->taxonomies as $tax ) {
				if( $tax == 'post_tag' || $tax == 'category' )
					continue;
				if( is_tax( $tax ) ) {
					$obj = get_queried_object();
					$term = get_term( $obj->term_id, $tax );
					break;
				}
			}
		}
		if( $term && isset( $term->meta[$this->field_name] ) )
			$this->menu = $term->meta[$this->field_name];

		if( $this->menu ) {
			add_filter( 'genesis_pre_get_option_subnav_type', array( &$this, 'pre_get_option_subnav_type' ) );
			add_filter( 'theme_mod_nav_menu_locations', array( &$this, 'theme_mod' ) );
		}
	}
/*
 * Tell Genesis to do a custom menu as the subnav
 */
	function pre_get_option_subnav_type( $nav ) {
		if( $this->menu )
			return 'nav-menu';

		return $nav;
	}
/*
 * Replace the menu selected in the WordPress Menu settings with the custom one for this request
 */
	function theme_mod( $mods ) {
		if( $this->menu )
			$mods['secondary'] = $this->menu;

		return $mods;
	}
}
/*
 *  giddyup
 */
$gsm_simple_menu = new Genesis_Simple_Menus();
