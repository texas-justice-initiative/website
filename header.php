<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Texas_Justice_Initiative
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119932656-1"></script>
	<script>
	 window.dataLayer = window.dataLayer || [];
	 function gtag(){dataLayer.push(arguments);}
	 gtag('js', new Date());
	
	 gtag('config', 'UA-119932656-1');
	</script>

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	
	<!-- Load Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet"> 

	<!-- Swiper Requirements -->
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/swiper-4.3.0/dist/css/swiper.min.css">
	<script src="<?php echo get_template_directory_uri(); ?>/swiper-4.3.0/dist/js/swiper.min.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/swiper-4.3.0/dist/js/swiper.esm.bundle.js"></script>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="page" class="site<?php if (!is_front_page()) { echo ' subpage'; }?>">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'tji' ); ?></a>

	<header id="masthead" class="site-header">

		<div class="site-branding">
			<?php
			the_custom_logo();
			$tji_description = get_bloginfo( 'description', 'display' );
			?>
		</div><!-- .site-branding -->
		
		<nav id="site-navigation" class="main-navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Menu', 'tji' ); ?></button>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'menu-1',
				'menu_id'        => 'primary-menu',
			) );
			?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
