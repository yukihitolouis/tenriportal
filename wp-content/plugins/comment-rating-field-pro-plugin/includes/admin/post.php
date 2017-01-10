<?php
/**
* Post class
* 
* @package Comment_Rating_Field_Pro
* @author Tim Carr
* @version 1.0
*/
class Comment_Rating_Field_Pro_Post {

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
	*
    * @since 3.2.0
	*/
	function __construct() {

		// Actions and Filters
		add_action( 'admin_menu', array( $this, 'register_meta_box' ) );
        // add_action( 'save_post', array( $this, 'save_post' ) );

        // Get all Post Types with Comments Enabled
        $post_types = Comment_Rating_Field_Pro_Common::get_instance()->get_post_types();
        foreach ( (array) $post_types as $post_type ) {
            add_filter( 'manage_edit-' . $post_type->name . '_columns', array( $this, 'admin_columns' ) );
            add_action( 'manage_' . $post_type->name . '_posts_custom_column', array( $this, 'admin_columns_output'), 10, 2 );
        }
        
	}

    /**
    * Adds columns to all Post Types that have comments enabled
    * 
    * @since 3.2.7
    *
    * @param array $columns Columns
    * @return array         New Columns
    */
    function admin_columns( $columns ) {

        $columns['crfp_average_rating'] = __( 'Average Rating', 'comment-rating-field-pro-plugin' );
        $columns['crfp_total_ratings']  = __( 'Total Ratings', 'comment-rating-field-pro-plugin' );

        return $columns;

    }

    /**
    * Manages the data to be displayed within a column within 
    * the WordPress Administration List Table
    * 
    * @since 3.2.7
    *
    * @param string $column_name    Column Name
    * @param int    $post_id        Post ID
    */
    function admin_columns_output( $column_name, $post_id ) {

        switch ( $column_name ) {

            /**
            * Average Rating
            */
            case 'crfp_average_rating':
                echo get_post_meta( $post_id, 'crfp-average-rating', true );
                break;

            /**
            * Total Ratings
            */
            case 'crfp_total_ratings':
                echo get_post_meta( $post_id, 'crfp-total-ratings', true );
                break;

        }

    }

	/**
    * Registers a meta box on Posts, Pages and CPTs, for displaying a reset ratings option.
    *
    * @since 3.2.0
    */
    function register_meta_box() {

    	// Get post types
    	$post_types = Comment_Rating_Field_Pro_Common::get_instance()->get_post_types();
    	if ( ! is_array( $post_types ) ) {
    		return;
    	}

    	foreach ( $post_types as $post_type => $data ) {
    		// Register meta box on this Post Type
        	add_meta_box( 'comment-rating-field-pro-plugin-ratings', __( 'Reset Ratings', 'comment-rating-field-pro-plugin' ), array( $this, 'display_meta_box' ), $post_type, 'side', 'low' );
    	}

    }

    /**
    * Outputs a "Reset Ratings" option
    *
    * @since 3.2.0
    */
    function display_meta_box( $post ) {

    	// Check if the Post has any comments
        $comments = get_comments( array(
            'post_id'   => $post->ID,
            'meta_query'=> array(
                array(
                    'key'       => 'crfp',
                    'compare'   => 'EXISTS',
                ),
            ),
        ) );
        ?>
        <div class="option">
            <p>
                <?php
                if ( count( $comments ) > 0 ) {
                    _e( 'Use this option to delete all ratings on comments made to this Post, as well as deleting the Post rating.', 'comment-rating-field-pro-plugin' );
                } else {
                    _e( 'No comments with ratings have been made against this Post yet.', 'comment-rating-field-pro-plugin' );
                }
                ?>
            </p>
        </div>
        <?php

        // Delete Button
        if ( count( $comments ) > 0 ) {
            ?>
            <div class="option">
                <p>
                    <a href="#" class="button button-red crfp-delete-ratings"><?php _e( 'Delete All Ratings', 'comment-rating-field-pro-plugin' ); ?></a>
                </p>
            </div>
            <?php
        }

	}

    /**
    * When saving any Post, if the total and average rating meta keys do not exist, set them at zero
    * This allows Posts to be sorted by developers without 'losing' data
    *
    * @since 3.2.7
    *
    * @param    int     $post_id    Post ID
    * @return
    */
    function save_post( $post_id ) {

        // Don't do anything if we're creating a new Post
        if ( ! isset( $_POST ) || empty( $_POST ) ) {
            return;
        }

        // Check for existence of meta keys
        $total_ratings           = get_post_meta( $post_id, 'crfp-total-ratings', true );
        $average_rating          = get_post_meta( $post_id, 'crfp-average-rating', true );

        if ( $total_ratings != '' || $average_rating != '' ) {
            return;
        }

        // Set zero values for total and average rating
        // Commented out as it breaks shit
        update_post_meta( $post_id, 'crfp-total-ratings', 0 );
        update_post_meta( $post_id, 'crfp-average-rating', 0 );

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