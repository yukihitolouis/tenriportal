<?php
/**
* Rating Output class
* 
* @package Comment_Rating_Field_Pro
* @author Tim Carr
* @version 1.0
*/
class Comment_Rating_Field_Pro_Rating_Output {

	/**
     * Holds the class object.
     *
     * @since 3.2.6
     *
     * @var object
     */
    public static $instance;

    /**
     * Holds the base class object.
     *
     * @since 3.3.5
     *
     * @var object
     */
    private $base;

	/**
	 * Groups
	 *
     * @since 3.2.0
     *
     * @var array
	 */
	public $group;

	/**
	 * Constructor
	 *
     * @since 3.2.0
	 */
	public function __construct() {

		// Actions and Filters
		if ( ! is_admin() ) {
			add_action( 'pre_get_posts', array( $this, 'sort_posts_by_rating' ) );
			add_action( 'wp', array( $this, 'register_comment_form_hooks' ) );

			// Non-singular Actions and Filters
			add_action( 'wp_enqueue_scripts', array( $this, 'css' ), 10 );
			add_filter( 'the_excerpt', array( $this, 'display_average_rating_excerpt' ) ); // Displays Average Rating for Excerpt
			add_filter( 'the_excerpt_rss', array( $this, 'display_average_rating_rss' ) ); // Displays Average Rating for RSS Feeds
		}

		// Admin-specific
		if ( is_admin() ) {
			add_action( 'comment_text', array( $this, 'display_comment_rating' ) ); // Displays Rating on Comments
		}

	}

	/**
	 * Sort Posts by their average rating. See https://gist.github.com/n7studios/56ad149b37634d7d69e8/
	 * for how to enable this.
	 *
	 * @since 3.2.7
	 */
	public function sort_posts_by_rating( $query ) {

	    // Bail if an admin query
	    if ( is_admin() ) {
	        return $query;
	    }

	    // Bail if not the main query
	    if ( ! $query->is_main_query() ) {
	        return $query;
	    }

	    // Define the Post Type(s) you want to enable sorting on
	    $post_types = apply_filters( 'comment_rating_field_pro_rating_output_sort_posts_by_rating', array() );

	    // If no Post Types defined, bail
	    if ( count( $post_types ) == 0 ) {
	    	return $query;
	    }

	    // Don't sort if we are not on the a post type archive that supports comments
	    if ( ! is_post_type_archive( $post_types ) ) {
	    	return $query;
	    }

	    // Change sorting
	    $query->set( 'orderby', 'meta_value_num' );
	    $query->set( 'meta_key', 'crfp-average-rating' );
	    $query->set( 'order', 'DESC' ); // Highest to lowest rating

	    // Return
	    return $query;

	}

	/**
	 * Registers actions and filters if a group is found matching the singular Post, Page or CPT
	 *
     * @since 3.2.0
	 */
	public function register_comment_form_hooks() {

		global $post;
		
		// Find group
		$this->group = Comment_Rating_Field_Pro_Groups::get_instance()->get_group_by_post_id( $post->ID );
		if ( ! $this->group ) {
			return;
		}

		// If not a feed and not a singular Post/Page/CPT, bail
		if ( ! is_comment_feed() && ! is_singular() ) {
			return;
		}

		// Register actions to display the comment field, depending on the group settings
		switch ( $this->group['ratingInput']['position'] ) {
    		/**
    		* Before All Fields
    		*/
    		case 'above':
    			// Before All Fields
    			add_action( 'comment_form_logged_in_after', array( &$this, 'display_rating_fields' ) ); // Logged in
    			add_action( 'comment_form_before_fields', array( &$this, 'display_rating_fields' ) ); // Guest
    			break;
    		
    		/**
    		* Before Comment Field
    		*/
    		case 'middle':
    			// Before Comment Field
    			add_action( 'comment_form_logged_in_after', array( $this, 'display_rating_fields' ) ); // Logged in
    			add_action( 'comment_form_after_fields', array( $this, 'display_rating_fields' ) ); // Guest
    			break;
    		

    		/**
    		* After Comment Field
    		*/
    		default:
    			// If Jetpack Comments is enabled, we need to use a different action
    			if ( class_exists( 'Jetpack_Comments' ) ) {
    				// Before Comment Field
    				add_action( 'comment_form_after', array( $this, 'display_rating_fields' ) );
    			} else {
    				// After Comment Field
    				add_filter( 'comment_form_field_comment', array( $this, 'display_rating_fields' ) );
    			}
    			break;
    	}

    	// Because we have found a group, we need to output JS and CSS and register our other hooks
    	add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );

    	// If we're on a comments feed, register some different filters
    	if ( is_comment_feed() ) {
    		add_filter( 'comment_text_rss', array( $this, 'display_comment_rating_rss' ) );
    		add_action( 'comment_text', array( $this, 'display_comment_rating_rss' ) );
    	} else {
    		add_filter( 'comments_array', array( $this, 'filter_comments_by_rating'), 10, 2 );
			add_action( 'comment_text', array( $this, 'display_comment_rating' ) ); // Displays Rating on Comments	
			add_filter( 'the_content', array( $this, 'display_average_rating_content' ) ); // Displays Average Rating for Content
    	}
    	
	}

	/**
	 * Register or enqueue any CSS
	 *
     * @since 3.2.0
	 */
	public function css() {

		// Get base instance
		$this->base = Comment_Rating_Field_Pro::get_instance();

		// Enqueue CSS and Custom CSS
    	wp_enqueue_style( $this->base->plugin->name, $this->base->plugin->url . 'assets/css/frontend.css', array(), false );
    	$this->custom_css();

	}

	/**
	 * Loads CSS for RSS Feeds
	 *
     * @since 3.3.5
	 */
	public function rss_css() {

		// Get base instance
		$this->base = Comment_Rating_Field_Pro::get_instance();

		// Output XML stylesheet link
		echo '<?xml-stylesheet type="text/xsl" href="' . $this->base->plugin->url . 'assets/css/rss.xsl"?>';

	}

	/**
     * Register or enqueue any JS
     *
     * @since 3.2.0
     */
    public function scripts() {

    	global $post;

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( $this->base->plugin->name, $this->base->plugin->url . 'assets/js/min/frontend-min.js', array( 'jquery' ), $this->base->plugin->version, true );
    	wp_localize_script( $this->base->plugin->name, 'crfp', array(
    		'ajax_url'            	=> admin_url( 'admin-ajax.php' ),
    		'disable_replies' 		=> $this->group['ratingInput']['disableReplies'],
    		'enable_half_ratings' 	=> $this->group['ratingInput']['enableHalfRatings'],
    		'nonce'                 => wp_create_nonce( $this->base->plugin->name . '_nonce' ),
            'post_id'               => ( is_singular() ? $post->ID : 0 ),
    	));
    	
    }

	/**
	 * Output each group's custom CSS
	 *
     * @since 3.2.0
	 */
	public function custom_css() {

		// Load group
		$groups = Comment_Rating_Field_Pro_Groups::get_instance()->get_all('name', 'ASC', -1);
		
		// Check groups exist
		if ( ! is_array( $groups ) || count( $groups ) == 0 ) {
			return;
		}
		
		// Iterate through groups, outputting CSS customisations
		ob_start();
		?>
		<style type="text/css">
			<?php
			foreach ( $groups as $group ) {
				// Manually force size if not set
				if ( ! isset( $group['css']['starSize'] ) || empty( $group['css']['starSize'] ) ) {
					$group['css']['starSize'] = 16;
				}
				?>
				div.rating-container.crfp-group-<?php echo $group['groupID']; ?> {
					min-height: <?php echo $group['css']['starSize']; ?>px;
				}
				div.rating-container.crfp-group-<?php echo $group['groupID']; ?> span,
				div.rating-container.crfp-group-<?php echo $group['groupID']; ?> a {
					line-height: <?php echo $group['css']['starSize']; ?>px;
				}
				div.rating-container.crfp-group-<?php echo $group['groupID']; ?> span.rating-always-on { 
					width: <?php echo ( $group['css']['starSize'] * $group['ratingInput']['maxRating'] ); ?>px;
					height: <?php echo $group['css']['starSize']; ?>px;
					background-image: url(<?php echo $this->base->plugin->url; ?>/views/global/svg.php?svg=star&color=<?php echo str_replace('#', '', $group['css']['starBackgroundColor']); ?>&size=<?php echo $group['css']['starSize']; ?>);
				}
				div.rating-container.crfp-group-<?php echo $group['groupID']; ?> span.crfp-rating {
					height: <?php echo $group['css']['starSize']; ?>px;
					background-image: url(<?php echo $this->base->plugin->url; ?>/views/global/svg.php?svg=star&color=<?php echo str_replace('#', '', $group['css']['starColor']); ?>&size=<?php echo $group['css']['starSize']; ?>);
				}
				div.rating-container.crfp-group-<?php echo $group['groupID']; ?> div.star-rating a {
					width: <?php echo $group['css']['starSize']; ?>px;
					max-width: <?php echo $group['css']['starSize']; ?>px;
					height: <?php echo $group['css']['starSize']; ?>px;
					background-image: url(<?php echo $this->base->plugin->url; ?>/views/global/svg.php?svg=star&color=<?php echo str_replace('#', '', $group['css']['starBackgroundColor']); ?>&size=<?php echo $group['css']['starSize']; ?>);
				}
				p.crfp-group-<?php echo $group['groupID']; ?> div.star-rating {
					width: <?php echo $group['css']['starSize']; ?>px;
					height: <?php echo $group['css']['starSize']; ?>px;
				}
				p.crfp-group-<?php echo $group['groupID']; ?> div.star-rating a {
					width: <?php echo $group['css']['starSize']; ?>px;
					max-width: <?php echo $group['css']['starSize']; ?>px;
					height: <?php echo $group['css']['starSize']; ?>px;
					background-image: url(<?php echo $this->base->plugin->url; ?>/views/global/svg.php?svg=star&color=<?php echo str_replace('#', '', $group['css']['starBackgroundColor']); ?>&size=<?php echo $group['css']['starSize']; ?>);
				}
				p.crfp-group-<?php echo $group['groupID']; ?> div.star-rating-hover a {
					background-image: url(<?php echo $this->base->plugin->url; ?>/views/global/svg.php?svg=star&color=<?php echo str_replace('#', '', $group['css']['starInputColor']); ?>&size=<?php echo $group['css']['starSize']; ?>);
				}
				p.crfp-group-<?php echo $group['groupID']; ?> div.star-rating-on a {
					background-image: url(<?php echo $this->base->plugin->url; ?>/views/global/svg.php?svg=star&color=<?php echo str_replace('#', '', $group['css']['starColor']); ?>&size=<?php echo $group['css']['starSize']; ?>);
				}
				p.crfp-group-<?php echo $group['groupID']; ?> div.rating-cancel {
					width: <?php echo $group['css']['starSize']; ?>px;
					height: <?php echo $group['css']['starSize']; ?>px;
				}
				p.crfp-group-<?php echo $group['groupID']; ?> div.rating-cancel a {
					width: <?php echo $group['css']['starSize']; ?>px;
					height: <?php echo $group['css']['starSize']; ?>px;
					background-image: url(<?php echo $this->base->plugin->url; ?>/views/global/svg.php?svg=delete&color=<?php echo str_replace('#', '', $group['css']['starBackgroundColor']); ?>&size=<?php echo $group['css']['starSize']; ?>);
				}
				p.crfp-group-<?php echo $group['groupID']; ?> div.rating-cancel.star-rating-hover a {
					background-image: url(<?php echo $this->base->plugin->url; ?>/views/global/svg.php?svg=delete&color=<?php echo str_replace('#', '', $group['css']['starInputColor']); ?>&size=<?php echo $group['css']['starSize']; ?>);
				}
				div.rating-container.crfp-group-<?php echo $group['groupID']; ?> div.crfp-bar .bar {
					background-color: <?php echo $group['css']['starBackgroundColor']; ?>;
				}
				div.rating-container.crfp-group-<?php echo $group['groupID']; ?> div.crfp-bar .bar .fill {
					background-color: <?php echo $group['css']['starColor']; ?>;
				}
				<?php
			}
			?>
		</style>
		<?php
		// Get output and echo
        $css = ob_get_clean();
        echo $this->minify( $css );

    }

    /**
     * Helper method to minify a string of data.
     *
     * @since 3.2.0
     *
     * @param string $string  String of data to minify.
     * @return string $string Minified string of data.
     */
    function minify( $string ) {

        $clean = preg_replace( '!/\*.*?\*/!s', '', $string );
        $clean = preg_replace( '/\n\s*\n/', "\n", $clean );
        $clean = str_replace( array( "\r\n", "\r", "\t", "\n", '  ', '    ', '     ' ), '', $clean );
        
        return $clean;

    }

    /**
	 * If a query var to filter comments is set, build a new comments array comprising
	 * of just the comments we want.
	 *
	 * Also sorts comments if the sort query var is specified
	 *
	 * @since 3.2.0
	 *
	 * @param array $comments 	Comments
	 * @param int 	$post_id 	Post ID
	 * @return array 			Comments
	 */
	function filter_comments_by_rating( $comments, $post_id ) {

		// Check if our rating query var was set
		if ( ! isset( $_GET['rating'] ) && ! isset( $_GET['sort'] ) ) {
			return $comments;
		}

		// Build our custom comment arguments
		$comment_args = array(
			'order'   	=> 'ASC',
			'orderby' 	=> 'comment_date_gmt',
			'status'  	=> 'approve',
			'post_id' 	=> $post_id,
			'meta_key'	=> 'crfp-average-rating',
		);

		// Only show comments matching the given rating, if specified
		if ( isset( $_GET['rating'] ) ) {
			$comment_args['meta_value'] = (string) $_GET['rating'];
		}

		// Sort comments in the specified direction, if given
		if ( isset( $_GET['sort'] ) ) {
			$comment_args['orderby'] 	= 'meta_value_num';

			// Sort works in reverse for some reason - DESC would give us 1 - 5 rating orders
			// so we reverse the order flag here
			$comment_args['order'] 		= ( ( $_GET['sort'] == 'ASC' ) ? 'DESC' : 'ASC' );
		}

		// Run the query and return the comments
		$comments = get_comments( $comment_args );

		return $comments;

	}

	/**
	* Main function to display average rating on excerpts
	*
	* Called on every excerpt, so we need to check if a group exists for each Post
	*
    * @since 3.2.0
    *
    * @param string $excerpt Post Excerpt
    * @return string 		 Post Excerpt with Average Rating HTML
	*/
	function display_average_rating_excerpt( $excerpt ) {

		global $post;

		/**
		* Check if we're in the loop
		* If not, return excerpt
		* This prevents us generating HTML multiple times, which might happen if an SEO plugin scans the_content
		* for its own usage.
		*/
		if ( ! in_the_loop() ) {
			return $excerpt;
		}
    	
    	// Find group
		$group = Comment_Rating_Field_Pro_Groups::get_instance()->get_group_by_post_id( $post->ID );
		if ( ! $group ) {
			return $excerpt;
		}
    	
        // Build rating HTML
        $html = $this->build_average_rating_html( $post->ID, $group, 'excerpt', $excerpt );
        $html = apply_filters( 'crfp_display_post_rating_excerpt', $html, $group, $excerpt, $post->ID, $post );

        // Return
        return $html; 

	}

	/**
	* Main function to display average rating on content
	*
	* Called when on a singular Post/Page/CPT and we already know there is a group available
	*
    * @since 3.2.0
    *
    * @param string $content Post Content
    * @return string 		 Post Content with Average Rating HTML
	*/
	function display_average_rating_content( $content ) {
		
		global $post;

		/**
		* Check if we're in the loop
		* If not, return content
		* This prevents us generating HTML multiple times, which might happen if an SEO plugin scans the_content
		* for its own usage.
		*/
		if ( ! in_the_loop() ) {
			return $content;
		}
    	
    	// Build rating HTML
        $html = $this->build_average_rating_html( $post->ID, $this->group, 'content', $content );
        $html = apply_filters( 'crfp_display_post_rating_content', $html, $this->group, $content, $post->ID, $post );
        
        // Return
        return $html; 

	}

	/**
	* Main function to display average rating on RSS Feeds
	*
	* Called on every feed item, so we need to check if a group exists for each Post
	*
    * @since 3.2.7
    *
    * @param string $excerpt RSS Post Excerpt
    * @return string 		 RSS Post Excerpt with Average Rating HTML
	*/
	function display_average_rating_rss( $excerpt ) {

		global $post;

		/**
		* Check if we're in the loop
		* If not, return excerpt
		* This prevents us generating HTML multiple times, which might happen if an SEO plugin scans the_content
		* for its own usage.
		*/
		if ( ! in_the_loop() ) {
			return $excerpt;
		}
    	
    	// Find group
		$group = Comment_Rating_Field_Pro_Groups::get_instance()->get_group_by_post_id( $post->ID );
		if ( ! $group ) {
			return $excerpt;
		}
    	
        // Bail if rating output on RSS is disabled
        if ( $group['ratingOutputRSS']['enabled'] == 0 ) {
        	return $excerpt;
        }

        // Get average rating and total rating
        $totalRatings 			= get_post_meta( $post->ID, 'crfp-total-ratings', true );
		$averageRating 			= get_post_meta( $post->ID, 'crfp-average-rating', true );

		// Build output
		$html = '<p>' . $averageRating . '/5';
		if ( $group['ratingOutputRSS']['totalRatings'] == 1 ) {
			$html .= ' ' . $group['ratingOutputRSS']['totalRatingsBefore'] . ' ' . $totalRatings . ' ' . $group['ratingOutputRSS']['totalRatingsAfter'];
		}
		$html .= '</p>';

		// Prepend or append rating to excerpt
		// Append average rating before or after content
		switch ( $group['ratingOutputRSS']['position'] ) {
			/**
			* Above Content
			*/
			case 'above':
				return $html . $excerpt;
				break;

			/**
			* Below Content
			*/
			case '':
			default:
				return $excerpt . $html;
				break;
		}

	}

	/**
	* Returns the average rating HTML markup, which is used by:
	* - content
    * - excerpt
    * - shortcode
    *
    * @since 3.2.0
    *
    * @param int 	$post_id 	Post ID
    * @param array 	$group 		Rating Group
    * @param string $type 		Content Type (excerpt|content|shortcode)
    * @param string $content 	Existing Content
    * @return string 			Average Rating HTML with Content
	*/
	function build_average_rating_html( $post_id, $group, $type, $content = '' ) {

		// Get post
	    $post = get_post( $post_id );

	    // Get rating data
	    $totals 				= get_post_meta( $post_id, 'crfp-totals', true );
	    $averageRatings 		= get_post_meta( $post_id, 'crfp-averages', true );
	    $totalRatings 			= get_post_meta( $post_id, 'crfp-total-ratings', true );
		$averageRating 			= get_post_meta( $post_id, 'crfp-average-rating', true );
        $ratingSplit 			= get_post_meta( $post_id, 'crfp-rating-split', true );
        $ratingSplitPercentages = get_post_meta( $post_id, 'crfp-rating-split-percentages', true );

        // Define a blank array of empty ratings if we need it
        $blank_rating_arr = array();
        if ( $group['ratingInput']['enableHalfRatings'] ) {
        	// Blank ratings should include half ratings
        	for ( $i = 0.5; $i <= $group['ratingInput']['maxRating']; $i += 0.5 ) {
        		$blank_rating_arr[ (string) $i ] = 0;
        	}
        } else {
        	// Blank ratings should not include half ratings
        	for ( $i = 1; $i <= $group['ratingInput']['maxRating']; $i++ ) {
        		$blank_rating_arr[ (string) $i ] = 0;
        	}
        }

        // If rating data is empty, set it
        if ( empty( $totalRatings ) ) {
        	$totalRatings = 0;
        }
        if ( empty( $averageRating ) ) {
        	$averageRating = 0;
        }
        if ( empty( $ratingSplit ) ) {
        	$ratingSplit = $blank_rating_arr; 
        }
        if ( empty( $ratingSplitPercentages ) ) {
        	$ratingSplitPercentages = $blank_rating_arr;
        }

        // Bail if output is set to never display
        $setting_group = 'ratingOutput' . ( $type == 'rss' ? 'RSS' : ucfirst( $type ) );
        if ( $group[ $setting_group ]['enabled'] == 0 ) {
	        return $content;
        }
        
        // Bail if output is conditional on ratings existing
        if ( $group[ $setting_group ]['enabled'] == 1 ) {
        	// If no ratings, bail
        	if ( $totalRatings == 0 ) {
	        	return $content;
			}
			
			// If ratings, check they are for fields in this group
			$ratingsForGroupFields = false;
			foreach ( $group['fields'] as $field ) {
				if ( isset( $totals[ $field['fieldID'] ] ) ) {
					$ratingsForGroupFields = true;
					break;
				}
			}
			if ( ! $ratingsForGroupFields ) {
				return $content;
			}
        } 
        
        // Start Display
        $html = ''; 

        // Filter the schema item name
        $item_name = apply_filters( 'comment_rating_field_pro_rating_output_build_average_rating_html_schema_title', $post->post_title, $group );

        // Schema Type        
        if ( ! empty( $group['schema_type'] ) ) {
	        $html = '
			<div class="comment-rating-field-pro-plugin" itemscope itemtype="http://schema.org/' . $group['schema_type'] . '">
    			<meta itemprop="name" content="' . $item_name . '" />';
        } else {
	        $html = '<div class="comment-rating-field-pro-plugin">';
        }
    	
        // Show Average  
	    if ( $group[ $setting_group ]['average'] > 0 ) {    
		    // Open Average
		    if ( ! empty( $group['schema_type'] ) ) {
			    $html .= '
				<div class="rating-container crfp-group-' . $group['groupID'] . '" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
        			<meta itemprop="ratingValue" content="' . $averageRating . '" />
					<meta itemprop="reviewCount" content="' . $totalRatings . '" />';
		    } else {
			    $html .= '<div class="rating-container crfp-group-' . $group['groupID'] . '">';
		    }
		    	
		    $html .= '<span class="label">';
		        	
		    // Average Label + Link to Comments
	        if ( $group[ $setting_group ]['linkToComments'] ) {
		        $html .= '<a href="' . get_permalink( $post_id ) . '#comments">' . $group[ $setting_group ]['averageLabel'] . '</a>';
	        } else {
		        $html .= $group[ $setting_group ]['averageLabel'];
	        }
	        
	        $html .= '
				</span>';
		    
		    // Average Bars or Stars
	        switch ( $group[ $setting_group ]['average'] ) {
		        /**
			    * Bars
			    */
			    case 2:
					// Iterate through each rating from highest to lowest
			    	foreach ( array_reverse( $ratingSplitPercentages, true ) as $rating => $percentage ) {
			    		// Make bar clickable if comment filtering is enabled
			    		if ( isset( $group[ $setting_group ]['filterComments'] ) && $group[ $setting_group ]['filterComments'] == 1 ) {	
							// Build URL with ?rating arg
							$url = add_query_arg( array(
								'rating' => $rating,
							), get_permalink( $post_id ) ) . '#comments';

							// HTML
							$html .= '<div class="crfp-bar rating-'.$rating.((($group[$setting_group]['enabled'] == 2 AND $totalRatings == 0) OR $group[$setting_group]['style'] == 'grey') ? ' rating-always-on' : '').'">
								<span class="label">
									<a href="' . $url . '" title="' . __( 'View', 'comment-rating-field-pro-plugin' ) . ' ' . $rating . ' ' . __( 'reviews', 'comment-rating-field-pro-plugin' ) . '">
										' . $rating . ' ' . __( 'stars', 'comment-rating-field-pro-plugin' ) . '
									</a>
								</span>
								<a class="bar" href="' . $url . '" title="' . __( 'View', 'comment-rating-field-pro-plugin' ) . ' ' . $rating . ' ' . __( 'reviews', 'comment-rating-field-pro-plugin' ) . '">
									<span class="fill" style="width:'.$percentage.'%;">&nbsp;</span>
								</a>
								<a class="count" href="' . $url . '" title="' . __( 'View', 'comment-rating-field-pro-plugin' ) . ' ' . $rating . ' ' . __( 'reviews', 'comment-rating-field-pro-plugin' ) . '">
									' . ( isset( $ratingSplit[ $rating ] ) ? $ratingSplit[ $rating ] : 0 ) . '
								</a>
							</div>';
						} else {
							$html .= '<div class="crfp-bar rating-'.$rating.((($group[$setting_group]['enabled'] == 2 AND $totalRatings == 0) OR $group[$setting_group]['style'] == 'grey') ? ' rating-always-on' : '').'">
								<span class="label">'.$rating.' '.__('stars', 'comment-rating-field-pro-plugin').'</span>
								<span class="bar">
									<span class="fill" style="width:' . $percentage . '%;">&nbsp;</span>
								</span>
								<span class="count">' . ( isset( $ratingSplit[ $rating ] ) ? $ratingSplit[ $rating ] : 0 ) . '</span>
							</div>';
						}
					}
				
			    	break;
			    
			    /**
				* Stars
				*/
				case 1:
			       	$html .= '
			       		<span'.( ( ( $group[$setting_group]['enabled'] == 2 && $totalRatings == 0 ) || $group[ $setting_group ]['style'] == 'grey' ) ? ' class="rating-always-on"' : '' ) . '>
							<span class="crfp-rating crfp-rating-' . str_replace( '.', '-', $averageRating ) . '" style="width:' . ( $averageRating * $group['css']['starSize'] ) . 'px">';
						
					// Link to Comments    	
					if ( $group[ $setting_group ]['linkToComments'] ) {
						$html .= '<a href="#comments">';
					} 
					$html .= $averageRating;
					if ( $group[ $setting_group ]['linkToComments'] ) {
						$html .= '</a>';
					}
						   	
					$html .= '
							</span>
						</span>';
						
					break;
			}

			// Show Rating Number
			$rating_number = '';
			switch ( $group[ $setting_group ]['showRatingNumber'] ) {

				/**
				 * Percentage
				 */
				case 2:
					$rating_number = '<span class="crfp-rating-number-percentage">' . ( ( $averageRating / $group['ratingInput']['maxRating'] ) * 100 ) . '%</span>';
					break;

				/**
				 * Number
				 */
				case 1:
					$rating_number = '<span class="crfp-rating-number">' . $averageRating . '</span>';
					break;

			}
			$rating_number = apply_filters( 'comment_rating_field_pro_rating_output_build_average_rating_html_show_rating_number_average', $rating_number, $group, $averageRating );
			$html .= $rating_number;
			
			// Total Ratings
			if ( $group[ $setting_group ]['totalRatings'] ) {
				// Get before/after text
				$total_ratings_before = isset( $group[ $setting_group ]['totalRatingsBefore'] ) ? $group[ $setting_group ]['totalRatingsBefore'] : __( 'from', 'comment-rating-field-pro-plugin' );
				$total_ratings_after = isset( $group[ $setting_group ]['totalRatingsAfter'] ) ? $group[ $setting_group ]['totalRatingsAfter'] : __( 'ratings', 'comment-rating-field-pro-plugin' );
			
				// Append Total Ratings to HTML
			   	$html .= '
				   	<span class="total">
				   		' . $total_ratings_before . '
				   		<span>'.$totalRatings.'</span>
				   		' . $total_ratings_after . '
				   	</span>';
			}
			
			// Close Div
			$html .= '
			</div>';
		}
			
		// Show Breakdown
		switch ( $group[ $setting_group ]['showBreakdown'] ) {	
			/**
			* Stars
			*/
			case 1:
				// Iterate through fields
				foreach ( $group['fields'] as $field ) {
					// Average Rating for Field
					if ( ! isset( $averageRatings[ $field['fieldID'] ] ) ) {
						$averageRatings[ $field['fieldID'] ] = 0;
					}
					
					// Field
					$html .= '
					<div class="rating-container crfp-group-'.$group['groupID'].' crfp-stars">
						<span class="label">'.$field['label'].'</span>
						<span'.((($group[$setting_group]['enabled'] == 2 AND $totalRatings == 0) OR $group[$setting_group]['style'] == 'grey') ? ' class="rating-always-on"' : '').'>
					    	<span class="crfp-rating crfp-rating-'. str_replace( '.', '-', $averageRatings[ $field['fieldID'] ] ) . '" style="width:' . ( $averageRatings[ $field['fieldID'] ] * $group['css']['starSize'] ) . 'px">
					    		' . $averageRatings[ $field['fieldID'] ] . '
					    	</span>
					   	</span>';

					// Show Rating Number
					$rating_number = '';
					switch ( $group[ $setting_group ]['showRatingNumber'] ) {

						/**
						 * Percentage
						 */
						case 2:
							$rating_number = '<span class="crfp-rating-number-percentage">' . ( ( $averageRatings[ $field['fieldID'] ] / $group['ratingInput']['maxRating'] ) * 100 ) . '%</span>';
							break;

						/**
						 * Number
						 */
						case 1:
							$rating_number = '<span class="crfp-rating-number">' . $averageRatings[ $field['fieldID'] ] . '</span>';
							break;

					}
					$rating_number = apply_filters( 'comment_rating_field_pro_rating_output_build_average_rating_html_show_rating_number_breakdown', $rating_number, $group, $averageRatings[ $field['fieldID'] ], $field );
					$html .= $rating_number;

					// Close Field
					$html .= '</div>';
				}
				break;
		}
		
		// Close Schema Type
		$html .= '</div>';
	    
		// Filter HTML
        $html = apply_filters( 'comment_rating_field_pro_rating_output_build_average_rating_html', $html, $group );
        
		// Append average rating before or after content
		switch ( $group[ $setting_group ]['position'] ) {
			/**
			* Above Content
			*/
			case 'above':
				return $html . $content;
				break;

			/**
			* Below Content
			*/
			case '':
			default:
				return $content . $html;
				break;
		}

	}

	/**
	 * Main function to display rating on a comment
	 *
	 * Called from both the frontend and admin, so if $this->group isn't populated, we'll try to populate it again
	 *
	 * @since 3.2.0
	 *
	 * @return string $comment
	 */
	public function display_comment_rating( $comment ) {

		global $post;
        
        // Get post and comment ID
        $post_id = $post->ID;
        $comment_id = get_comment_ID();

        // Always get the group if we're in the WordPress Admin, as we'll be viewing comments from different
        // Posts on a single screen (vs. the frontend, where we only view comments for a single Post)
        if ( is_admin() ) {
        	// Find group
			$this->group = Comment_Rating_Field_Pro_Groups::get_instance()->get_group_by_post_id( $post_id );
        }

        // Check if we have a group. If not, bail
        if ( ! $this->group ) {
			return $comment;
		}
        
        // Build comment rating HTML
        $html = $this->build_comment_rating_html( $post_id, $comment_id, $this->group, $comment );

        // Filter
        $html = apply_filters( 'comment_rating_field_pro_rating_output_display_comment_rating', $html, $this->group, $comment_id, $comment, $post_id, $post );
        
        // Return
        return $html;

	}

	/**
	 * Main function to display rating on a comment in a comment RSS feed.
	 *
	 * @since 3.3.5
	 *
	 * @param 	string 	$comment_text 	Comment Text
	 * @return 	string 					Comment Text
	 */
	public function display_comment_rating_rss( $comment_text ) {

		global $post;

        // Get post and comment ID
        $post_id 	= $post->ID;
        $comment_id = get_comment_ID();

        // Check if we have a group. If not, bail
        if ( ! $this->group ) {
			return $comment_text;
		}
        
        // Build comment rating RSS output
        $rss = $this->build_comment_rating_rss( $post_id, $comment_id, $this->group, $comment_text );

        // Filter
        $rss = apply_filters( 'comment_rating_field_pro_rating_output_display_comment_rating_rss', $rss, $this->group, $comment_id, $comment_text, $post_id, $post );
        
        // Return
        return $rss;

	}

	/**
	 * Returns the average rating HTML markup for individual comments
	 *
	 * @since 3.2.0
     *
     * @param 	int 	$post_id 		Post ID
     * @param 	int 	$comment_id 	Comment ID
     * @param 	array 	$group 			Rating Group
     * @param 	string 	$content 		Existing Comment
     * @return 	string 					Average Rating HTML with Comment
     */
    public function build_comment_rating_html( $post_id, $comment_id, $group, $content = '' ) {

		// Set key to get display settings from
        $setting_group = 'ratingOutputComments';

        // Get rating data
        $comment = get_comment( $comment_id );
        $ratings = get_comment_meta( $comment_id, 'crfp', true );
        $averageRating = get_comment_meta( $comment_id, 'crfp-average-rating', true );
        
        // Bail if no rating was left on this comment
        if ( ! is_array( $ratings ) ) {
	        return $content;
        }
        
        // Bail if output is set to never display
        if ( $group[ $setting_group ]['enabled'] == 0 ) {
	        return $content;
        }
        
        // Bail if output is conditional on ratings existing
        if ( $group[ $setting_group ]['enabled'] == 1 ) {
        	// If no ratings, bail
        	if ($averageRating == 0) {
	        	return $content;
			}
			
			// If ratings, check they are for fields in this group
			$ratingsForGroupFields = false;
			foreach ($group['fields'] as $field) {
				if (isset($ratings[$field['fieldID']])) {
					$ratingsForGroupFields = true;
					break;
				}
			}
			if (!$ratingsForGroupFields) {
				return $content;
			}
        } 
        
        // Start Display
        $html = ''; 
        
        // Display Average       
        if ( $group[ $setting_group ]['average'] ) {
        	$html .= '
	        	<div class="rating-container crfp-group-' . $group['groupID'] . ' crfp-average-rating">
		        	<span class="label">
		        		' . $group[ $setting_group ]['averageLabel'] . '
		        	</span>
					<span'.( ( ( $group[ $setting_group ]['enabled'] == 2 && $averageRating == 0 ) || $group[ $setting_group ]['style'] == 'grey' ) ? ' class="rating-always-on"' : '' ) . '>
				    	<span class="crfp-rating crfp-rating-' . str_replace( '.', '-', $averageRating ) . '" style="width:' . ( $averageRating * $group['css']['starSize'] ) . 'px">
				    		' . $averageRating . '
				    	</span>
					</span>';

			// Show Rating Number
			$rating_number = '';
			switch ( $group[ $setting_group ]['showRatingNumber'] ) {

				/**
				 * Percentage
				 */
				case 2:
					$rating_number = '<span class="crfp-rating-number-percentage">' . ( ( $averageRating / $group['ratingInput']['maxRating'] ) * 100 ) . '%</span>';
					break;

				/**
				 * Number
				 */
				case 1:
					$rating_number = '<span class="crfp-rating-number">' . $averageRating . '</span>';
					break;

			}
			$rating_number = apply_filters( 'comment_rating_field_pro_rating_output_build_comment_rating_html_show_rating_number_average', $rating_number, $group, $averageRating );
			$html .= $rating_number;

			// Close average
			$html .= '</div>';
		}
			
		// Display Breakdown
		if ( $group[ $setting_group ]['showBreakdown'] ) {
			// Iterate through fields
			foreach ( $group['fields'] as $field ) {
				// Rating for Field
				if ( ! isset( $ratings[ $field['fieldID'] ] ) ) {
					$ratings[ $field['fieldID'] ] = 0;
				}
				
				// Field
				$html .= '
				<div class="rating-container crfp-group-' . $group['groupID'] . ' crfp-rating-breakdown">
					<span class="label">' . $field['label'] . '</span>
					<span' . ( ( ( $group[ $setting_group ]['enabled'] == 2 && $ratings[ $field['fieldID'] ] == 0 ) || $group[ $setting_group ]['style'] == 'grey' ) ? ' class="rating-always-on"' : '' ) . '>
				    	<span class="crfp-rating crfp-rating-' . str_replace( '.', '-', $ratings[ $field['fieldID'] ] ) . '" style="width:' . ( $ratings[ $field['fieldID'] ] * $group['css']['starSize'] ) . 'px">
				    		' . $ratings[ $field['fieldID'] ] . '
				    	</span>
				   	</span>';

				// Show Rating Number
				$rating_number = '';
				switch ( $group[ $setting_group ]['showRatingNumber'] ) {

					/**
					 * Percentage
					 */
					case 2:
						$rating_number = '<span class="crfp-rating-number-percentage">' . ( ( $ratings[ $field['fieldID'] ] / $group['ratingInput']['maxRating'] ) * 100 ) . '%</span>';
						break;

					/**
					 * Number
					 */
					case 1:
						$rating_number = '<span class="crfp-rating-number">' . $ratings[ $field['fieldID'] ] . '</span>';
						break;

				}
				$rating_number = apply_filters( 'comment_rating_field_pro_rating_output_build_comment_rating_html_show_rating_number_breakdown', $rating_number, $group, $ratings[ $field['fieldID'] ], $field );
				$html .= $rating_number;

				// Close field
				$html .= '</div>';
			}
		}
		
		// Markup the comment
		if ( ! empty( $group['schema_type'] ) ) {
			$content = '
	        <div class="rating-container" itemprop="review" itemscope itemtype="http://schema.org/Review">
	        	<meta itemprop="itemReviewed" content="' . get_the_title( $comment->comment_post_ID ) . '" />
	        	<meta itemprop="author" content="'.$comment->comment_author.'" />
	        	<meta itemprop="datePublished" content="'.date('Y-m-d', strtotime($comment->comment_date)).'" />
	        	<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
	        		<meta itemprop="worstRating" content="1" />
	        		<meta itemprop="ratingValue" content="'.$averageRating.'" />
	        		<meta itemprop="bestRating" content="5" />
	        	</div>
	        	<div itemprop="description" class="crfp-rating-text">'.wpautop($content).'</div>
	        </div>';	
		}
        
		// Apply filters
        $html = apply_filters('crfp_display_comment_rating', $html);
        
        // Strip newlines from $html, as WordPress will convert these to <br> in comments
        $html = str_replace(array("\r", "\n"), '', $html);
        $content = str_replace(array("\r", "\n"), '', $content);
        
		// Append average rating before or after content
		switch ($group[ $setting_group ]['position']) {

			/**
			* Above
			*/
			case 'above':
				return $html . $content;
				break;
			
			/**
			* Below
			*/
			case '':
			default:
				return $content . $html;
				break;

		}

	}

	/**
	 * Returns the average rating RSS markup for individual comments
	 *
	 * @since 	3.3.5
     *
     * @param 	int 	$post_id 		Post ID
     * @param 	int 	$comment_id 	Comment ID
     * @param 	array 	$group 			Rating Group
     * @param 	string 	$content 		Existing Comment
     * @return 	string 					Average Rating HTML with Comment
     */
    public function build_comment_rating_rss( $post_id, $comment_id, $group, $content = '' ) {

		// Set key to get display settings from
        $setting_group = 'ratingOutputRSSComments';

        // Get rating data
        $comment = get_comment( $comment_id );
        $ratings = get_comment_meta( $comment_id, 'crfp', true );
        $averageRating = get_comment_meta( $comment_id, 'crfp-average-rating', true );
        
        // Bail if no rating was left on this comment
        if ( ! is_array( $ratings ) ) {
	        return $content;
        }
        
        // Bail if output is set to never display
        if ( $group[ $setting_group ]['enabled'] == 0 ) {
	        return $content;
        }
        
        // Bail if output is conditional on ratings existing
        if ( $group[ $setting_group ]['enabled'] == 1 ) {
        	// If no ratings, bail
        	if ($averageRating == 0) {
	        	return $content;
			}
			
			// If ratings, check they are for fields in this group
			$ratingsForGroupFields = false;
			foreach ($group['fields'] as $field) {
				if (isset($ratings[$field['fieldID']])) {
					$ratingsForGroupFields = true;
					break;
				}
			}
			if (!$ratingsForGroupFields) {
				return $content;
			}
        } 
        
        // Start Display
        $rss = "\n"; 
        
        // Display Average       
        if ( $group[ $setting_group ]['average'] ) {
        	$rss .= $group[ $setting_group ]['averageLabel'] . ' ' . $averageRating . '/' . $group['ratingInput']['maxRating'] . "\n";
		}
			
		// Display Breakdown
		if ( $group[ $setting_group ]['showBreakdown'] ) {
			// Iterate through fields
			foreach ( $group['fields'] as $field ) {
				// Rating for Field
				if ( ! isset( $ratings[ $field['fieldID'] ] ) ) {
					$ratings[ $field['fieldID'] ] = 0;
				}
				
				// Field
				$rss .= $field['label'] . ' ' . $ratings[ $field['fieldID'] ] . '/' . $group['ratingInput']['maxRating'] . "\n";
			}
		}
		
		// Apply filters
        $rss = apply_filters( 'comment_rating_field_pro_rating_output_build_comment_rating_rss', $rss, $group );
        
		// Append average rating before or after content
		switch ( $group[ $setting_group ]['position'] ) {

			/**
			* Above
			*/
			case 'above':
				return $rss . $content;
				break;
			
			/**
			* Below
			*/
			case '':
			default:
				return $content . $rss;
				break;

		}

	}

	/**
	* Main function to display rating fields on a comments form
	*
	* Called by:
    * - add_action. $html will be an array of fields
    * - add_filter('comment_form_field_comment'), which sends us the comment form field HTML markup, so we must return this too.
    *
    * @since 3.2.0
	*
	* @param mixed $html Array of fields | HTML markup
	*/
	function display_rating_fields( $comment_field_html = '' ) {

	    // Get markup and apply filters to it
    	$html = $this->build_comment_form_html( $this->group );
        $html = apply_filters( 'crfp_display_rating_field', $html, $this->group );
    	
    	// If $comment_field_html is a non-empty string, then this is called using add_filter, so we always want
    	// to return the comment field first, then the rating field.
    	// Otherwise, OUTPUT the rating field.
    	if ( isset( $comment_field_html ) && ! is_array( $comment_field_html ) && ! empty( $comment_field_html ) ) {
    		// Output comment fields and our HTML
    		return $comment_field_html . $html;
    	} else {
    		// Just output HTML
    		echo $html;
    	}

	}

	/**
	* Main function to create comment rating inputs
	*
	* @since 3.2.0
	*
	* @param array $group 	Field Group
	* @param array $rating 	Existing Rating (optional)
	* @return string 		HTML Form Markup
	*/
	function build_comment_form_html( $group, $ratings = false ) {

		// Output rating fields
    	$html = '';
    	foreach ( $group['fields'] as $key => $field ) {
    		// Define rating value, depending on whether an existing rating value has been defined or not
    		$value = 0;
    		if ( is_array( $ratings ) && isset( $ratings[ $field['fieldID'] ] ) ) {
    			$value = $ratings[ $field['fieldID'] ];
    		}

    		$html .= '<p class="crfp-field crfp-group-' . $group['groupID'] . '" data-required="' . $field['required'] . '" data-required-text="' . $field['required_text'] . '" data-cancel-text="' . $field['cancel_text'] . '">
		        <label for="rating-star-' . $field['fieldID'] . '">' . $field['label'] . '</label>';
		        
		    if ( $group['ratingInput']['enableHalfRatings'] ) {
		    	for ( $i = 0.5; $i <= $group['ratingInput']['maxRating']; $i += 0.5 ) {
	        		$html .= '<input name="rating-star-' . $field['fieldID'] . '" type="radio" class="star' . ( $field['required'] ? ' required' : '' ) . '" value="' . ( (string) $i ) . '"' . checked( $value, ( (string) $i ) , false ) . ' />';
	        	}
			} else {
				for ( $i = 1; $i <= $group['ratingInput']['maxRating']; $i++ ) {
	        		$html .= '<input name="rating-star-' . $field['fieldID'] . '" type="radio" class="star' . ( $field['required'] ? ' required' : '' ) . '" value="' . ( (string) $i ) . '"' . checked( $value, ( (string) $i ) , false ) . ' />';
	        	}
			}
		        
		    $html .='<input type="hidden" name="crfp-rating[' . $field['fieldID'] . ']" value="' . $value . '" />
		    </p>';
    	}

    	return $html;

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