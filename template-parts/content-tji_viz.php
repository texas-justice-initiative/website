<?php
	/*
		This is the template file for the visualization sections of the homepage.
	*/

?>

<section id="<?php echo $post->post_name ?>" class="tji-section story-section">
	<?php the_title( '<h2 class="story-title">', '</h2>' ); ?>

	<div class="story-viz">
	
		<!-- Load visualization for this section -->
		<?php 
			if (locate_template( array('/vis/' . $post->post_name . '.php')) != '') {
				get_template_part( '/vis/' . $post->post_name);
			} else {
				echo "No visualization found.";
			}
		?>
		
		<!-- End Visualization section -->
	</div>

	
	<article class="story-content">
		
		<!-- Data here will be pulled from WordPress page content -->
		<?php the_content(); ?>
		
	</article>
</section>

