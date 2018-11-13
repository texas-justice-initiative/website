<?php
/**
 * Texas Justice Initiative functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Texas_Justice_Initiative
 */

if ( ! function_exists( 'tji_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function tji_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Texas Justice Initiative, use a find and replace
		 * to change 'tji' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'tji', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'tji' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'tji_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'tji_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function tji_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'tji_content_width', 640 );
}
add_action( 'after_setup_theme', 'tji_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function tji_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'About TJI', 'tji' ),
		'id'            => 'about',
		'description'   => esc_html__( 'Add widgets here to be displayed on the about tji pages.', 'tji' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'About the Data', 'tji' ),
		'id'            => 'about-data',
		'description'   => esc_html__( 'Add widgets here to be displayed on the about the data pages.', 'tji' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );


	register_sidebar( array(
		'name'          => esc_html__( 'Publications', 'tji' ),
		'id'            => 'publications',
		'description'   => esc_html__( 'Add widgets here to be displayed on the publications page.', 'tji' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

}
add_action( 'widgets_init', 'tji_widgets_init' );

//Register custom post types
function tji_create_posttypes() {
	
	//Visualization custom post type
	register_post_type( 'tji_viz', 
		array(
			'labels' => array (
				'name' => __( 'Visualizations' ),
				'singular_name' => __( 'Visualization' ),
				'add_new' => __( 'Add New', 'Visualization' ),
				'add_new_item' => __( 'Add New Visualization' ),
				'edit_item' => __( 'Edit Visualization' ),
				'new_item' => __( 'New Visualization' ),
				'view_item' => __( 'View Visualizations' ),
				'search_items' => __( 'Search Visualizations' ),
				'menu_name' => __( 'Visualizations' )
			),
			'public' => true,
			'has_archive' => true,
			'hierarchical' => true,
			'rewrite' => array( 'slug' => 'visualizations' ),
			'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' )
		)
	);

}

add_action( 'init', 'tji_create_posttypes' );

// Add custom meta box to upload visualization chart
function tji_custom_meta() {
	add_meta_box( 'tji_meta', 'Visualization Options', 'tji_viz_meta_box', 'tji_viz', 'normal', 'high' );
}

function tji_viz_meta_box() {
	$myPost = get_the_ID();
	$vizChart = get_post_meta( $myPost, 'tji_viz_chart', true );
		
	?>
	<div id="custom-page-options">
		<div class="add-image">
		    <label for="image_url">Add Visualization Chart</label>
		    <input type="text" name="chart_url" id="chart_url" class="regular-text" value="<?php echo $vizChart; ?>">
		    <input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="Upload Image">
		    <input type="button" name="remove-btn" id="remove-btn" class="button-secondary" value="Remove Image">
		</div>
		<div class="viz-chart-container">
			<img class="viz-chart" src="<?php echo $vizChart; ?>">
		</div>
	</div>	
	<?php
}

add_action( 'add_meta_boxes', 'tji_custom_meta' );

function save_meta() {	
	$myPost = get_the_ID();
		
	if ( isset( $_REQUEST['chart_url'] ) ) {
        update_post_meta( $myPost, 'tji_viz_chart', sanitize_text_field( $_REQUEST['chart_url'] ) );
    }
}

add_action( 'save_post', 'save_meta' );

/**
 * Enqueue scripts and styles.
 */
function tji_scripts() {
	wp_enqueue_style( 'tji-style', get_stylesheet_uri() );

	wp_enqueue_script( 'tji-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'tji-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );
	
	wp_enqueue_script( 'tji-general', get_template_directory_uri() . '/js/tji.js', array('jquery'), '20180601', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'tji_scripts' );

function load_wp_media_files() {
    wp_enqueue_media();
    wp_enqueue_style('adminstyle', get_template_directory_uri() . '/css/tji.admin.css', array(), '1.0.0', 'all');
    wp_enqueue_script('adminjs', get_template_directory_uri() . '/js/tji.admin.js', array(), '1.0.0', true);
}
add_action( 'admin_enqueue_scripts', 'load_wp_media_files' );


/**
 * Load datasets where needed
 */
function tji_data_load() {
	global $post;

	if ( $post->post_name == 'data') {
		wp_enqueue_script( 'tji-data-explore', get_template_directory_uri() . '/js/tji-datasets-explore.js', array('jquery'), '20180601', true );
	}	
}
add_action( 'wp_enqueue_scripts', 'tji_data_load' );

/**
 * Create rest endpoint for mailchimp sign up
 */

require('inc/vendor/MailChimp.php'); 
use \TJI\MailChimp\MailChimp;

function mc_signup(WP_REST_Request $request) {
	// api key for mailchimp
	$mc_apikey = getenv('MAILCHIMP_API_KEY');
	//id for mailchimp email list: Texas Justice Initiative
	$mc_listid = getenv('MAILCHIMP_NEWSLETTER_LIST');

	$MC = new MailChimp($mc_apikey);

	$result = $MC->post("lists/$mc_listid/members", [
	        'email_address' => sanitize_text_field($request->get_param( 'email' )),
	        'status'        => 'subscribed',
	        'merge_fields' 	=> ['FNAME'=>sanitize_text_field($request->get_param( 'fname' ))]
	      ]);

	if ($MC->success()) {
	  return $result['merge_fields']['FNAME']; 
	} else {
		$error = json_decode($MC->getLastResponse()['body']);
	  return new WP_Error('mailchimp_post_error', $error->detail, ['status' => $error->status]);
	}
}

add_action( 'rest_api_init', function () {
  register_rest_route( 'newsletter', '/signup/', [
    'methods' => 'POST',
    'callback' => 'mc_signup',
  ]);
});


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Functions for AJAX loading homepage visualization sections
 */

//require get_template_directory() . '/inc/loadmore-functions.php';

