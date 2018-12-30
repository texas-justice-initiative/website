<?php
	/*
		Template Name: Frontpage
		Description: A section based template used for website frontpage.
		
	*/

//Get visualizations for use later in the page to build content/nav

$viz_ids = get_posts(array(
	'fields'					=> 'ids',
	'posts_per_page'	=> -1,
	'post_type'				=> 'tji_viz',
	'orderby' 				=> 'menu_order',
	'order' 					=> 'ASC'
));

$viz_args = array(
	'post_type' => 'tji_viz',
	'orderby' => 'menu_order',
	'order' => 'ASC',
	'paged' => (get_query_var('paged')) ? get_query_var('paged') : 1,
	'posts_per_page' 	=> -1 // remove if loading via AJAX
);

$viz_query = new WP_Query( $viz_args );

?>

<?php get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">

		<!-- The initial landing screen -->
		<section id="hero" class="tji-section">
			<?php
				//A unique splash screen
				include( locate_template( 'template-parts/homepage-landing.php' ) );
			?>
		<div class="read-more">
			<img src="<?php echo get_template_directory_uri(); ?>/img/home_arrow.png">
		</div>
		</section><!-- #hero -->

		<!-- Story navigation icons -->
		<section id="tji-story-nav" class="tji-section">
			<div class="tji-story-start">
				<span class="tji-tagline">What do we know about those deaths?</span>
				<hr class="tji-divider">
				<p>Choose a topic below to learn more, or scroll down to start from the beginning.</p>
			</div>
			<!-- Landing page navigation panels -->
			<div id="tji-nav-main" class="nav-container">
			<?php 
				//echo '<pre>' . print_r( $viz_ids, true ) . '</pre>';

				//Loop through each story and build a panel for each
				foreach ($viz_ids as $id) {
					$title = get_the_title( $id );
					$slug = get_post_field('post_name', $id);
					?>
					
					<div class="tji-nav-child">
						<a href="#<?php echo $slug; ?>" class="viz-link">
							<img src="<?php echo get_the_post_thumbnail_url($id); ?>" alt="<?php echo $title; ?>" class="tji-nav-icon">
							<span class="tji-nav-title"><?php echo $title; ?></span>
						</a>
					</div>
					
					<?php
				} //end child loop
			?>
			</div><!-- #tji-nav-main -->
		</section>
		<!-- End story navigation -->	

		<!-- Wrapper for our story and nav panel -->
		<div class="story-wrapper">
			
			<!-- Right side navigation panel -->
			<div class="nav-panel closed">	
				<div class="link-container">
					<div class="panel-toggle">
						<a href="#">&larr;</a>
					</div>
				<?php 					
					//Loop through each story and build a panel for each
					foreach ($viz_ids as $id) {
						$title = get_the_title( $id );
						$slug = get_post_field('post_name', $id);
						?>
						
						<div class="nav-link">
							<a href="#<?php echo $slug; ?>" class="viz-link">
								<div class="link-icon">
									<img src="<?php echo get_the_post_thumbnail_url( $id ); ?>" alt="<?php echo $title; ?>" class="tji-nav-icon">
								</div>
								<div class="link-title">
									<?php echo $title; ?>
								</div>		
							</a>
						</div>
	
						
						<?php
					} //end child loop
				?>
				</div><!-- .link-container -->
				
				<!-- Social media links -->
				<section id="wpcw_social-2" class="widget wpcw-widgets wpcw-widget-social homepage-social-links">
					<ul>
						<li class="no-label">
							<a href="https://google.com/+TexasJusticeInitiative" target="_blank" title="Visit Texas Justice Initiative on Google+"><span class="fab fa-2x fa-google-plus"></span></a>
						</li>
						<li class="no-label">
							<a href="https://www.facebook.com/TXJusticeInitiative" target="_blank" title="Visit Texas Justice Initiative on Facebook"><span class="fab fa-2x fa-facebook"></span></a>
						</li>
						<li class="no-label">
							<a href="https://twitter.com/JusticeTexas" target="_blank" title="Visit Texas Justice Initiative on Twitter"><span class="fab fa-2x fa-twitter"></span></a>
						</li>
					</ul>
				</section>

			</div><!-- .nav-links -->	
		
			<!-- Begin story sections -->
			<div class="story-sections closed">
				<?php						
					if ($viz_query->have_posts()) : while ($viz_query->have_posts()) : $viz_query->the_post();
						get_template_part( 'template-parts/content', get_post_type() );
					endwhile;
					endif;
				?>
				
			</div><!-- .story-content -->

		<div class="tji-about-brief">
			<div class="tji-about-column">
				<p><strong>What is the Texas Justice Initiative?</strong></p>
				<p>We are a nonprofit organization that collects, analyzes, publishes and provides oversight for criminal justice data throughout Texas. Co-founded by a researcher and a journalist, TJI is devoted to increasing transparency and accountability.</p>
			</div>
			<div class="tji-about-column">		
				<p><strong>Data in context. </strong></p>
				<p>TJI is building a one-stop shop for all kinds of data related to the Texas criminal justice, starting with officer-involved shootings and custodial deaths.We aim to be a resource for all.</p>
			</div>
		</div><!-- .tji-about-brief -->
		
		</div><!-- .story-wrapper -->
		
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>