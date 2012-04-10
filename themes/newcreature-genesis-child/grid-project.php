</div><!-- the closing div from block 2, the content block -->
<div id="block3" class="section project_block">

<?php // the grid of projects that expands with ajax to reveal project content
$args =	array('post_type' => 'project' );
$projects = new WP_Query($args); //Query to get the Projects
?>
<div class="project_grid" id="project_grid_1">
<?php
$total_posts = $projects->found_posts;
// The Loop
$post_num = 1;
while ( $projects->have_posts() ) : $projects->the_post();
$bg_image = wp_get_attachment_image_src( get_post_thumbnail_id( $projects->ID ), 'medium' );

if($post_num == 1 | $post_num % 3 == 1){
$extra_classes = 'firstcolumn';
echo '<div class="project_row">';
}
elseif ($post_num == 3 | $post_num % 3 == 0) {
	$extra_classes = 'thirdcolumn';
}
elseif ($post_num == 2 | $post_num % 3 == 2) {
	$extra_classes = 'secondcolumn';
}

?>

<div id = "project<?php echo $post_num; ?>" class="project <?php echo $extra_classes; ?>">
<a href="#" class="project_thumb_link">
<h2 class="project_title">
<?php
the_title();
?></h2>
<div class="project_description"> <?php the_excerpt(); ?> </div>
<img src="<?php echo $bg_image[0]; ?>" alt ="<?php the_title(); ?>" width="100%" height="auto" />
</a>
<div class="project_content">
<div class="full-width">
<?php the_content(); ?>
</div>
</div>
</div><!-- Close the Project --> 

<?php
if( $post_num % 3 == 0){
echo '</div><!--close the project row-->';
}
$post_num ++;
endwhile; //end of the project loop
if( $total_posts % 3 != 0 ){
echo '</div><!--close the project row when $total_posts % 3 != 0 -->';
}
wp_reset_query();
?>
</div>


</div>
