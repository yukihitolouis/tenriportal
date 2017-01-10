<?php
/**
* Rating Input class
* 
* @package Comment_Rating_Field_Pro
* @author Tim Carr
* @version 1.0
*/
class Comment_Rating_Field_Pro_Rating_Input {

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
		add_action( 'pre_comment_on_post', array( $this, 'pre_save_rating' ) ); // Check if rating can be saved on new comment
		add_action( 'comment_post', array( $this, 'save_rating' ) ); // Save Rating Field on new comment
		add_action( 'edit_comment', array( $this, 'save_rating' ) ); // Save Rating Field on editing existing comment

	}

	/**
	* Performs some checks before allowing a comment to be posted:
	* - If rating left for a rating group that limits ratings to 1 per person, check this isn't a 2nd rating
	* - WooCommerce: Removes filters for WC ratings
	*
    * @since 3.2.0
	*
	* @param 	int 	$post_id Post ID
	* @return 	wp_die() on error, true on success
	*/
	function pre_save_rating( $post_id ) {

	    // Setup some vars
	    $user = wp_get_current_user();
	    $email = ( $user->exists() ? wp_slash( $user->user_email ) : $_POST['email'] );
	    
	    // Check ratings have been posted
	    if ( ! isset( $_POST['crfp-rating'] ) ) {
		    return;
	    }
	    
	    // Check if the comment author has made any other comments to this post
	    $author_comments = get_comments( array(
			'author_email' 	=> $email,
			'post_ID' 		=> $post_id,
			'post_id'		=> $post_id,
			'meta_query'	=> array(
				array(
					'key' 		=> 'crfp',
					'compare' 	=> 'EXISTS',
				),
			),
		) );
		if ( ! is_array( $author_comments ) ) {
			return;
		}
	    
	    // Iterate through fields, getting their group to check if we have a limit enabled on ratings
	    foreach ( $_POST['crfp-rating'] as $field_id => $rating ) {

	    	// If the rating is zero, the user hasn't left a rating, so we don't need to then
	    	// check whether they're trying to leave a second rating.
	    	if ( $rating == '0' ) {
	    		continue;
	    	}

		    // Get field's group ID
		    $field = Comment_Rating_Field_Pro_Fields::get_instance()->get_by_id( $field_id );
		    if ( ! isset( $field['groupID'] ) ) {
			    continue;
		    }
		    
		    // Get group
		    $group = Comment_Rating_Field_Pro_Groups::get_instance()->get_by_id( $field['groupID'] );
		    
		    // Check if group imposes a limit
		    if ( ! isset( $group['ratingInput']['limitRating'] ) ) {
			    continue;
		    }
		    if ( $group['ratingInput']['limitRating'] != 1 ) {
			    continue;
		    }
		    
		    // If here, the group imposes a rating limit
		    // Iterate through comments to check if a rating has been left on another comment for this field
		    foreach ( $author_comments as $comment ) {
			    $ratings = get_comment_meta( $comment->comment_ID, 'crfp', true );
			    if ( ! is_array( $ratings ) ) {
				    continue;
			    }
			    
				if ( array_key_exists( $field_id, $ratings ) ) {
					// Author has already left a rating on this field for this Post - abort
					wp_die( __( 'Sorry, you cannot post more than one rating.' ), 403 );
					exit;
				}
		    }
	    }
	    
	    // OK
	    return;

	}

	/**
    * Saves the POSTed rating for the given comment ID to the comment meta table,
    * as well as storing the total ratings and average on the post itself.
    *
    * @since 3.2.0
    * 
    * @param int $comment_id Comment ID
    */
	function save_rating( $comment_id ) {

		// If Jetpack Comments are enabled, read ratings for the user's
		// IP address from the options table.  Ratings won't have been
		// POSTed through Jetpack, but will have been stored in the option
		// table data through AJAX calls
		if ( class_exists( 'Jetpack_Comments' ) ) {
			// Jetpack Comments
			// Get Post ID from comment
			$comment = get_comment( $comment_id );
			$post_id = $comment->comment_post_ID;

			// Get ratings from options table
			$ratings = get_option( 'comment-rating-field-pro-plugin-ratings' );

			// Get user's IP address
			$ip = Comment_Rating_Field_Pro_Common::get_instance()->get_user_ip_address();

			// Check a rating has been saved for the user's IP
			if ( ! isset( $ratings[ $ip ] ) ) {
				return;
			}

			// Check a rating has been saved for the user's IP and Post ID
			if ( ! isset( $ratings[ $ip ][ $post_id ] ) ) {
				return;
			}

			// If here, we have rating data for the given user and Post
			$ratings = $ratings[ $ip ][ $post_id ];
		} else {
			// WordPress Comments

		    // Check a rating has been posted
	    	if ( ! isset( $_POST['crfp-rating'] ) ) {
		    	return;
		    }
			if ( ! is_array( $_POST['crfp-rating'] ) ) {
		    	return;
		    }
		    $ratings = $_POST['crfp-rating'];
		}

		// We now have a key/value pair array comprising of field IDs and values
		// Iterate through and process as necessary
	    foreach ( $ratings as $field_id => $value ) {
	    	if ( $value == 0 ) {
	    		unset( $ratings[ $field_id ] );
	    		continue;
	    	}

	    	// Get field and group from DB, if we don't have it
	    	if ( ! isset( $field ) || ! is_array( $field ) ) {
	    		$field = Comment_Rating_Field_Pro_Fields::get_instance()->get_by_id( $field_id );
	    		$group = Comment_Rating_Field_Pro_Groups::get_instance()->get_by_id( $field['groupID'] );
	    	}
	    }

	    // Check ratings exist now we've verified rating values
	    if ( count ( $ratings ) == 0 ) {
	    	return;
	    }

	    // Store ratings
	    update_comment_meta( $comment_id, 'crfp', $ratings );

        // Calculate and store average rating
        $average_rating = ( is_array( $ratings ) ? ( array_sum( $ratings ) / count( $ratings ) ) : 0 );

        // Round rating depending on the group's settings
        if ( $group['ratingInput']['enableHalfRatings'] ) {
        	// Round to nearest .5
	        $average_rating = ( round( $average_rating * 2 ) / 2 );	
        } else {
        	// Round to nearest whole number
	        $average_rating = round( $average_rating, 0 );
        }

	    // Store average rating
	    update_comment_meta( $comment_id, 'crfp-average-rating', $average_rating );
        
	    // Update the Post's rating
        $result = $this->update_post_rating_by_comment_id( $comment_id );

	}

	/**
	* Updates a Post's rating by a Comment ID
	*
    * @since 3.2.0
	*
	* @param int $comment_id Comment ID
	* @return bool Success
	*/
	function update_post_rating_by_comment_id( $comment_id ) {

		// Check we have a valid comment ID
    	if ( empty( $comment_id ) || ! is_numeric( $comment_id ) ) {
	    	return false;
    	}

    	$comment = get_comment( $comment_id );
    	if ( ! isset( $comment->comment_post_ID ) ) {
    		return false;
    	}

    	return $this->update_post_rating_by_post_id( $comment->comment_post_ID );

	}

	/**
	 * Updates a Post's rating by a Post ID
	 *
     * @since 3.2.0
     *
	 * @param int $post_id Post ID
	 * @return bool Success
	 */
	public function update_post_rating_by_post_id( $post_id ) {

		// Define variables
    	$totalRatings = array();
    	$countRatings = array();
    	$averageRatings = array();
    	$commentsWithARating = 0;

		// Get all approved comments for the given post ID
		$comments = get_comments( array(
			'post_id' 	=> $post_id,
			'status' 	=> 'approve',
		) );

		// Check any comments exist from the above query
		if ( ! is_array( $comments ) || count( $comments ) == 0 ) {
			$this->reset_post_rating_by_post_id( $post_id );

			// Run action to tell developers the post rating was updated.
        	do_action( 'comment_rating_field_pro_rating_input_updated_post_rating', $post_id, $totalRatings, $averageRatings, $commentsWithARating, $countRatings, $averageRating, $ratingSplit, $ratingSplitPercentages );
		}

		// Iterate through comments
		foreach ( $comments as $comment ) {
			// Get ratings
			$ratings = get_comment_meta( $comment->comment_ID, 'crfp', true );
			$averageRating = get_comment_meta( $comment->comment_ID, 'crfp-average-rating', true );

			// Check ratings exist
			if ( ! is_array( $ratings ) ) {
				continue;
			}

			// Ignore zero ratings
			foreach ( $ratings as $fieldID => $rating ) {
				if ( $rating == 0) {
					unset( $ratings[ $fieldID] );
				}

				// Get field and group from DB, if we don't have it
		    	if ( ! isset( $field ) || ! is_array( $field ) ) {
		    		$field = Comment_Rating_Field_Pro_Fields::get_instance()->get_by_id( $fieldID );
					$group = Comment_Rating_Field_Pro_Groups::get_instance()->get_by_id( $field['groupID'] );
		    	}
			}

			// If no ratings exist, delete the plugin's comment meta against this comment
			if ( count( $ratings ) == 0 ) {
				delete_comment_meta( $comment->comment_ID, 'crfp' );
				delete_comment_meta( $comment->comment_ID, 'crfp-average-rating' );
				continue;
			}

			// Post has a valid rating
			$commentsWithARating++;

			// Build total ratings and number of ratings per field
			foreach ( $ratings as $fieldID => $rating ) {
				if ( ! isset( $totalRatings[ $fieldID ] ) ) {
					$totalRatings[ $fieldID ] = $rating;
					$countRatings[ $fieldID ] = 1;
				} else {
					$totalRatings[ $fieldID ] += $rating;
					$countRatings[ $fieldID ]++;
				}
			}

			// Calculate and store average rating
	        $averageRating = ( is_array( $ratings ) ? ( array_sum( $ratings ) / count( $ratings ) ) : 0 );

	        // Round rating depending on the group's settings
	        if ( $group['ratingInput']['enableHalfRatings'] ) {
	        	// Round to nearest .5
		        $averageRating = ( round( $averageRating * 2 ) / 2 );	
	        } else {
	        	// Round to nearest whole number
		        $averageRating = round( $averageRating, 0 );
	        }
			
			// Store average rating
		    update_comment_meta( $comment->comment_ID, 'crfp-average-rating', $averageRating );

		    // If we have a group, and haven't defined our ratingSplit and ratingSplitPercentage, do so now
		    if ( isset( $group ) && ! isset( $ratingSplit ) ) {
		    	$ratingSplit = array();

		    	if ( $group['ratingInput']['enableHalfRatings'] ) {
		    		for ( $i = 0.5; $i <= $group['ratingInput']['maxRating']; $i += 0.5 ) {
		        		$blank_rating_arr[ (string) $i ] = 0;
		        	}
		    	} else {
		    		for ( $i = 0.5; $i <= $group['ratingInput']['maxRating']; $i++ ) {
		        		$blank_rating_arr[ (string) $i ] = 0;
		        	}
		    	}
		    }
		   
			// Add to rating split
			$ratingSplitPercentages = $ratingSplit;
			if ( ! isset( $ratingSplit[ (string) $averageRating ] ) ) {
				$ratingSplit[ (string) $averageRating ] = 1;
			} else {
				$ratingSplit[ (string) $averageRating ]++;
			}
			
		}

		// Check we found any comments with a rating
		// If not, exit
		if ( $commentsWithARating == 0 ) {
			$this->reset_post_rating_by_post_id( $post_id );

			// Run action to tell developers the post rating was updated.
	        do_action( 'comment_rating_field_pro_rating_input_updated_post_rating', $post_id, $totalRatings, $averageRatings, $commentsWithARating, $countRatings, $averageRating, $ratingSplit, $ratingSplitPercentages );

			return true;
		}

		// Calculate the average rating for each field across all comments to the nearest .5
		foreach ( $totalRatings as $fieldID => $totalRating ) {
			$averageRatings[ $fieldID ] = ( $totalRating / $countRatings[ $fieldID ] );

			if ( $group['ratingInput']['enableHalfRatings'] ) {
	        	// Round to nearest 0.5
				$averageRatings[ $fieldID ] = ( intval( ( $averageRatings[ $fieldID ] * 2 ) + 0.5 ) / 2 );	
	        } else {
	        	// Round to nearest whole number
		        $averageRatings[ $fieldID ] = round( $averageRatings[ $fieldID ], 0 );
	        }
		}

		// Convert rating split counts to percentages of 100
		foreach ( $ratingSplit as $rating => $numberOfRatings ) {
			$ratingSplitPercentages[ $rating ] = round( ( $numberOfRatings / $commentsWithARating ) * 100 );
		}

		// Calculate average rating for the Post based on all ratings made
		$averageRating = ( array_sum( $averageRatings ) / count( $averageRatings ) );
		if ( $group['ratingInput']['enableHalfRatings'] ) {
        	// Round to nearest 0.5
			$averageRating = ( intval( ( $averageRating * 2 ) + 0.5 ) / 2 );	
        } else {
        	// Round to nearest whole number
	        $averageRating = round( $averageRating, 0 );
        }

		// Update post meta
		update_post_meta( $post_id, 'crfp-totals', $totalRatings );
        update_post_meta( $post_id, 'crfp-averages', $averageRatings );
        update_post_meta( $post_id, 'crfp-total-ratings', $commentsWithARating );
        update_post_meta( $post_id, 'crfp-rating-count', $countRatings );
        update_post_meta( $post_id, 'crfp-average-rating', $averageRating );
        update_post_meta( $post_id, 'crfp-rating-split', $ratingSplit );
        update_post_meta( $post_id, 'crfp-rating-split-percentages', $ratingSplitPercentages );

        // Run action to tell developers the post rating was updated.
        do_action( 'comment_rating_field_pro_rating_input_updated_post_rating', $post_id, $totalRatings, $averageRatings, $commentsWithARating, $countRatings, $averageRating, $ratingSplit, $ratingSplitPercentages );

        return true;

	}

	/**
	* Deletes all metadata associated with comments for the given Post ID
	*
	* @since 3.2.0
	*
	* @param int $post_id Post ID
	*/
	function reset_comment_rating_by_post_id( $post_id ) {

		// Get comments
        $comments = get_comments( array(
            'post_id'   => $post_id,
            'meta_query'=> array(
                array(
                    'key'       => 'crfp',
                    'compare'   => 'EXISTS',
                ),
            ),
        ) );

        // Check if any comments with ratings were found
        // If not, bail
        if ( count( $comments ) == 0 ) {
        	return false;
        }

        // Iterate through comments, deleting metadata
        foreach ( $comments as $comment ) {
        	delete_comment_meta( $comment->comment_ID, 'crfp' );
			delete_comment_meta( $comment->comment_ID, 'crfp-average-rating' );
        }

        // Done
        return true;

	}

	/**
     * Reset the given Post ID's rating
     *
     * @since 3.2.0
     *
     * @param int $post_id Post ID
     */
    function reset_post_rating_by_post_id( $post_id ) {

    	delete_post_meta( $post_id, 'crfp-totals' );
		delete_post_meta( $post_id, 'crfp-averages' );
		update_post_meta( $post_id, 'crfp-total-ratings', 0 );
    	update_post_meta( $post_id, 'crfp-rating-count', 0 );
		update_post_meta( $post_id, 'crfp-average-rating', 0 );
		delete_post_meta( $post_id, 'crfp-rating-split' );
		delete_post_meta( $post_id, 'crfp-rating-split-percentages' );

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