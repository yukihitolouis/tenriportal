<?php
/**
* Widgets class
* 
* @package Comment_Rating_Field_Pro
* @author Tim Carr
* @version 1.0
*/
class Comment_Rating_Field_Pro_Widgets {

    /**
     * Holds the class object.
     *
     * @since 3.2.6
     *
     * @var object
     */
    public static $instance;

	/**
	* Constructor
	*/
	function __construct() {

		add_action( 'widgets_init', array( $this, 'register_widgets' ) );

	}

    /**
    * Registers widgets
    */
    function register_widgets() {

        register_widget( 'CRFPTopRatedPosts' );
    }

    /**
     * Returns the singleton instance of the class.
     *
     * @since 3.2.6
     *
     * @return object Class.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {
            self::$instance = new self;
        }

        return self::$instance;

    }

}

/**
* Top Rated Posts Widget
*
* @since 3.2.0
*/
class CRFPTopRatedPosts extends WP_Widget {
    /**
    * Constructor.  Sets up Widget jobs, and adds it to the WP_Widget class
    */
    function __construct() {
	    
        $widget_ops = array(
        	'classname' => 'widget_crfp_top_rated', 
        	'description' => __( 'Display the top rated Posts for any given taxonomy.', 'comment-rating-field-pro-plugin' ) );
        
        parent::__construct(
        	'crfp-top-rated', 
        	__('CRFP Top Rated Posts', 'comment-rating-field-pro-plugin'), 
        	$widget_ops
        );
        
        $this->alt_option_name = 'widget_crfp_top_rated_widget';

    }

    /**
    * Displays the front end widget
    * 
    * @param string $args Native Wordpress Vars
    * @param string $instance Configurable jobs
    */
    function widget( $args, $instance ) { 

    	// Check if we have selected a post type or a term
        if ( ! isset( $instance['postTypeOrTerm'] ) ) {
            return;
        }

        // Get Post Type and Term
        if ( strpos( $instance['postTypeOrTerm'], ':' ) !== false) {
            list( $post_type, $taxonomy, $term ) = explode( ':', $instance['postTypeOrTerm'] );
        } elseif ( strpos( $instance['postTypeOrTerm'], '_' ) !== false) {
    		list( $post_type, $taxonomy, $term ) = explode( '_', $instance['postTypeOrTerm'] );
    	} else {
    		$post_type = $instance['postTypeOrTerm'];
    	}

    	// Build WP_Query args
    	$post_args = array(
    		'post_type' 	=> array( $post_type ),
    		'post_status' 	=> 'publish',
    		'meta_key' 		=> 'crfp-average-rating',
    		'orderby' 		=> 'meta_value_num',
    		'order' 		=> 'DESC',
    		'posts_per_page'=> ( is_numeric( $instance['limit'] ) ? $instance['limit'] : 5 ),
    	);

    	// Query by taxonomy and term
    	if ( isset( $taxonomy ) && ! empty( $taxonomy ) && isset( $term ) && ! empty( $term ) ) {
    		$post_args['tax_query'] = array(
    			array(
    				'taxonomy' 	=> $taxonomy,
    				'field' 	=> 'id',
    				'terms' 	=> array( $term ),
    			)
    		);
    	}

    	// Run Query
    	$posts = new WP_Query( $post_args );
 		?>
 		<div class="widget widget_crfp_top_rated">
 			<?php
 			if ( ! empty( $instance['title'] ) ) { 
 				$title = apply_filters( 'widget_title', $instance['title'] );
                echo $args['before_title'] . $title . $args['after_title'];
 			}
 			?>
	 		<ul>
				<?php
				if ( is_array( $posts->posts ) && count( $posts->posts ) > 0) {
					foreach ( $posts->posts as $key => $post ) {
						// Get average rating
						$average_rating = get_post_meta( $post->ID, 'crfp-average-rating', true );
						echo ('	<li>
									<a href="' . get_permalink( $post->ID ) . '" title="' . $post->post_title . '">' . $post->post_title . '</a>
									<div class="rating-container">
										<span class="crfp-rating crfp-rating-' . str_replace( '.', '-', $average_rating ) . '" style="width:' . ( $average_rating * 16 ) . 'px"></span>
									</div>
								</li>');
					}
				}
				?>
			</ul>
		</div>
		<?php

    }

    /**
    * Process the new settings before they're sent off to be saved
    * 
    * @param array $new_instance Array of settings we're about to process, before saving
    * @param array $old_instance Old Settings
    * @return array New Settings to be saved
    */
    function update( $new_instance, $old_instance ) {    

        return $new_instance;

    }
    
    /**
    * Creates the edit form for the widget
    * 
    * @param array $instance Current Settings
    */
    function form( $instance ) {
    	
		// Get Post Types
		$post_types = Comment_Rating_Field_Pro_Common::get_instance()->get_post_types();

        // Get excluded Taxonomies
        $excluded_taxonomies = Comment_Rating_Field_Pro_Common::get_instance()->get_excluded_taxonomies(); 
        
        // Setup options array
        $options = array();

        // Iterate through Post Types
		foreach ( $post_types as $type => $post_type ) {
    		$options[ $type ] = $post_type->labels->name; // Add post type to options list

            // Get taxonomies for this Post Type
            $taxonomies = get_object_taxonomies( $type, 'objects' );

    		// Skip if no taxonomies for this Post Type exist
    		if ( ! is_array( $taxonomies ) || count( $taxonomies ) == 0 ) {
                continue;
            }

            // Iterate through taxonomies
            foreach ( $taxonomies as $tax => $taxonomy ) {
                // Skip if this is an excluded taxonomy
                if ( in_array( $tax, $excluded_taxonomies ) ) {
                    continue;
                }

                // Get taxonomy terms
                $terms = get_terms( $tax );
                
                // Skip if no taxonomy terms
                if ( ! is_array( $terms ) || count( $terms ) == 0 ) {
                    continue;
                }

                // Iterate through taxonomy terms
                foreach ( $terms as $term_key => $term ) {
                    $options[ $type . ':' . $tax . ':' . $term->term_id ] = $post_type->labels->name . ': ' . $taxonomy->labels->name . ': ' . $term->name;        
                }
            } 
    	}

        echo (' <p>
                    <label for="' . $this->get_field_id( 'title' ) . '">
                        Title:
                        <input type="text" name="' . $this->get_field_name( 'title' ).'" id="' . $this->get_field_id( 'title' ) . '" value="' . ( isset( $instance['title'] ) ? $instance['title'] : '' ) .'" class="widefat" />
                    </label>
                </p>
                <p>
                   <label for="' . $this->get_field_id( 'postTypeOrTerm' ) . '">
                        Post Type / Taxonomy / Term:
                        <select name="' . $this->get_field_name( 'postTypeOrTerm' ) . '" id="' . $this->get_field_id( 'postTypeOrTerm' ) . '" size="1">' );
       
        foreach ( $options as $key=>$option ) {
			echo ('     	<option value="' . $key . '"' . ( ( isset( $instance['postTypeOrTerm'] ) && $instance['postTypeOrTerm'] == $key ) ? ' selected' : '' ).'>' . $option . '</option>' ); 
        }                    
       
        echo ('         </select>
                    </label>
                </p>
                <p>
                    <label for="' . $this->get_field_id( 'limit' ) . '">
                        Number of Posts:
                        <input type="text" name="' . $this->get_field_name( 'limit' ) . '" id="' . $this->get_field_id( 'limit' ) . '" value="' . ( isset( $instance['limit'] ) ? $instance['limit'] : '' ) . '" class="widefat" />
                    </label>
                </p>' );
    }
    
} // Close CRFPTopRatedPosts