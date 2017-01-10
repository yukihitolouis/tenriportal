<div class="wrap">
	<h2>
    	<?php echo $this->base->plugin->displayName; ?> &raquo; <?php _e( 'Field Groups', $this->base->plugin->name ); ?>
    	<a href="admin.php?page=<?php echo $this->base->plugin->name; ?>-rating-fields&amp;cmd=form" class="add-new-h2"><?php _e( 'Add New', $this->base->plugin->name ); ?></a>
    	
    	<?php
	    // Search Subtitle
	    if ( isset( $_REQUEST['s'] ) && ! empty( $_REQUEST['s'] ) ) {
	    	?>
	    	<span class="subtitle"><?php _e( 'Search results for', $this->base->plugin->name ); ?> &#8220;<?php echo urldecode( $_REQUEST['s'] ); ?>&#8221;</span>
	    	<?php
	    }
	    ?>
    </h2>
           
    <?php
    // Notices
    foreach ( $notices as $type => $notices_type ) {
        if ( count( $notices_type ) == 0 ) {
            continue;
        }
        ?>
        <div class="<?php echo ( ( $type == 'success' ) ? 'updated' : $type ); ?> notice">
            <?php
            foreach ( $notices_type as $notice ) {
                ?>
                <p><?php echo $notice; ?></p>
                <?php
            }
            ?>
        </div>
        <?php
    }
    ?>
    
	<form action="admin.php?page=<?php echo $this->base->plugin->name; ?>-rating-fields" method="post">
		<p class="search-box">
	    	<label class="screen-reader-text" for="post-search-input"><?php _e(' Search Field Groups', $this->base->plugin->name ); ?>:</label>
	    	<input type="text" id="field-search-input" name="s" value="<?php echo ( isset( $_REQUEST['s'] ) ? $_REQUEST['s'] : ''); ?>" />
	    	<input type="submit" name="search" class="button" value="<?php _e( 'Search Field Groups', $this->base->plugin->name ); ?>" />
	    </p>
	    
		<?php   
		// Output WP_List_Table
		$table = Comment_Rating_Field_Pro_Groups_Table::get_instance();
		$table->prepare_items();
		$table->display(); 
		?>	
	</form>
</div>