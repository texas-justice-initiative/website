<?php
	/* Template Name: Tableau Page */
?>

<?php get_header(); ?>

<script type="text/javascript"
  src="https://online.tableau.com/javascripts/api/tableau-2.min.js"></script>

	<div id="primary" class="content-area">
	    <main id="main" class="site-main">
            <!-- Get the content from WordPress -->
            <?php the_content(); ?>
            <hr>
            <div id="vizContainer"></div>
        </main><!-- #main -->
	</div><!-- #primary -->

<script type="text/javascript">
    function initViz() {
        var containerDiv = document.getElementById("vizContainer"),
            url = "https://public.tableau.com/views/TJI-BailReform/Deaths_Bail",
            options = {
                hideTabs: true,
                onFirstInteractive: function () {
                    console.log("Run this code when the viz has finished loading.");
                }
            };

        var viz = new tableau.Viz(containerDiv, url, options);
        // Create a viz object and embed it in the container div.
    }
</script>

<?php
//Default sidebar
get_sidebar('about');
get_footer(); ?>
