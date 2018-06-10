<?php
/**
 * The sidebar containing the about page widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Texas_Justice_Initiative
 */

if ( ! is_active_sidebar( 'about' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area">
	<?php dynamic_sidebar( 'about' ); ?>
</aside><!-- #secondary -->
