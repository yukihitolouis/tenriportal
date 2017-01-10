<?php
/**
* Install class
* 
* @package Comment_Rating_Field_Pro
* @author Tim Carr
* @version 1.0
*/
class Comment_Rating_Field_Pro_Install {

    /**
     * Holds the class object.
     *
     * @since 3.2.6
     *
     * @var object
     */
    public static $instance;

    /**
    * Activation routine
    * - Installs database tables as necessary
    *
    * @since 3.2.0
    *
    * @param bool $network_wide Network Wide activation
    */
    static public function activate( $network_wide = false ) {

        // Check if we are on a multisite install, activating network wide, or a single install
        if ( is_multisite() && $network_wide ) {
            // Multisite network wide activation
            // Iterate through each blog in multisite, creating table
            $sites = wp_get_sites( array( 
                'limit' => 0 
            ) );
            foreach ( $site as $site ) {
                switch_to_blog( $site->blog_id );
                Comment_Rating_Field_Pro_Fields::get_instance()->activate();
                Comment_Rating_Field_Pro_Groups::get_instance()->activate();
                restore_current_blog();
            }
        } else {
            // Single Site
            Comment_Rating_Field_Pro_Fields::get_instance()->activate();
            Comment_Rating_Field_Pro_Groups::get_instance()->activate();
        }

    }

    /**
    * Activation routine when a WPMU site is activated
    * - Installs database tables as necessary
    *
    * We run this because a new WPMU site may be added after the plugin is activated
    * so will need necessary database tables
    *
    * @since 3.2.3
    */
    static public function activate_wpmu_site( $blog_id ) {

        switch_to_blog( $blog_id );
        $this->activate();
        restore_current_blog();

    }

    /**
     * Migrates settings and upgrades database tables when a user has a free version instaled
     * and then goes Pro.
     *
     * Also runs migrations for Pro to Pro version upgrades
     *
     * @since 3.2.0
     */
    public function upgrade() {

        global $wpdb;

        // Get current installed version number
        $installed_version = get_option( 'comment-rating-field-pro-plugin-version' ); // false | 3.2.6

        /**
         * 3.3.5
         * - Adds ratingOutputRSSComments column to DB table
         */
        if ( $installed_version < '3.3.5' ) {
            // Add ratingOutputRSSComments column if it doesn't exist
            $add_rss_column = true;
            $columns = $wpdb->get_results( "SHOW COLUMNS FROM " . $wpdb->prefix . Comment_Rating_Field_Pro_Groups::get_instance()->table );
            foreach ( $columns as $column ) {
                if ( $column->Field == 'ratingOutputRSSComments' ) {
                    $add_rss_column = false;
                }
            }

            if ( $add_rss_column ) {
                $wpdb->query("  ALTER TABLE " . $wpdb->prefix . Comment_Rating_Field_Pro_Groups::get_instance()->table . " 
                                ADD ratingOutputRSSComments text NOT NULL AFTER ratingOutputComments" );
            }

            // Update option
            update_option( 'comment-rating-field-pro-plugin-version', '3.3.5' );
        }

        /**
         * 3.2.7
         * - Add crfp-average-rating and crfp-total-ratings meta keys for post sorting
         * - Add RSS option to Field Groups
         */
        if ( $installed_version < '3.2.7' ) {
            // Get every published Post that doesn't have the average or total rating meta keys
            $posts = new WP_Query( array(
                'post_type'     => 'any',
                'post_status'   => 'publish',
                'posts_per_page'=> -1,
                'meta_query'    => array(
                    'relationship' => 'OR',
                    array(
                        'key'       => 'crfp-average-rating',
                        'value'     => '',
                        'compare'   => 'NOT EXISTS',
                    ),
                    array(
                        'key'       => 'crfp-total-ratings',
                        'value'     => '',
                        'compare'   => 'NOT EXISTS',
                    ),
                ),
            ) );

            foreach ( $posts->posts as $post ) {
                update_post_meta( $post->ID, 'crfp-average-rating', 0 );
                update_post_meta( $post->ID, 'crfp-total-ratings', 0 );
            }

            // Add ratingOutputRSS column if it doesn't exist
            $add_rss_column = true;
            $columns = $wpdb->get_results( "SHOW COLUMNS FROM " . $wpdb->prefix . Comment_Rating_Field_Pro_Groups::get_instance()->table );
            foreach ( $columns as $column ) {
                if ( $column->Field == 'ratingOutputRSS' ) {
                    $add_rss_column = false;
                }
            }

            if ( $add_rss_column ) {
                $wpdb->query("  ALTER TABLE " . $wpdb->prefix . Comment_Rating_Field_Pro_Groups::get_instance()->table . " 
                                ADD ratingOutputRSS text NOT NULL AFTER ratingOutputContent" );
            }

            // Update option
            update_option( 'comment-rating-field-pro-plugin-version', '3.2.7' );
        }
        
        // Check if options data has settings
        // If so, we need to migrate settings from the free version to the pro version
        $settings = get_option( 'comment-rating-field-plugin' );
        if ( ! $settings || ! is_array( $settings ) ) {
            return;
        }

        // Create arrays of settings to be stored in Groups
        $ratingInput = array(
            'position'          => $settings['ratingFieldPosition'],
            'disableReplies'    => $settings['ratingDisableReplies'],
            'enableHalfRatings' => $settings['enableHalfRatings'],    
        );
        $ratingOutputExcerpt = array(
            'enabled'           => $settings['enabled']['averageExcerpt'],
            'position'          => $settings['averageRatingPositionExcerpt'],
            'style'             => $settings['displayStyleExcerpt'],
            'average'           => $settings['displayAverageExcerpt'],
            'averageLabel'      => $settings['averageRatingTextExcerpt'],
            'totalRatings'      => $settings['displayTotalRatingsExcerpt'],
            'showBreakdown'     => $settings['displayBreakdownExcerpt'],
            'linkToComments'    => $settings['displayLinkExcerpt'],
        );
        $ratingOutputContent = array(
            'enabled'           => $settings['enabled']['average'],
            'position'          => $settings['averageRatingPosition'],
            'style'             => $settings['displayStyle'],
            'average'           => $settings['displayAverage'],
            'averageLabel'      => $settings['averageRatingText'],
            'totalRatings'      => $settings['displayTotalRatings'],
            'showBreakdown'     => $settings['displayBreakdown'],
            'linkToComments'    => $settings['displayLink'],
        );
        $ratingOutputRSS = array(
            'enabled'           => 0,
            'position'          => '',
            'totalRatings'      => '',
            'totalRatingsBefore'=> 'from',
            'totalRatingsAfter' => 'ratings',
        );
        $ratingOutputComments = array(
            'enabled'           => $settings['enabled']['comment'],
            'position'          => $settings['commentRatingPosition'],
            'style'             => $settings['displayStyleComment'],
            'average'           => $settings['displayAverageComment'],
            'averageLabel'      => $settings['commentRatingText'],
            'showBreakdown'     => $settings['displayBreakdownComment'],
        );

        // Create Groups Table
        Comment_Rating_Field_Pro_Groups::get_instance()->activate();
            
        // Revise structure of Fields table
        // Check these columns haven't already been created
        $columns = $wpdb->get_results( "SHOW COLUMNS FROM " . $wpdb->prefix . Comment_Rating_Field_Pro_Fields::get_instance()->table );
        $columns_added = false;
        foreach ( $columns as $column ) {
            if ( $column->Field == 'groupID' ) {
                $columns_added = true;
            }
        }
    
        // Revise structure if needed                   
        if ( ! $columns_added ) {
            $wpdb->query("  ALTER TABLE " . $wpdb->prefix . Comment_Rating_Field_Pro_Fields::get_instance()->table . " 
                            ADD groupID int(10) NOT NULL AFTER fieldID" );
            $wpdb->query("  ALTER TABLE " . $wpdb->prefix . Comment_Rating_Field_Pro_Fields::get_instance()->table . " 
                            ADD hierarchy int(10) NOT NULL DEFAULT '0' AFTER groupID" );
        }
                        
        // Iterate through Fields:
        // 1. Create a new group for each Field
        // 2. Migrate placementOptions from field --> group
        // 3. Migrate rating input + output from settings --> group
        $fields = $wpdb->get_results("  SELECT * FROM " . $wpdb->prefix . Comment_Rating_Field_Pro_Fields::get_instance()->table . "
                                        WHERE groupID = 0");
        foreach ($fields as $field) {                       
            // Create group
            $groupID = Comment_Rating_Field_Pro_Groups::get_instance()->save( array(
                'name'                  => $field->label,
                'placementOptions'      => unserialize( $field->placementOptions ),
                'schema_type'           => '',
                'css'                   => '',
                'ratingInput'           => $ratingInput,
                'ratingOutputExcerpt'   => $ratingOutputExcerpt,
                'ratingOutputContent'   => $ratingOutputContent,
                'ratingOutputRSS'       => $ratingOutputRSS,
                'ratingOutputComments'  => $ratingOutputComments,
            ) );
            
            // Update existing field
            Comment_Rating_Field_Pro_Fields::save( array(
                'groupID'   => $groupID,
            ), $field->fieldID );
        }
        
        // Drop Fields placementOptions column if it exists
        foreach ( $columns as $column ) {
            if ( $column->Field == 'placementOptions' ) {
                $wpdb->query("  ALTER TABLE " . $wpdb->prefix . Comment_Rating_Field_Pro_Fields::get_instance()->table . " 
                                DROP placementOptions" );
            }
        }
                            
        // Delete Free version's settings
        delete_option( 'comment-rating-field-plugin' );
                
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