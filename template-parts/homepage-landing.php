<?php
	/*
		This is the template file for the initial landing screen of the homepage.
	*/
?>

<!-- Swiper Slider main container -->
<div class="swiper-container">
    <!-- Additional required wrapper -->
    <div class="swiper-wrapper">
        <!-- Slides -->
        <div class="swiper-slide">
        	<div class="content">Since 2005, <span style="color:#CE2727">8,730 deaths</span> have been reported in Texas custody.</div>
        </div>
        <div class="swiper-slide">
        	<div class="content">Texas law enforcement officers have shot <span style="color:#CE2727">466 civilians</span> since Sept. 1, 2015.</div>
        </div>
        <div class="swiper-slide">
        	<div class="content">There have been <span style="color:#CE2727">78 Texas law enforcement officers</span> shot since Sept. 1, 2015.</div>
        </div>
    </div>
    <!-- If we need pagination -->
    <div class="swiper-pagination"></div>

    <!-- If we need navigation buttons -->
<!--     <div class="swiper-button-prev"></div> -->
<!--     <div class="swiper-button-next"></div> -->

    <!-- If we need scrollbar -->
    <div class="swiper-scrollbar"></div>
</div>

<?php
	//Build the landing screen from the homepage content.
	/*
	while ( have_posts() ) : the_post();
	?>
		<!-- Unless we want to display large logo on homepage
		<div class="site-brand">
			<?php the_post_thumbnail('full') ?>
		</div>
		-->
	<div class="hero-content">
		<?php the_content(); ?>
	</div>
	<?php
	endwhile;
	wp_reset_postdata();
	*/
	//End homepage content loop
?>
