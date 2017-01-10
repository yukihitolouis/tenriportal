<?php
/**
* Plugin Name: Comment Rating Field Pro - Remove Comment Rating on Comments
* Plugin URI: http://www.wpcube.co.uk/plugins/comment-rating-field-pro-plugin
* Version: 1.0.0
* Author: WP Cube
* Author URI: http://www.wpcube.co.uk
* Description: Removes the Ratings on Comment Text.  
*/

/**
 * Removes all star ratings from comments left by reviewers.
 *
 * Developers can then use the following code within the comment loop to output the rating:
 * Comment_Rating_Field_Pro_Rating_Output::get_instance()->display_comment_rating( get_comment_ID() );
 */
/*add_action( 'wp', 'remove_display_comment_rating' );
function remove_display_comment_rating() {

	$instance = Comment_Rating_Field_Pro_Rating_Output::get_instance();
	remove_action( 'comment_text', array( $instance, 'display_comment_rating' ) );

	// Remember, you now need to use the below code if you want to display commments:
	// Comment_Rating_Field_Pro_Rating_Output::get_instance()->display_comment_rating( get_comment_ID() );

}*/