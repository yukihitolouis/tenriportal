<?php

/**
 * Send debug code to the Javascript console
 */ 
function debug_to_console($text, $data) {

    if(is_array($data) || is_object($data))
	{
		echo("<script>console.log('PHP: ". $text . json_encode($data)."');</script>");
	} else {
		echo("<script>console.log('PHP: ". $text . $data."');</script>");
	}
}

/**
 * pathways functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package pathways
 */

if ( ! function_exists( 'pathways_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function pathways_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on pathways, use a find and replace
	 * to change 'pathways' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'pathways', get_template_directory() . '/languages' );

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
		'primary' => esc_html__( 'Primary', 'pathways' ),
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

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'pathways_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif;
add_action( 'after_setup_theme', 'pathways_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function pathways_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'pathways_content_width', 640 );
}
add_action( 'after_setup_theme', 'pathways_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function pathways_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'pathways' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'pathways' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'pathways_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function pathways_scripts() {
	wp_enqueue_style( 'pathways-style', get_stylesheet_uri() );

	wp_enqueue_script( 'pathways-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'pathways-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'pathways_scripts' );

function yt_theme_styles () {
	wp_enqueue_style('bootstrap_css', get_template_directory_uri() . '/css/bootstrap.css');

	wp_enqueue_style('bootstrap_theme_css', get_template_directory_uri() . '/css/bootstrap-theme.css');

	wp_enqueue_style('main_css', get_template_directory_uri() . '/css/main.css');

	wp_enqueue_style('style_css', get_template_directory_uri() . '/style.css');
};

add_action('wp_enqueue_scripts', 'yt_theme_styles');

function yt_theme_js () {

	wp_enqueue_script('modernizr_js',  get_template_directory_uri() . '/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js');
	
	wp_enqueue_script('bootstrap_js',  get_template_directory_uri() . '/js/vendor/bootstrap.min.js', array('jquery'), '', true);

	wp_enqueue_script('main_js',  get_template_directory_uri() . '/js/main.js', array('jquery'), '', true);

};

add_action('wp_enqueue_scripts', 'yt_theme_js');

/**
 * Register custom query vars
 *
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/query_vars
 */
function yt_register_query_vars( $vars ) {
	$vars[] = 'book_author';
	return $vars;
} 
add_filter( 'query_vars', 'yt_register_query_vars' );

/**
 * Build a custom query based on several conditions
 * The pre_get_posts action gives developers access to the $query object by reference
 * any changes you make to $query are made directly to the original object - no return value is requested
 *
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts
 *
 */
function yt_pre_get_posts( $query ) {
	// check if the user is requesting an admin page 
	// or current query is not the main query
	if ( is_admin() || ! $query->is_main_query() ){
		return;
	}

	$meta_query = array();

	// add meta_query elements
	if( !empty( get_query_var( 'book_author' ) ) ){
		$meta_query[] = array( 'key' => 'book_author', 'value' => get_query_var( 'book_author' ), 'compare' => 'LIKE' );
	}

	if( count( $meta_query ) > 1 ){
		$meta_query['relation'] = 'AND';
	}

	if( count( $meta_query ) > 0 ){
		$query->set( 'meta_query', $meta_query );
	}

}
add_action( 'pre_get_posts', 'yt_pre_get_posts', 1 );

/**
 * Short code to insert a search form
 */
function yt_setup() {
	add_shortcode( 'book_search_form', 'book_search_form' );
}
add_action( 'init', 'yt_setup' );

function book_search_form( $args ){

	// The Query
	// meta_query expects nested arrays even if you only have one query
	$yt_query = new WP_Query( array( 'post_type' => 'book',
		'post_status' => 'publish') );

	// The Loop
	if ( $yt_query->have_posts() ) {
		$book_authors = array();
		while ( $yt_query->have_posts() ) {
			$yt_query->the_post();
			$book_author = get_post_meta( get_the_ID(), 'book_author', true );
			
			// populate an array of all occurrences (non duplicated)
			if( !in_array( $book_author, $book_authors)  && $book_author != '' ){
				$book_authors[] = $book_author;    
			}
		}
	} else{
		echo 'No book reviews yet!';
		return;
	}

	/* Restore original Post Data */
	wp_reset_postdata();

	if( count($book_authors) == 0){
		return;
	}

	asort($book_authors);
	    
	$select_book_author = '<select name="book_author" style="width: 100%">';
	$select_book_author .= '<option value="" selected="selected">' . __( 'Select author', 'pathways_plugin' ) . '</option>';
	foreach ($book_authors as $book_author ) {
		$select_book_author .= '<option value="' . $book_author . '">' . $book_author . '</option>';
	}
	$select_book_author .= '</select>' . "\n";

	reset($book_authors);

	$args = array( 'hide_empty' => false );
	$genre_terms = get_terms( 'genre', $args );
	if( is_array( $genre_terms ) ){
		$select_genre = '<select name="genre" style="width: 100%">';
		$select_genre .= '<option value="" selected="selected">' . __( 'Select genre', 'pathways_plugin' ) . '</option>';
		foreach ( $genre_terms as $term ) {
			$select_genre .= '<option value="' . $term->slug . '">' . $term->name . '</option>';
		}
		$select_genre .= '</select>' . "\n";
	}

	$output = '<form action="' . esc_url( home_url() ) . '" method="GET" role="search">';
	$output .= '<div class="ytselectbox">' . $select_book_author . '</div>';
	$output .= '<div class="ytselectbox">' . $select_genre . '</div>';
	$output .= '<input type="hidden" name="post_type" value="book" />';
	$output .= '<p><input type="submit" value="Go!" class="button" /></p></form>';

	return $output;
}

 function template_chooser($template)   
{    
  global $wp_query;   
  $post_type = get_query_var('post_type');
    
  if( $post_type == 'book' )   
  {
    return locate_template('archive-book_review.php');  //  archive-book_review.php
  }   
  return $template;   
}
add_filter('template_include', 'template_chooser');   



/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
