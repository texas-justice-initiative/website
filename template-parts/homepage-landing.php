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
        	<div class="content">Since 2005, <span style="color:#CE2727"><span id="cdrTotal"></span> deaths</span> have been reported in Texas custody.</div>
        </div>
        <div class="swiper-slide">
        	<div class="content">Texas law enforcement officers have shot <span style="color:#CE2727"><span id="oisTotal"></span> civilians</span> since Sept. 1, 2015.</div>
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
<!-- Fetch JSON data for dynamic slider numbers -->
<script type="text/javascript">
	var cdrTotalRecords = 0;
	jQuery.getJSON("<?php echo get_template_directory_uri(); ?>/data/cdr_compressed.json", function (data) {
		console.log(data);
		cdrTotalRecords = data.meta.num_records;
		jQuery("#cdrTotal").append(cdrTotalRecords.toLocaleString('en'));
	    console.log(data);
	});
	var oisTotalRecords = 0;
	jQuery.getJSON("<?php echo get_template_directory_uri(); ?>/data/ois_compressed.json", function (data) {
		console.log(data);
		oisTotalRecords = data.meta.num_records;
		jQuery("#oisTotal").append(oisTotalRecords.toLocaleString('en'));
	    console.log(data);
	}); 
</script>

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

