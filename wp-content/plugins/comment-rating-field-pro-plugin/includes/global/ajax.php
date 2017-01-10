<?php
/**
* AJAX class
* 
* @package Comment_Rating_Field_Pro
* @author Tim Carr
* @version 1.0
*/
class Comment_Rating_Field_Pro_AJAX {

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

        // Frontend AJAX Actions
        add_action( 'wp_ajax_comment_rating_field_pro_save_rating', array( $this, 'save_rating' ) );
        add_action( 'wp_ajax_nopriv_comment_rating_field_pro_save_rating', array( $this, 'save_rating' ) );

        // Admin AJAX Actions
        add_action( 'wp_ajax_comment_rating_field_pro_delete_all_ratings', array( &$this, 'delete_all_ratings' ) );

    }

    /**
    * If Jetpack Comments are enabled, ratings can't be POSTed.  Instead, we use JS
    * to make an AJAX call to store each rating in the options table.
    *
    * See includes/global/rating-input.php::save_ratings(), where we then read this
    * data to store it against the created comment
    *
    * @since 3.2.0
    */
    function save_rating() {

        // Run a security check first.
        check_ajax_referer( 'comment-rating-field-pro-plugin_nonce', 'nonce' );

        // Check we have required data
        if ( ! isset( $_POST['post_id'] ) ) {
            wp_die( 0 );
        }
        if ( ! isset( $_POST['rating'] ) ) {
            wp_die( 0 );
        }
        if ( ! isset( $_POST['field_id'] ) ) {
            wp_die( 0 );
        }

        // Get rating, field ID and IP address
        $post_id = absint( $_POST['post_id'] );
        $rating = absint( $_POST['rating'] );
        $field_id = absint( $_POST['field_id'] );
        $ip = Comment_Rating_Field_Pro_Common::get_instance()->get_user_ip_address();

        // Get option data
        $ratings = get_option( 'comment-rating-field-pro-plugin-ratings' );
        if ( ! is_array( $ratings ) ) {
            $ratings = array();
        }

        // Create IP key, if one doesn't exist
        if ( ! isset( $ratings[ $ip ] ) ) {
            $ratings[ $ip ] = array();
        }

        // Create post key, if one doesn't exist
        if ( ! isset( $ratings[ $ip ][ $post_id ] ) ) {
            $ratings[ $ip ][ $post_id ] = array();
        }

        // Store rating
        $ratings[ $ip ][ $post_id ][ $field_id ] = $rating;
        update_option( 'comment-rating-field-pro-plugin-ratings', $ratings );

        // Done
        wp_die( 1 );

    }

    /**
    * Deletes all Ratings on Comments associated with the given Post.
    * Also deletes the Post's metadata for ratings
    *
    * @since 3.2.0
    */
    function delete_all_ratings() {

        // Run a security check first.
        check_ajax_referer( 'comment-rating-field-pro-plugin_nonce', 'nonce' );

        // Check we have required data
        if ( ! isset( $_POST['post_id'] ) ) {
            wp_die( 0 );
        }

        // Get Post ID
        $post_id = absint( $_POST['post_id'] );

        // Delete rating metadata from comments and Post
        Comment_Rating_Field_Pro_Rating_Input::get_instance()->reset_comment_rating_by_post_id( $post_id );
        Comment_Rating_Field_Pro_Rating_Input::get_instance()->reset_post_rating_by_post_id( $post_id );

        // Done
        wp_die( 1 );

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