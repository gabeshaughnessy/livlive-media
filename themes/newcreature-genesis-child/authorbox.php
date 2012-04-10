<div id="block6" class="section author">
<?php // the grid of profiles

add_action('genesis_before_post', 'format_elements');
function format_elements() {
	global $post;
	remove_action('genesis_before_post_content', 'genesis_post_info');
	};

$args =	array('post_type' => 'post', 'cat' => '7');
genesis_custom_loop( $args );




?>
</div>
