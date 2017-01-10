<?php
/**
* Functions - typically for developer use / convinience functions
* 
* @package Comment_Rating_Field_Pro
* @author Tim Carr
* @version 1.0
*/

/**
* Helper method to manually output comment rating fields within a custom comments form
*
* Call this within your WordPress Comment form.  Save routines will be handled by the plugin
*/
if ( ! function_exists( 'display_rating_fields' ) ) {
    function display_rating_fields() {

        Comment_Rating_Field_Pro_Rating_Output::get_instance()->display_rating_fields( '' );

    }
}

/**
* Helper method to manually output the average rating for a Post, Page or Custom Post Type
*
* @param array $atts Display Arguments
*/
if ( ! function_exists( 'display_average_rating' ) ) {
    function display_average_rating( $atts = array() ) {

        global $post;

        // Map attributes to CRFP-valid array
        // This is because the shortcode attribute keys are slightly different from CRFP's array keys
        $instance = array(
            'enabled'           => ( isset( $atts['enabled'] ) ? $atts['enabled'] : 0 ),
            'position'          => '', // N/A
            'style'             => ( isset( $atts['displaystyle'] ) ? $atts['displaystyle'] : 'grey' ),
            'average'           => ( isset( $atts['displayaverage'] ) ? $atts['displayaverage'] : 0 ),
            'averageLabel'      => ( isset( $atts['averageratingtext'] ) ? $atts['averageratingtext'] : '' ),
            'totalRatings'      => ( isset( $atts['displaytotalratings'] ) ? $atts['displaytotalratings'] : 0 ),
            'totalRatingsBefore'=> ( isset( $atts['totalratingsbefore'] ) ? $atts['totalratingsbefore'] : '' ),
            'totalRatingsAfter' => ( isset( $atts['totalratingsafter'] ) ? $atts['totalratingsafter'] : '' ),
            'showBreakdown'     => ( isset( $atts['displaybreakdown'] ) ? $atts['displaybreakdown'] : 0 ),
            'showRatingNumber'  => ( isset( $atts['displayratingnumber'] ) ? $atts['displayratingnumber'] : 0 ),
            'filterComments'    => ( isset( $atts['filtercomments'] ) ? $atts['filtercomments'] : 0 ),
            'linkToComments'    => ( isset( $atts['displaylink'] ) ? $atts['displaylink'] : 0 ),
        );

        // Define the Post ID
        $post_id = ( ! empty( $atts['id'] ) ? $atts['id'] : $post->ID );

        // Check if the Post ID has a rating group
        $group = Comment_Rating_Field_Pro_Groups::get_instance()->get_group_by_post_id( $post_id );
        if ( ! $group ) {
            return;
        }

        // Override the group's shortcode settings with the ones specificed
        $group['ratingOutputShortcode'] = $instance;

        // Build rating HTML
        $html = Comment_Rating_Field_Pro_Rating_Output::get_instance()->build_average_rating_html( $post_id, $group, 'shortcode' );
        $html = apply_filters( 'crfp_display_average_rating', $html, $group, $instance );

        // Output
        echo $html;

    }
}

/**
* Helper method to return Posts ordered by average rating
*/
if ( ! function_exists( 'get_posts_ordered_by_rating' ) ) {
    function get_posts_ordered_by_rating( $query_args ) {

        // Default args
        $default_query_args = array(
            'post_type'     => 'post',
            'post_status'   => 'publish',
            'orderby'       => 'meta_value_num',
            'order'         => 'DESC',
            'meta_key'      => 'crfp-average-rating',
        );

        // Merge with supplied args
        $args = array_merge( $default_query_args, $query_args );

        // Run query
        $posts = new WP_Query( $args );

        // Return posts
        return $posts->posts;

    }
}