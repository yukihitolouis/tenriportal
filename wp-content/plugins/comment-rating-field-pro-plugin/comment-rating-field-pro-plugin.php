<?php
/**
* Plugin Name: Comment Rating Field Pro
* Plugin URI: http://www.wpcube.co.uk/plugins/comment-rating-field-pro-plugin
* Version: 3.3.6
* Author: WP Cube
* Author URI: http://www.wpcube.co.uk
* Description: Add one or more 5 star rating fields to the comments form in WordPress, with granular options by Post Type and Taoxnomy.
*/

/**
* Comment Rating Field Pro Class
* 
* @package WP Cube
* @subpackage CRFP
* @author Tim Carr
* @version 1.0.0
* @copyright WP Cube
*/
class Comment_Rating_Field_Pro {

    /**
     * Holds the class object.
     *
     * @since 3.2.6
     *
     * @var object
     */
    public static $instance;

    /**
     * Plugin
     *
     * @since 1.0
     *
     * @var object
     */
    public $plugin = '';

    /**
     * Holds the dashboard class object.
     *
     * @since 3.3.2
     *
     * @var object
     */
    public $dashboard = '';

    /**
     * Holds the licensing class object.
     *
     * @since 3.3.2
     *
     * @var object
     */
    public $licensing = '';

    /**
    * Constructor. Acts as a bootstrap to load the rest of the plugin
    *
    * @since 1.0.0
    */
    function __construct() {

        // Plugin Details
        $this->plugin = new stdClass;
        $this->plugin->name         = 'comment-rating-field-pro-plugin';
        $this->plugin->displayName  = 'Comment Rating Field Pro';
        $this->plugin->version      = '3.3.6';
        $this->plugin->buildDate    = '2016-07-03 05:00:00';
        $this->plugin->requires     = 3.6;
        $this->plugin->tested       = '4.5.3';
        $this->plugin->folder       = plugin_dir_path( __FILE__ );
        $this->plugin->url          = plugin_dir_url( __FILE__ );
        $this->plugin->documentationURL = 'https://www.wpcube.co.uk/documentation/comment-rating-field-pro-plugin';
        $this->plugin->supportURL   = 'https://www.wpcube.co.uk/support';

        // Dashboard Submodule
        if ( ! class_exists( 'WPCubeDashboardWidget' ) ) {
            require_once( $this->plugin->folder . '_modules/dashboard/dashboard.php' );
        }
        $this->dashboard = new WPCubeDashboardWidget( $this->plugin );

        // Licensing Submodule
        if ( ! class_exists( 'LicensingUpdateManager' ) ) {
            require_once( $this->plugin->folder . '_modules/licensing/lum.php' );
        }
        $this->licensing = new LicensingUpdateManager( $this->plugin, 'http://www.wpcube.co.uk/wp-content/plugins/lum', $this->plugin->name );
        
        // Global Required
        require_once( $this->plugin->folder . 'includes/global/fields.php' );
        require_once( $this->plugin->folder . 'includes/global/groups.php' );

        // Global
        if ( $this->licensing->check_license_key_valid() ) {

            require_once( $this->plugin->folder . 'includes/global/ajax.php' );
            require_once( $this->plugin->folder . 'includes/global/common.php' );
            require_once( $this->plugin->folder . 'includes/global/functions.php' );
            require_once( $this->plugin->folder . 'includes/global/rating-input.php' );
            require_once( $this->plugin->folder . 'includes/global/rating-output.php' );
            require_once( $this->plugin->folder . 'includes/global/shortcode.php' );
            require_once( $this->plugin->folder . 'includes/global/widgets.php' );  

            // Init non-static classes
            $ajax = Comment_Rating_Field_Pro_AJAX::get_instance();
            $common = Comment_Rating_Field_Pro_Common::get_instance();
            $input = Comment_Rating_Field_Pro_Rating_Input::get_instance();
            $output = Comment_Rating_Field_Pro_Rating_Output::get_instance();
            $shortcode = Comment_Rating_Field_Pro_Shortcode::get_instance();
            $widgets = Comment_Rating_Field_Pro_Widgets::get_instance();

        }

        // Admin
        if ( is_admin() ) {
            // Required class
            require_once( $this->plugin->folder . 'includes/admin/admin.php' );
            require_once( $this->plugin->folder . 'includes/admin/install.php' );
            
            // Init non-static classes
            $admin = Comment_Rating_Field_Pro_Admin::get_instance();
            $install = Comment_Rating_Field_Pro_Install::get_instance();

            // Run upgrade routine
            $install->upgrade();

            // Licensed
            if ( $this->licensing->check_license_key_valid() ) {
                // Load licensed files
                require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
                require_once( $this->plugin->folder . 'includes/admin/comments.php' );
                require_once( $this->plugin->folder . 'includes/admin/editor.php' );
                require_once( $this->plugin->folder . 'includes/admin/groups-table.php' );
                require_once( $this->plugin->folder . 'includes/admin/post.php' );

                // Init non-static classes
                $admin_comments = Comment_Rating_Field_Pro_Admin_Comments::get_instance();
                $admin_editor = Comment_Rating_Field_Pro_Editor::get_instance();
                $admin_post = Comment_Rating_Field_Pro_Post::get_instance();
            }
        }
        
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

// Initialise class
$crfpPro = Comment_Rating_Field_Pro::get_instance();

// Register activation hooks
register_activation_hook( __FILE__, array( 'Comment_Rating_Field_Pro_Install', 'activate' ) );
add_action( 'activate_wpmu_site', array( 'Comment_Rating_Field_Pro_Install', 'activate_wpmu_site' ) );