<div class="wrap">
    <h2 class="wpcube"><?php echo $this->base->plugin->displayName; ?> &raquo; <?php _e( 'Licensing', $this->base->plugin->name ); ?></h2>
           
    <?php    
    if ( isset( $this->message ) ) {
        ?>
        <div class="updated fade"><p><?php echo $this->message; ?></p></div>  
        <?php
    }
    if ( isset( $this->errorMessage ) ) {
        ?>
        <div class="error fade"><p><?php echo $this->errorMessage; ?></p></div>  
        <?php
    }
    ?> 
    
    <div id="poststuff">
    	<div id="post-body" class="metabox-holder columns-2">
    		<!-- Content -->
    		<div id="post-body-content">
    		
    			<!-- Form Start -->
		        <form id="post" name="post" method="post" action="admin.php?page=<?php echo $this->base->plugin->name; ?>">
		            <div id="normal-sortables" class="meta-box-sortables ui-sortable">                        
		                <div class="postbox">
		                    <h3 class="hndle"><?php _e( 'License Key', $this->base->plugin->name ); ?></h3>

                            <?php
                            // If the license key is defined in wp-config as a constant, just display it here and don't offer the option to edit
                            if ( defined( strtoupper( $this->base->plugin->name ) . '_LICENSE_KEY' ) ) {
                                $license_key = constant( strtoupper( $this->base->plugin->name ) . '_LICENSE_KEY' );
                                ?>
                                <div class="option">
                                    <input type="text" name="<?php echo $this->base->plugin->name; ?>[licenseKey]" value="<?php echo $license_key; ?>" class="widefat" disabled="disabled" />
                                    <p class="description"><?php _e( 'Your license key is defined in your wp-config.php file; to change it, edit that file.', $this->base->plugin->name ); ?></p>
                                </div>
                                <?php
                            } else {
                                // Get from options table
                                $license_key = get_option( $this->base->plugin->name . '_licenseKey' );
                                ?>
                                <div class="option">
                                    <input type="text" name="<?php echo $this->base->plugin->name; ?>[licenseKey]" value="<?php echo $license_key; ?>" class="widefat" />
                                    
                                </div>
                                <div class="option">
                                    <input type="submit" name="submit" value="<?php _e( 'Save', $this->base->plugin->name ); ?>" class="button button-primary" /> 
                                </div>
                                <?php
                            }
                            ?>
		                </div>
		                <!-- /postbox -->
					</div>
					<!-- /normal-sortables -->
			    </form>
			    <!-- /form end -->
    			
    		</div>
    		<!-- /post-body-content -->
    		
    		<!-- Sidebar -->
    		<div id="postbox-container-1" class="postbox-container">
    			<!-- About -->
                <div class="postbox">
                    <h3 class="hndle"><?php _e( 'About', $this->base->plugin->name ); ?></h3>
                    
                    <div class="option">
                        <div class="left">
                            <strong><?php _e('Version', $this->base->plugin->name); ?></strong>
                        </div>
                        <div class="right">
                            <label><?php echo $this->base->plugin->version; ?></label>
                        </div>
                    </div>
                </div>

                <!-- Support -->
                <div class="postbox">
                    <div class="handlediv" title="Click to toggle"><br /></div>
                    <h3 class="hndle"><span><?php _e('Support', $this->base->plugin->name); ?></span></h3>
                    
                    <div class="option">
                        <a href="<?php echo ( isset( $this->base->plugin->documentationURL ) ? $this->base->plugin->documentationURL : '#' ); ?>" target="_blank" class="button">
                            <?php _e( 'Documentation', $this->base->plugin->name ); ?>
                        </a>
                        <a href="<?php echo ( isset( $this->base->plugin->supportURL ) ? $this->base->plugin->supportURL : '#' ); ?>" class="button button-secondary" target="_blank">
                            <?php _e( 'Support', $this->base->plugin->name ); ?>
                        </a>
                    </div>
                </div>
    		</div>
    		<!-- /postbox-container -->
    	</div>
	</div>       
</div>