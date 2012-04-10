<?php 
add_action('wp_head', 'slider_script');
function slider_script() {//function to add javascript to the header
    ?>        
<?php
}
//The Slider Template -- copy this to your theme folder and edit it to make changes to the slider content.
$args =	array('post_type' => 'project' );
$slides = new WP_Query($args); //Query to get the Projects
?>
<div id="block1" class="section page_block">
<div id="project_slider" class="slider">
<?php
// The Loop
$post_num = 1;
while ( $slides->have_posts() ) : $slides->the_post();
$bg_image = wp_get_attachment_image_src( get_post_thumbnail_id( $slides->ID ), 'slider' );
?>
<a href="#project<?php echo $post_num; ?>">
<div class="slide" >
<div class="inset_container"></div>
<h2 class="slide_title">
<?php
the_title();
?></h2>
<div class="slide_description"><?php the_excerpt(); ?></div>
<img src="<?php echo $bg_image[0]; ?>" alt ="<?php the_title(); ?>" width="100%" height="auto" />
</div><!-- Close the Slide --> 
<?php
endwhile; //end of the slide loop
?></div>
</a><!-- Close the Slide Container -->
<div id="project_pager" class="pager" >
</div>
</div>
<div id="block2" class="section page_block">

<?php
// Reset Post Data
wp_reset_postdata();
wp_reset_query();
?>