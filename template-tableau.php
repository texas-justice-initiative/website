<?php
	/* Template Name: Tableau Page */
?>

<?php

get_header();

// Get our post content
$content = apply_filters( 'the_content', $post->post_content );

?>

<script type="text/javascript"
  src="https://online.tableau.com/javascripts/api/tableau-2.min.js"></script>

<div id="primary" class="content-area full-width">
    <main id="main" class="site-main">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
            </header><!-- .entry-header -->

            <?php tji_post_thumbnail(); ?>

            <div class="entry-content">
                <?php echo $content; ?>
                <div id="vizContainer"></div>
            </div><!-- .entry-content -->
        </article><!-- #post-<?php the_ID(); ?> --> 
    </main><!-- #main -->
</div><!-- #primary -->       

<script type="text/javascript">
    function initViz() {
        var containerDiv = document.getElementById("vizContainer"),
            url = "https://public.tableau.com/views/TJI-BailReform/Deaths_Bail",
            options = {
                hideTabs: true,
            };

        var viz = new tableau.Viz(containerDiv, url, options);
        // Create a viz object and embed it in the container div.
    }
</script>

<?php

get_footer(); ?>
