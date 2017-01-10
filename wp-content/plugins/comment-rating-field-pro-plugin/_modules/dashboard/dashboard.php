<?php
/**
 * Dashboard Widget
 * 
 * @package     WP Cube
 * @subpackage  Dashboard
 * @author      Tim Carr
 * @version     1.0.0
 * @copyright   WP Cube
 */
class WPCubeDashboardWidget {  

    /**
     * Holds the plugin object
     *
     * @since 1.0.0
     *
     * @var object
     */
    public $plugin; 

    /**
     * Holds the exact path to this file's folder
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $dashboard_folder;

    /**
     * Holds the exact URL to this file's folder
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $dashboard_url;  

	/**
	 * Constructor
     *
     * @since 1.0
	 *
	 * @param object $plugin Plugin Object (name, displayName, version, folder, url)
	 */
	public function __construct( $plugin ) {

		// Set class vars
        $this->plugin           = $plugin;
        $this->dashboard_folder = plugin_dir_path( __FILE__ );
        $this->dashboard_url    = plugin_dir_url( __FILE__ );

		// Admin CSS, JS and Menu
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts_css' ) );
        add_action( str_replace( '-', '_', $this->plugin->name ) . '_admin_menu', array( $this, 'admin_menu' ), 99 );

        // Export and Support
        add_action( 'plugins_loaded', array( $this, 'export' ) );
        add_action( 'plugins_loaded', array( $this, 'maybe_redirect' ) );

	}     
	
	/**
     * Register and enqueue shared Admin UI CSS
     *
     * @since 1.0
     */
    public function admin_scripts_css() {    

    	// JS
    	wp_register_script( 'wpcube-admin-conditional', $this->dashboard_url . 'js/jquery.form-conditionals.js', array( 'jquery' ), $this->plugin->version, true );
        wp_register_script( 'wpcube-admin-clipboard', $this->dashboard_url . 'js/clipboard.js', array( 'jquery' ), $this->plugin->version, true );
        wp_register_script( 'wpcube-admin-media-library', $this->dashboard_url . 'js/media-library.js', array( 'jquery' ), $this->plugin->version, true );
        wp_register_script( 'wpcube-admin-tabs', $this->dashboard_url . 'js/tabs.js', array( 'jquery' ), $this->plugin->version, true );
        wp_register_script( 'wpcube-admin-tags', $this->dashboard_url . 'js/tags.js', array( 'jquery' ), $this->plugin->version, true );
        wp_register_script( 'wpcube-admin', $this->dashboard_url . 'js/min/admin-min.js', array( 'jquery' ), $this->plugin->version, true );
    	   
    	// CSS
        wp_enqueue_style( 'wpcube-admin', $this->dashboard_url . 'css/admin.css' ); 

    }	

    /**
     * Add Import / Export + Support Menu Links to the WordPress Administration interface
     *
     * @since 1.0.0
     */
    public function admin_menu() {

        add_submenu_page( $this->plugin->name, __( 'Import & Export', $this->plugin->name ), __( 'Import & Export', $this->plugin->name), 'manage_options', $this->plugin->name . '-import-export', array( $this, 'import_export_screen' ) ); 
        add_submenu_page( $this->plugin->name, __( 'Support', $this->plugin->name ), __( 'Support', $this->plugin->name ), 'manage_options', $this->plugin->name . '-support', array( $this, 'support_screen' ) );
    
    }

    /**
     * Import / Export Screen
     *
     * @since 1.0.0
     */
    public function import_export_screen() {

        // Import
        if ( isset( $_POST['submit'] ) ) {
            // Check nonce
            if ( ! isset( $_POST[ $this->plugin->name . '_nonce'] ) ) {
                // Missing nonce    
                $this->errorMessage = __( 'nonce field is missing. Settings NOT saved.', $this->plugin->name );
            } elseif ( ! wp_verify_nonce( $_POST[ $this->plugin->name . '_nonce' ], $this->plugin->name ) ) {
                // Invalid nonce
                $this->errorMessage = __( 'Invalid nonce specified. Settings NOT saved.', $this->plugin->name );
            } else {       
                // Import
                if ( ! is_array( $_FILES ) ) {
                    $this->errorMessage = __( 'No JSON file uploaded.', $this->plugin->name );
                } elseif ( $_FILES['import']['error'] != 0 ) {
                    $this->errorMessage = __( 'Error when uploading JSON file.', $this->plugin->name );
                } else {
                    $handle = fopen( $_FILES['import']['tmp_name'], 'r' );
                    $json = fread( $handle, $_FILES['import']['size'] );
                    fclose( $handle );
                    $json_arr = json_decode( $json, true );
                    
                    // Run import routine
                    $result = $this->import( $json_arr );
                    if ( ! $result ) {
                        $this->errorMessage = __( 'Supplied file is not a valid JSON settings file, or has become corrupt.', $this->plugin->name );
                    } else {
                        $this->message = __( 'Settings imported.', $this->plugin->name );
                    }   
                }
            }
        }
        
        // Output view
        include_once( $this->dashboard_folder . '/views/import-export.php' );

    }
    
    /**
     * Support Screen
     *
     * @since 1.0.0
     */
    public function support_screen() {   
        // We never reach here, as we redirect earlier in the process
    }
    
    /**
     * Imports the given JSON
     *
     * @since 1.0.0
     *
     * @param   array   $json_arr   JSON Array (settings|data keys)
     * @return  bool                Result
     */
    public function import( $json_arr ) {
        
        // Import Settings
        // Newer JSON exports store settings in the 'settings' array key
        // Older JSON exports store settings and nothing else
        $settings = ( ! array_key_exists( 'settings', $json_arr ) ? $json_arr : $json_arr['settings'] );
        if ( is_array( $settings ) ) {  
            if ( isset($this->plugin->settingsName ) ) {
                delete_option( $this->plugin->settingsName );
                update_option( $this->plugin->settingsName, $settings );    
            } else {
                delete_option( $this->plugin->name );
                update_option( $this->plugin->name, $settings );
            }
        }
        
        // Import Data
        if ( array_key_exists( 'data', $json_arr ) ) {
            // Data exists - fire action which main plugin hooks into to import data
            do_action( $this->plugin->name . '-import', $json_arr['data'] );
        }
        
        // Done
        return true;
        
    }
    
    /**
     * If we have requested the export JSON, force a file download
     *
     * @since 1.0.0
     */ 
    public function export() {

        // Check we are on the right page
        if ( ! isset( $_GET['page'] ) ) {
            return;
        }
        if ( $_GET['page'] != $this->plugin->name . '-import-export' ) {
            return;
        }
        if ( ! isset( $_GET['export'] ) ) {
            return;
        }
        if ( $_GET['export'] != 1 ) {
            return;
        }
        
        // Get settings
        $settings = get_option( $this->plugin->name );
        
        // If settings are false, we masy be using settingsName
        if ( ! $settings && isset( $this->plugin->settingsName ) ) {
            $settings = get_option( $this->plugin->settingsName );
        }
        
        // Get any other data from the main plugin
        // Main plugin can hook into this filter and return an array of data
        $data = array();
        $data = apply_filters( $this->plugin->name . '-export', $data );
        
        // Build final array
        $json_arr = array(
            'settings'  => $settings,
            'data'      => $data,
        );
        
        // Build JSON
        $json = json_encode( $json_arr );
        
        header( "Content-type: application/x-msdownload" );
        header( "Content-Disposition: attachment; filename=export.json" );
        header( "Pragma: no-cache" );
        header( "Expires: 0" );
        echo $json;
        exit();

    }

    /**
     * If the Support menu item was clicked, redirect
     *
     * @since 3.0
     */
    public function maybe_redirect() {

        // Check we requested the support page
        if ( ! isset( $_GET['page'] ) ) {
            return;
        }
        if ( $_GET['page'] != $this->plugin->name . '-support' ) {
            return;
        }

        // Redirect
        wp_redirect( 'https://www.wpcube.co.uk/support' );
        die();

    }
    
	/**
     * Adds a dashboard widget to list WP Cube Products + News
     *
     * Checks if another WP Cube plugin has already created this widget - if so, doesn't duplicate it
     *
     * @since 1.0.0
     */
    public function dashboard_widget() {

    	global $wp_meta_boxes;

        // Check if another plugin has already registered this widget
    	if ( isset( $wp_meta_boxes['dashboard']['normal']['core']['wp_cube'] ) ) {
            return;
        }

        // Register widget 
    	wp_add_dashboard_widget( 'wp_cube', 'WP Cube', array( &$this, 'output_dashboard_widget' ) );

    }
    
    /**
     * Includes dashboard.php to output the Dashboard Widget
     *
     * @since 1.0.0
     */
    public function output_dashboard_widget() {

    	$result = wp_remote_get( 'https://www.wpcube.co.uk/plugins/feed/' );
    	if ( ! is_wp_error( $result ) ) {
	    	if ( $result['response']['code'] == 200 ) {
	    		$xml = simplexml_load_string( $result['body'] );
	    		$products = $xml->channel;
	    	}
	    	
	    	include_once( $this->dashboard_folder . '/views/dashboard.php' );
    	} else {
    		include_once( $this->dashboard_folder . '/views/dashboard_nodata.php' );
    	}

    }

}