<?php
	/*
		This is the template file for the initial landing screen of the homepage.
	*/
?>
  <!-- Swiper Requirements -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.0/css/swiper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.0/js/swiper.min.js"></script>

<!-- Swiper Slider main container -->
<div class="swiper-container">
    <!-- Additional required wrapper -->
    <div class="swiper-wrapper">
        <!-- Slides -->
        <div class="swiper-slide">
        	<div class="content">Since <span id="js-cdr-year"></span>, <span class="text--color-red"><span id="js-cdr-total"></span> deaths</span> have been reported in Texas custody.</div>
        </div>
        <div class="swiper-slide">
        	<div class="content">Texas law enforcement officers have shot <span class="text--color-red"><span id="js-ois-total"></span> civilians</span> since <span id="js-ois-year"></span>.</div>
        </div>
        <div class="swiper-slide">
        	<div class="content">There have been <span class="text--color-red"><span id="js-ois-officers-total"></span> Texas law enforcement officers</span> shot since <span id="js-officers-year"></span>.</div>
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
	var fetch1 = jQuery.getJSON("<?php echo get_template_directory_uri(); ?>/data/cdr_compressed.json", function (cdrData) {
		var cdrStartingYear = cdrData.meta.lookups.year[0];
		jQuery("#js-cdr-year").text(cdrStartingYear);
		var cdrTotalRecords = cdrData.meta.num_records;
		jQuery("#js-cdr-total").text(cdrTotalRecords.toLocaleString('en'));
	});
	var fetch2 = jQuery.getJSON("<?php echo get_template_directory_uri(); ?>/data/ois_compressed.json", function (oisData) {
		var oisStartingYear = oisData.meta.lookups.year[0];
		jQuery("#js-ois-year").text(oisStartingYear);
		var oisTotalRecords = oisData.meta.num_records;
		jQuery("#js-ois-total").text(oisTotalRecords.toLocaleString('en'));
	});
	var fetch3 = jQuery.getJSON("<?php echo get_template_directory_uri(); ?>/data/ois_officers_compressed.json", function (officersData) {
		var officersStartingYear = officersData.meta.lookups.year[0];
		jQuery("#js-officers-year").text(officersStartingYear);
		var officersTotalRecords = officersData.meta.num_records;
		jQuery("#js-ois-officers-total").text(officersTotalRecords.toLocaleString('en'));
	});

  //Initialize Swiper Slider
  jQuery.when(fetch1, fetch2, fetch3).done(function(){
    new Swiper ('.swiper-container', {
        loop:true,
        centeredSlides: true,
        freeMode: true,
      freeModeSticky: true,
      slidesPerView: 1,
      speed: 300,
      autoplay: { 
            delay: 5000,
            disableOnInteraction: false
          },
      spaceBetween: 15,
      navigation: {
        prevEl: ".swiper-button-prev",
        nextEl: ".swiper-button-next"
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true
      },
      
    });
  })
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

