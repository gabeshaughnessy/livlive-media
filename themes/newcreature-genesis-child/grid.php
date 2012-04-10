<div id="block4" class="section feed_block">
<?php // the grid of projects that expands with ajax to reveal project content
$args =	array('post_type' => 'post', 'category__not_in' => '7');
genesis_custom_loop( $args );
?></div>