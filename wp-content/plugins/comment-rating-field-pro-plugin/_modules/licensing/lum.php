<?php
/**
 * Licensing and Update Manager Class
 * 
 * @package      Licensing Update Manager
 * @author       Tim Carr
 * @version      3.0
 * @copyright    WP Cube
 */
class LicensingUpdateManager {

    /**
     * Flag to determine if we've queried the remote endpoint
     * for updates. Prevents plugin update checks running
     * multiple times
     *
     * @since 1.0.0
     *
     */
    public $update_check = false;

    /**
     * Constructor.
     *
     * @since 1.0.0
     * 
     * @param object $plugin    WordPress Plugin
     * @param string $endpoint  Licensing Endpoint
     */
    public function __construct( $plugin, $endpoint ) {

        // Plugin Details
        $this->plugin = $plugin;
        $this->endpoint = $endpoint;
        
        // Admin Notice
        $this->notice = new stdClass;
        
        if ( is_admin() ) {
            // Check if the licensing form has been submitted
            // If so, save its results before we check the license key validitiy
            if ( isset( $_POST[ $this->plugin->name ] ) && is_array( $_POST[ $this->plugin->name ] ) && array_key_exists( 'licenseKey', $_POST[ $this->plugin->name ] ) ) {
                update_option( $this->plugin->name . '_licenseKey', $_POST[ $this->plugin->name ]['licenseKey'] );
                
                // Force a license key check
                $this->check_license_key_valid( true );
            } else if ( isset( $_GET['page'] ) && $_GET['page'] == $this->plugin->name ) {
                // GET request on licensing screen
                // Force a license key check
                $this->check_license_key_valid( true );
            }
            
            // Hooks and Filters
            add_action( 'admin_notices', array( $this, 'admin_notices' ) );
            add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'api_check' ) );
            add_filter( 'plugins_api', array( $this, 'plugins_api' ), 10, 3 );
        }
        
        // Always perform a license check, so if the transient expires, a fresh check takes place to update the transient.
        $this->check_license_key_valid( false );

    }

    /**
     * Outputs Administration Notices relating to license key validation
     *
     * @since 3.0
     */
    public function admin_notices() {
        
        // If no message has been set, bail
        if ( ! isset( $this->notice->message ) ) {
            return false;
        }

        // Only show message if an error, or a success + we are on the licensing screen
        $screen = get_current_screen();
        if ( ( ! isset( $this->notice->error ) || $this->notice->error == 0 ) && $screen->base != 'toplevel_page_' . $this->plugin->name ) {
            // Success message, but we're not on the licensing screen - bail
            return false;
        }

        // Output message
        echo ( '<div class="' . ( ( isset( $this->notice->error ) && $this->notice->error == 1 ) ? 'error' : 'updated' ) . '">
            <p>' . $this->notice->message . '</p>
        </div>' );
    
    }

    /**
     * Gets the license key from either the wp-config constant, or the options table
     *
     * @since 3.0
     *
     * @return string License Key
     */
    public function get_license_key() {

        // If the license key is defined in wp-config, use that
        if ( defined( strtoupper( $this->plugin->name ) . '_LICENSE_KEY' ) ) {
            $license_key = constant( strtoupper( $this->plugin->name ) . '_LICENSE_KEY' );
        } else {
            // Get from options table
            $license_key = get_option( $this->plugin->name . '_licenseKey' );
        }

        return $license_key;

    }
    
    /**
     * Checks whether a license key has been specified in the settings table.
     * 
     * @since 3.0
     *
     * @return bool License Key Exists
     */                   
    public function check_license_key_exists() {

        // If the license key is defined in wp-config, use that
        if ( defined( strtoupper( $this->plugin->name ) . '_LICENSE_KEY' ) ) {
            $license_key = constant( strtoupper( $this->plugin->name ) . '_LICENSE_KEY' );
        } else {
            // Get from options table
            $license_key = get_option( $this->plugin->name . '_licenseKey' );
        }
        
        // Return license key
        return ( ( isset( $license_key ) && trim( $license_key ) != '' ) ? true : false );
    
    }    
    
    /**
     * Checks whether the license key stored in the settings table exists and is valid.
     *
     * If so, we store the latest remote plugin details in a transient, which can then be used when
     * updating plugins.
     * 
     * @since 3.0
     *
     * @param bool $force   Force License Key Check (used when saving the licensing screen form options)
     * @return bool         License Key Valid
     */
    public function check_license_key_valid( $force = false ) { 

        // Check last result from transient
        // If it exists and is valid, assume the license key is still valid until
        // this transient expires
        if ( ! $force ) {
            if ( absint( get_site_transient( $this->plugin->name . '_valid' ) ) == 1 ) {
                // OK
                return true;
            }
        }
        
        // If here, we're either forcing a check, the transient does not exist / has expired,
        // or the license key wasn't valid last time around, so we need to keep checking.
        if ( ! $this->check_license_key_exists() ) {
            $this->notice->error = 1;
            $this->notice->message = $this->plugin->displayName . __( ': Please specify a license key on the Licensing screen.', $this->plugin->name );
            delete_site_transient( $this->plugin->name . '_valid' );
            delete_site_transient( $this->plugin->name . '_version' );
            delete_site_transient( $this->plugin->name . '_package' );
            return false;
        }

        // Get site URL, excluding http(s), and whether this is an MS install
        $site_url = str_replace( parse_url( get_bloginfo('url'), PHP_URL_SCHEME ) . '://', '', get_bloginfo('url') );
        $is_multisite = ( is_multisite() ? '1' : '0' );

        // Get license key
        $license_key = $this->get_license_key();

        // Build endpoint
        $url = $this->endpoint . "/index.php?request=checkLicenseKeyIsValid&params[]=" . $license_key . '&params[]=' . $this->plugin->name . '&params[]=' . urlencode( $site_url ) . '&params[]=' . $is_multisite;
        
        // Send license key check
        // Set user agent to beat aggressive caching
        $response = wp_remote_get( $url, array(
            'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36',
        ) );
        
        // Check response
        if ( is_wp_error( $response ) ) {
            // Could not connect to licensing server
            // Assume the license key is valid, so the plugin can run, but don't permit updates right now
            set_site_transient( $this->plugin->name . '_valid', 1, ( HOUR_IN_SECONDS * 12 ) );
            delete_site_transient( $this->plugin->name . '_version' );
            delete_site_transient( $this->plugin->name . '_package' );
            return true;
        }

        // Retrieve the body content
        $body = wp_remote_retrieve_body( $response );
        if ( empty( $body ) ) {
            // Something went wrong
            // Assume the license key is valid, so the plugin can run, but don't permit updates right now
            set_site_transient( $this->plugin->name . '_valid', 1, ( HOUR_IN_SECONDS * 12 ) );
            delete_site_transient( $this->plugin->name . '_version' );
            delete_site_transient( $this->plugin->name . '_package' );
            return true;
        }

        // Decode the body JSON into an array
        $result = json_decode( $body );
        
        // Check license key is valid
        if ( (int) $result->code != 1 ) {
            delete_site_transient( $this->plugin->name . '_valid' );
            delete_site_transient( $this->plugin->name . '_version' );
            delete_site_transient( $this->plugin->name . '_package' );
            
            // Define error message as license key is not valid
            $this->notice->error = 1;
            $this->notice->message = $this->plugin->displayName . ': ' . (string) $result->codeDescription;
            return false;   
        }

        // If here, license key is valid
        // Update in plugin settings, and store the remote version and packages available
        $this->notice->error = 0;
        $this->notice->message = $this->plugin->displayName . ': ' . (string) $result->codeDescription;
        set_site_transient( $this->plugin->name . '_valid', 1, ( HOUR_IN_SECONDS * 12 ) );
        set_site_transient( $this->plugin->name . '_version', (string) $result->productVersion, ( HOUR_IN_SECONDS * 12 ) );
        if ( isset( $result->product ) ) {
            set_site_transient( $this->plugin->name . '_package', $result->product );
        }
        
        return true;

    }  
    
    /**
     * Hooks into the plugin update check process, telling WordPress if a newer version of our
     * Plugin is available.
     *
     * @since 3.0
     *
     * @param array $transient
     * @return array Transient Plugin Data
     */
    public function api_check( $transient ) {

        // Check we have a valid license
        if ( ! $this->update_check ) {
            $this->update_check = true;
            if ( ! $this->check_license_key_valid( true ) ) {
                return $transient;
            }
        }

        // If here, we have a valid license
        $remote_version = get_site_transient( $this->plugin->name . '_version' );
        $remote_package = get_site_transient( $this->plugin->name . '_package' );
        if ( $remote_version > $this->plugin->version ) {
            // New version available - add to transient
            $response               = new stdClass;
            $response->new_version  = $remote_package->version;
            $response->slug         = $remote_package->slug;
            $response->plugin       = $this->plugin->name . '/' . $this->plugin->name . '.php';
            $response->url          = $remote_package->homepage;
            $response->package      = $remote_package->download_link;
            
            // Add response to transient array
            $transient->response[ $this->plugin->name . '/' . $this->plugin->name . '.php' ] = $response;
        }
   
        return $transient;

    }

    /**
     * Hooks into the plugins_api process, telling WordPress information about our plugin, such
     * as the WordPress compatible version and the changelog.
     *
     * @since 3.0
     *
     * @param object    $api    The original plugins_api object.
     * @param string    $action The action sent by plugins_api.
     * @param array     $args   Additional args to send to plugins_api.
     * @return object           New stdClass with plugin information on success, default response on failure.
     */
    public function plugins_api( $api, $action = '', $args = null ) {

        // Check if we are getting info for our plugin
        $plugin = ( 'plugin_information' == $action ) && isset( $args->slug ) && ( $this->plugin->name == $args->slug );
        if ( ! $plugin ) {
            return $api;
        }

        // Get remote package data from transients
        // This was populated by the update/license checks earlier
        $remote_package = get_site_transient( $this->plugin->name . '_package' );
        if ( ! $remote_package ) {
            return $api;
        }

        // Create a new stdClass object and populate it with our plugin information.
        $api                        = new stdClass;
        $api->name                  = $remote_package->name;
        $api->slug                  = $remote_package->slug;
        $api->plugin                = $this->plugin->name . '/' . $this->plugin->name . '.php';
        $api->version               = $remote_package->version;
        $api->author                = $remote_package->author;
        $api->author_profile        = $remote_package->author_profile;
        $api->requires              = $remote_package->requires;
        $api->tested                = $remote_package->tested;
        $api->last_updated          = $remote_package->last_updated;
        $api->homepage              = $remote_package->homepage;
        $api->sections['changelog'] = $remote_package->changelog;
        $api->download_link         = $remote_package->download_link;

        // Return the new API object with our custom data.
        return $api;

    }

}