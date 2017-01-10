<?php
/**
* Editor class
* 
* @package Comment_Rating_Field_Pro
* @author Tim Carr
* @version 1.0
*/
class Comment_Rating_Field_Pro_Editor {

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

        add_action( 'init', array( $this, 'setup_tinymce_plugins' ) );

    }

    /**
    * Setup calls to add a button and plugin to the Page Generator Pro WP_Editor
    *
    * @since 3.2.0
    */
    function setup_tinymce_plugins() {

        // Check user has capabilites to edit posts or pages
        if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
            return;
		}

		// Check if rich editing is enabled for the user
        if ( get_user_option( 'rich_editing' ) != 'true' ) {
        	return;
        }

        // Add filters to register TinyMCE Plugins
		add_filter( 'mce_external_plugins', array( &$this, 'register_tinymce_plugins' ) );
        add_filter( 'mce_buttons', array( &$this, 'register_tinymce_buttons' ) );

    }

    /**
    * Register JS plugins for the TinyMCE Editor
    *
    * @since 3.2.0
    *
    * @param array $plugins JS Plugins
    * @return array 		JS Plugins
    */
    function register_tinymce_plugins( $plugins ) {

    	$plugins['crfp']= Comment_Rating_Field_Pro::get_instance()->plugin->url . 'assets/js/min/editor_plugin-min.js';
	    
	    return $plugins;

    }

    /**
    * Registers buttons in the TinyMCE Editor
    *
    * @since 3.2.0
    *
    * @param array $buttons Buttons
    * @return array 		Buttons
    */
    function register_tinymce_buttons( $buttons ) {

    	array_push( $buttons, 'crfp' );
    	return $buttons;

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