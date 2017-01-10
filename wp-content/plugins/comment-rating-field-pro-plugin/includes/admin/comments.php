<?php
/**
* Comments class
* 
* @package Comment_Rating_Field_Pro
* @author Tim Carr
* @version 1.0
*/
class Comment_Rating_Field_Pro_Admin_Comments  {

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
		add_action( 'wp_set_comment_status', array( $this, 'update_post_rating_by_comment_id') ); // Recalculate average rating on comment approval / hold / spam
		add_action( 'trash_comment', array( $this, 'update_post_rating_by_comment_id') ); // Recalculate average rating on comment -> trash
		add_action( 'delete_comment', array( $this, 'update_post_rating_by_comment_id') ); // Recalculate average rating on trash -> delete
		add_action( 'untrashed_comment', array( $this, 'update_post_rating_by_comment_id' ) ); // Recalculate average rating on trash -> restore
				
	}

	/**
    * Registers a meta box on Comments, for displaying the rating fields
    *
    * @since 3.2.0
    */
    function register_meta_box() {

        // Register meta box on Comments
        add_meta_box( 'comment-rating-field-pro-plugin-ratings', 'Comment Rating Field Pro', array( $this, 'display_meta_box' ), 'comment', 'normal', 'low' );
        
    }

    /**
    * Outputs the comment rating form when editing a WordPress Comment
    *
    * @since 3.2.0
    *
    * @param WP_Comment $comment Comment
    */
    function display_meta_box( $comment ) {

    	// Get Post ID and Comment ID
    	$post_id = $comment->comment_post_ID;
    	$comment_id = $comment->comment_ID;

    	// Check if this Comment has any groups
    	$group = Comment_Rating_Field_Pro_Groups::get_instance()->get_group_by_post_id( $post_id );
    	if ( ! $group ) {
			?>
			<div class="option">
				<p>
					<?php _e( 'No Rating Fields apply to this Post.', 'comment-rating-field-pro-plugin' ); ?>
				</p>
			</div>
			<?php
		}
    
    	// Get comment meta
    	$ratings = get_comment_meta( $comment->comment_ID, 'crfp', true );
    	if ( ! is_array( $ratings ) || count( $ratings ) == 0 ) {
    		?>
			<div class="option">
				<p>
					<?php _e( 'No ratings were left by this user.', 'comment-rating-field-pro-plugin' ); ?>
				</p>
			</div>
			<?php
    	}
	    	
    	// Localize JS
    	wp_localize_script( 'comment-rating-field-pro-plugin', 'crfp', array(
    		'disable_replies' 		=> $group['ratingInput']['disableReplies'],
    		'enable_half_ratings' 	=> $group['ratingInput']['enableHalfRatings'],
    	) );

	    // Get fields and output
	    ?>
        <div class="option">
            <p class="description">
                <?php _e( 'Hover the mouse cursor over the ratings below, and click the new star rating to change them.', 'comment-rating-field-pro-plugin' ); ?>
            </p>
        </div>
		<div class="option">
			<p>
				<?php echo Comment_Rating_Field_Pro_Rating_Output::get_instance()->build_comment_form_html( $group, $ratings ); ?>
			</p>
		</div>
    	<?php

	}

	/**
	* Alias function to update a Post's rating by comment ID
	*
    * @since 3.2.0
	*
	* @param int $comment_id Comment ID
	*/
	function update_post_rating_by_comment_id( $comment_id ) {

		return Comment_Rating_Field_Pro_Rating_Input::get_instance()->update_post_rating_by_comment_id( $comment_id );

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