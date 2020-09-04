<?php
function monno_theme_support() 
{
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );

	// Set post thumbnail size.
	//set_post_thumbnail_size( 1200, 9999 );

	// add_image_size( 'monno-fullscreen', 1920, 968 );
	add_image_size( 'banner', 1920, 801 );
	add_image_size( 'profile-459x459', 459, 459 );
	add_image_size( 'profile-637x841', 637, 841 );
	add_image_size( 'admission-506x404', 506, 404 );
	add_image_size( 'gallery-354x245', 354, 245 );
	add_image_size( 'gallery-354x245', 360, 205 );
	add_image_size( 'contact-138x176', 138, 176 );
	add_image_size( 'about-625x639', 625, 639 );
	
	// Custom logo.
	$logo_width  = 300;
	$logo_height = 200;

	// If the retina setting is active, double the recommended width and height.
	if ( get_theme_mod( 'retina_logo', false ) ) {
		$logo_width  = floor( $logo_width * 2 );
		$logo_height = floor( $logo_height * 2 );
	}

	add_theme_support(
		'custom-logo',
		array(
			'height'      => $logo_height,
			'width'       => $logo_width,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_theme_support( 'title-tag' );

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
		)
	);

	load_theme_textdomain( 'monno' );
	add_theme_support( 'align-wide' );
}
add_action( 'after_setup_theme', 'monno_theme_support' );

/**
 * Register and Enqueue Styles.
 */
function monno_register_styles()
{
	$theme_version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'montserrat', 'https://fonts.googleapis.com/css?family=Montserrat:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900&display=swap', $theme_version );
	wp_enqueue_style( 'linearicons', get_template_directory_uri() . '/assets/css/linearicons.css', $theme_version );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', $theme_version );
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css', $theme_version );
	wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/assets/css/magnific-popup.css', $theme_version );
	wp_enqueue_style( 'owlcarousel', get_template_directory_uri() . '/assets/css/owl.carousel.css', $theme_version );
	wp_enqueue_style( 'aos','https://unpkg.com/aos@2.3.1/dist/aos.css', $theme_version );
	wp_enqueue_style( 'main', get_template_directory_uri() . '/assets/css/main.css', $theme_version );
	wp_enqueue_style( 'responsive', get_template_directory_uri() . '/assets/css/responsive.css', $theme_version );
	wp_enqueue_style( 'style', get_stylesheet_uri(), array(), $theme_version );
}
add_action( 'wp_enqueue_scripts', 'monno_register_styles' );

/**
 * Register and Enqueue Scripts.
 */
function monno_register_scripts()
{
	$theme_version = wp_get_theme()->get( 'Version' );
	wp_enqueue_script( 'jquery-js', get_template_directory_uri() . '/assets/js/jquery-2.2.4.min.js', array(), $theme_version, true );
	wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array(), $theme_version, true );
	wp_enqueue_script( 'magnific-popup-js', get_template_directory_uri() . '/assets/js/jquery.magnific-popup.min.js', array(), $theme_version, true );
	wp_enqueue_script( 'counterup-js', get_template_directory_uri() . '/assets/js/jquery.counterup.min.js', array(), $theme_version, true );
	wp_enqueue_script( 'ice-select-js', get_template_directory_uri() . '/assets/js/jquery.nice-select.min.js', array(), $theme_version, true );
	wp_enqueue_script( 'owl-carousel-js', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array(), $theme_version, true );
	wp_enqueue_script( 'aos-js', 'https://unpkg.com/aos@2.3.1/dist/aos.js', array(), $theme_version, true );
	wp_enqueue_script( 'main-js', get_template_directory_uri() . '/assets/js/main.js', array(), $theme_version, true );
	wp_script_add_data( 'monno-js', 'async', true );
}
add_action( 'wp_enqueue_scripts', 'monno_register_scripts' );

/**
 * Register navigation menus.
 */
function monno_menus() 
{
	$locations = array(
		'primary'  => __( 'Primary menu', 'monno' ),
		'secondary'  => __( 'Secondary', 'monno' ),
	);

	register_nav_menus( $locations );
}
add_action( 'init', 'monno_menus' );

/**
 * options page
 */
if ( function_exists('acf_add_options_page') ) 
{
	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
}
/**
 * limit excerpt length 
 * */
function monno_excerpt_length( $length ) 
{
	return 20;
}
add_filter( 'excerpt_length', 'monno_excerpt_length', 999 );

/**debug */
function prepare_data($arr){
	echo '<pre>';
	var_dump($arr);
	echo '</pre>';
}

include get_template_directory().'/wp-helpers.php';

