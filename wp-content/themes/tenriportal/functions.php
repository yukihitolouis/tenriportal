<?php
	//include 'ChromePhp.php';
	
	add_action('wp_head', 'mbe_wp_head');
	function mbe_wp_head(){
    echo '<style>'.PHP_EOL;
    echo 'body{ padding-top: 70px !important; }'.PHP_EOL;
    // Using custom CSS class name.
    echo 'body.body-logged-in .navbar-fixed-top{ top: 28px !important; }'.PHP_EOL;
    // Using WordPress default CSS class name.
    echo 'body.logged-in .navbar-fixed-top{ top: 28px !important; }'.PHP_EOL;
    echo '</style>'.PHP_EOL;
	}

	function tp_theme_setup(){
		require_once('wp-advanced-search/wpas.php');
	}
	add_action( 'after_setup_theme', 'tp_theme_setup' );

	function tp_search_form() {
    $args = array();
    $args['wp_query'] = array('post_type' => 'book',
                              'posts_per_page' => 5
                              );
    $args['fields'][] = array('type' => 'search',
                              'title' => 'Search',
                              'placeholder' => 'Enter search terms...');
/*    $args['fields'][] = array('type' => 'taxonomy',
                              'taxonomy' => 'category',
                              'format' => 'select');*/
    $args['form']['ajax'] = array(
			'enabled' => true
		);
		$args['debug'] = true;
    register_wpas_form('tp-form', $args);    
	}
	add_action('init', 'tp_search_form');
	
	function mytheme_comment($comment, $args, $depth) {
    if ( 'div' === $args['style'] ) {
        $tag       = 'div';
        $add_below = 'comment';
    } else {
        $tag       = 'div';
        $add_below = 'div-comment';
    }
    ?>
    
    
    <div id="div-comment-<?php comment_ID() ?>" class="comment-body review-post">
    
    	<div class="col-lg-2">
	    	<div class="user-meta">
		    	<div class="comment-author vcard user-thumb img-container">
		        	<?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, 256 ); ?>
		    	</div>
				<div class="user-info">
		        	<?php printf( __( '<p class="username">%s</p>' ), get_comment_author_link() ); ?>
		    
					<?php if ( $comment->comment_approved == '0' ) : ?>
						<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></em>
						<br />
					<?php endif; ?>
			
					<div class="review-postdate">
				        <?php
				        /* translators: 1: date, 2: time */
				        echo "Posted on: "; printf(get_comment_date() ); ?>
				        
				        <?php //edit_comment_link( __( '(Edit)' ), '  ', '' );
				        ?>
				    </div>
		    	</div>
	    	</div>
    	</div>
    
	    <div class="col-lg-10">
		    

		    <?php comment_text(); ?>
	    </div>
		
		<div class="col-lg-10 col-lg-offset-1 divider">
	      	<hr />
      	</div>
    
    </div>
    <?php } ?>
    
    
 
 
 <?php
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

add_theme_support('post-thumbnails');

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
			if( !in_array( $book_author, $book_authors ) ){
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

	//$output = '<form action="' . esc_url( home_url() ) . '" method="GET" role="search">';
	$output = '<div class="ytselectbox">' . $select_book_author . '</div>';
	$output .= '<div class="ytselectbox">' . $select_genre . '</div>';
	$output .= '<input type="hidden" name="post_type" value="book" />';
	//$output .= '<p><input type="submit" value="Go!" class="button" /></p>';
	//	$output .= '</form>';

	return $output;
}

?>