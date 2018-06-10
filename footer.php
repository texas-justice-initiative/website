<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Texas_Justice_Initiative
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<?php the_custom_logo(); ?>
		<div class="site-info">
			<p>Copyright 2018 Texas Justice Initiative. All rights reserved. <a href="<?php echo site_url(); ?>/disclaimer">Disclaimer</a>.</p>
			<p>TJI <a href="<?php echo bloginfo('url') . '/thanks/'; ?>">appreciates</a> your creativity and talent.</p>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
