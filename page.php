<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Texas_Justice_Initiative
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			/*
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
			*/

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
	//Specific Sidebars for different pages
	if ($post->post_name == 'about-the-data') {
		get_sidebar('about-data');
	} elseif ($post->post_name == 'publications') {
		get_sidebar('publications');
	} else {
		//Default sidebar
		get_sidebar('about');
	}
	
get_footer();
