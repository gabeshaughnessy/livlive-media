<?php //The Page Content Block template
$args =	array(
	'post_type' => 'page',
	'name' => 'sample-page' //replace with options panel page slug
);
?>
<div id="block5" class="section page_block">
<?php
genesis_custom_loop( $args );
?>
</div>