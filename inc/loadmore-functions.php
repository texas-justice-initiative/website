<?php
	/* 
		These functions retrieve visualization custom posts and send them to JavaScript
		for AJAX loading.
	*/

function tji_my_load_more_scripts() {
 
	global $wp_query; 
	$viz_args = array(
		'post_type' => 'tji_viz',
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'paged' => (get_query_var('paged')) ? get_query_var('paged') : 1,
		'posts_per_page' => 1
	);

	$viz_query = new WP_Query( $viz_args );
 
	// register our main script but do not enqueue it yet
	wp_register_script( 'my_loadmore', get_stylesheet_directory_uri() . '/js/loadmore.js', array('jquery') );
 
	// we have to pass parameters to myloadmore.js script but we can get the parameters values only in PHP
	// the proper way is the WordPress function wp_localize_script()
	wp_localize_script( 'my_loadmore', 'tji_loadmore_params', array(
		'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
		'posts' => json_encode( $viz_query->query_vars ), // everything about your loop is here
		'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
		'max_page' => $viz_query->max_num_pages
	) );
 
 	wp_enqueue_script( 'my_loadmore' );
}
 
add_action( 'wp_enqueue_scripts', 'tji_my_load_more_scripts' );

function tji_loadmore_ajax_handler(){
 
	// prepare our arguments for the query
	$args = json_decode( stripslashes( $_POST['query'] ), true );
	$args['paged'] = $_POST['page'] + 1; // we need next page to be loaded
	$args['post_status'] = 'publish';
 
	// it is always better to use WP_Query but not here
	query_posts( $args );
 
	if( have_posts() ) :
 
		// run the loop
		while( have_posts() ): the_post();
 
			get_template_part( 'template-parts/content', get_post_type() );
 
		endwhile;
 
	endif;
	die; // exit the script and even no wp_reset_query() required!
}
 
add_action('wp_ajax_loadmore', 'tji_loadmore_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_loadmore', 'tji_loadmore_ajax_handler'); // wp_ajax_nopriv_{action}
