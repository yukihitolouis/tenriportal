<div class="wrap">
    <h2>
    	<?php 
	    if ( isset( $_GET['id'] ) && $_GET['id'] > 0 ) {
		    _e( 'Edit Field Group', 'comment-rating-field-pro-plugin' );
	    } else {
		    _e( 'Add Field Group', 'comment-rating-field-pro-plugin' );
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
    
    <div id="poststuff">
    	<div id="post-body" class="metabox-holder columns-2">
    		<form id="post" class="<?php echo 'comment-rating-field-pro-plugin'; ?>" name="post" method="post" action="admin.php?page=<?php echo 'comment-rating-field-pro-plugin'; ?>-rating-fields&amp;cmd=form<?php echo ( isset( $_GET['id'] ) ? '&id=' . $_GET['id'] : '' ); ?>" enctype="multipart/form-data">		
	    		<!-- Content -->
	    		<div id="post-body-content">
		    		<!-- Name -->
		    		<div id="titlediv">
		    			<div id="titlewrap">
			    			<input type="text" name="name" id="title" value="<?php echo $group['name']; ?>" size="30" placeholder="<?php _e( 'Field Group Name', 'comment-rating-field-pro-plugin' ); ?>" />
		    				<input type="hidden" name="id" id="id" value="<?php echo ( isset( $group['groupID'] ) ? $group['groupID'] : '' ); ?>" />
		    			</div>
		    		</div>

		            <div id="normal-sortables" class="meta-box-sortables ui-sortable">                        
		               	<!-- Schema Type -->
		               	<div class="postbox">
	                        <h3 class="hndle"><?php _e( 'General', 'comment-rating-field-pro-plugin' ); ?></h3>
	                        <div class="option">
								<div class="left">
									<strong><?php _e( 'Schema Type', 'comment-rating-field-pro-plugin' ); ?></strong>
								</div>
								<div class="right">
									<select name="schema_type" size="1">
										<?php
										foreach ( Comment_Rating_Field_Pro_Groups::get_instance()->schemas as $schema_type => $label ) {
											?>
											<option value="<?php echo $schema_type; ?>"<?php selected( $group['schema_type'], $schema_type ); ?>>
												<?php echo $label; ?>
											</option>
											<?php
										}
										?>
									</select>

									<p class="description">
				                        <?php _e( 'The schema type / rich snippets markup type that these reviews are left for. This helps Google, Bing and Yahoo show star ratings and relevant information in the search engine results.', 'comment-rating-field-pro-plugin' ); ?>
			                        </p>
		                        </div>
							</div>
							
							<div class="option">
								<div class="left">
									<strong><?php _e( 'Empty Color', 'comment-rating-field-pro-plugin' ); ?></strong>
								</div>
								<div class="right">
									<input type="text" name="css[starBackgroundColor]" value="<?php echo $group['css']['starBackgroundColor']; ?>" class="color-picker-control" />
								
									<p class="description">
				                        <?php _e( 'The color of empty stars/bars.', 'comment-rating-field-pro-plugin' ); ?>
			                        </p>
		                        </div>
							</div>
							
							<div class="option">
								<div class="left">
									<strong><?php _e( 'Filled Color', 'comment-rating-field-pro-plugin' ); ?></strong>
								</div>
								<div class="right">
									<input type="text" name="css[starColor]" value="<?php echo $group['css']['starColor']; ?>" class="color-picker-control" />
								
									<p class="description">
				                        <?php _e( 'The color of filled stars/bars.', 'comment-rating-field-pro-plugin' ); ?>
			                        </p>
		                        </div>
							</div>
							
							<div class="option">
								<div class="left">
									<strong><?php _e( 'Selected Stars Color', 'comment-rating-field-pro-plugin' ); ?></strong>
								</div>
								<div class="right">
									<input type="text" name="css[starInputColor]" value="<?php echo $group['css']['starInputColor']; ?>" class="color-picker-control" />
								
									<p class="description">
				                        <?php _e('The color of selected stars when adding a rating on the comments form.', 'comment-rating-field-pro-plugin'); ?>
			                        </p>
			                    </div>
							</div>
							
							<div class="option">
								<div class="left">
									<strong><?php _e( 'Star Size', 'comment-rating-field-pro-plugin' ); ?></strong>
								</div>
								<div class="right">
									<input type="number" name="css[starSize]" min="16" max="128" step="1" value="<?php echo $group['css']['starSize']; ?>" />
									<?php _e( 'px', 'comment-rating-field-pro-plugin' ); ?>
								
									<p class="description">
				                        <?php _e( 'The size, in pixels, to output stars.', 'comment-rating-field-pro-plugin' ); ?>
			                        </p>
			                    </div>
							</div>
		               	</div>
		               
		                <!-- Fields -->
	                    <div class="postbox">
	                        <h3 class="hndle"><?php _e( 'Rating Fields', 'comment-rating-field-pro-plugin' ); ?></h3>
	                        <div class="option">
		                        <p class="description">
			                        <?php _e( 'Define the rating fields to display in this group.', 'comment-rating-field-pro-plugin' ); ?>
		                        </p>
	                        </div>
	                        
	                        <div id="sortable">
		                    	<?php
			                    // Output existing fields
			                    foreach ( $group['fields'] as $field ) {
			                    	?>
				                    <div class="option">
				                        <div class="left">
				                        	<strong>
					                        	<a href="#" class="dashicons dashicons-sort"></a>
					                        	<span><?php _e( 'Field', 'comment-rating-field-pro-plugin'); ?> #</span>
					                        	<span class="hierarchy"><?php echo $field['hierarchy']; ?></span>
					                        </strong>
					                    </div>
										<div class="right">
				                        	<input type="text" name="fields[label][]" value="<?php echo $field['label']; ?>" placeholder="<?php _e( 'Label', 'comment-rating-field-pro-plugin' ); ?>" />
				                        	<select name="fields[required][]" size="1">
					                        	<option value="0"<?php echo ( ( $field['required'] != 1 ) ? ' selected' : '' ); ?>>
					                        		<?php _e( 'Not Required', 'comment-rating-field-pro-plugin' ); ?>
					                        	</option>
					                        	<option value="1"<?php selected( $field['required'], 1 ); ?>>
					                        		<?php _e( 'Required', 'comment-rating-field-pro-plugin' ); ?>
					                        	</option>
					                        </select>
					                        <input type="text" name="fields[required_text][]" value="<?php echo $field['required_text']; ?>" placeholder="<?php _e( 'Required Text', 'comment-rating-field-pro-plugin' ); ?>" />
				                        	<input type="text" name="fields[cancel_text][]" value="<?php echo $field['cancel_text']; ?>" placeholder="<?php _e( 'Cancel Text', 'comment-rating-field-pro-plugin' ); ?>" />
				                        	<input type="hidden" name="fields[fieldID][]" value="<?php echo $field['fieldID']; ?>" />
				                        	
				                        	<!-- Delete -->
				                        	<a href="#" class="dashicons dashicons-trash delete-rating-field" title="<?php _e( 'Delete Rating Field', 'comment-rating-field-pro-plugin' ); ?>"></a>
				                        </div>
			                        </div>
				                    <?php
			                    }
			                    ?>    
	                        </div>
	                        
	                        <!-- Hidden Option -->
	                        <div class="field hidden">
		                        <div class="option">
			                        <div class="left">
			                        	<strong>
				                        	<a href="#" class="dashicons dashicons-sort"></a>
				                        	<span><?php _e( 'Field', 'comment-rating-field-pro-plugin'); ?> #</span>
				                        	<span class="hierarchy"></span>
				                        </strong>
				                    </div>
									<div class="right">
			                        	<input type="text" name="fields[label][]" value="" placeholder="<?php _e( 'Label', 'comment-rating-field-pro-plugin' ); ?>" />
			                        	<select name="fields[required][]" size="1">
				                        	<option value="0"><?php _e( 'Not Required', 'comment-rating-field-pro-plugin' ); ?></option>
				                        	<option value="1"><?php _e( 'Required', 'comment-rating-field-pro-plugin' ); ?></option>
				                        </select>
			                        	<input type="text" name="fields[required_text][]" value="" placeholder="<?php _e( 'Required Text', 'comment-rating-field-pro-plugin' ); ?>" />
			                        	<input type="text" name="fields[cancel_text][]" value="" placeholder="<?php _e( 'Cancel Text', 'comment-rating-field-pro-plugin' ); ?>" />

			                        	<!-- Delete -->
					                    <a href="#" class="dashicons dashicons-trash delete-rating-field" title="<?php _e( 'Delete Rating Field', 'comment-rating-field-pro-plugin' ); ?>"></a>    
			                        </div>
		                        </div>
	                        </div>
	                        
	                        <!-- Add -->
	                        <div class="option">
	                        	<button class="button button-primary add-rating-field"><?php _e( 'Add Field', 'comment-rating-field-pro-plugin' ); ?></button>
	                        </div>
						</div>
						
						<!-- Rating Input -->
						<div class="postbox">
	                        <h3 class="hndle"><?php _e( 'Rating Input', 'comment-rating-field-pro-plugin' ); ?></h3>
	                        
	                        <div class="option">
		                        <p class="description">
			                        <?php _e( 'Controls where the rating field(s) are displayed on the Comment Form.', 'comment-rating-field-pro-plugin' ); ?>
		                        </p>
	                        </div>

	                        <!-- Max Rating -->
	                        <div class="option">
                            	<div class="left">
                            		<strong><?php _e( 'Maximum Rating', 'comment-rating-field-pro-plugin' ); ?></strong>
                            	</div>
								<div class="right">
                                	<select name="ratingInput[maxRating]" size="1">
                                		<?php
                                		foreach ( Comment_Rating_Field_Pro_Common::get_instance()->get_max_rating_options() as $key => $value ) {
                                			?>
                                			<option value="<?php echo $key; ?>" <?php selected( $group['ratingInput']['maxRating'], $key ); ?>>
                                				<?php echo $value; ?>
                                			</option>
                                			<?php
                                		}
                                		?>
									</select>
	                            </div>
                            </div>
	                        
	                        <!-- Position -->
	                        <div class="option">
                            	<div class="left">
                            		<strong><?php _e( 'Position', 'comment-rating-field-pro-plugin' ); ?></strong>
                            	</div>
								<div class="right">
                                	<select name="ratingInput[position]" size="1">
                                    	<option value=""<?php selected( $group['ratingInput']['position'], '' ); ?>>
											<?php _e( 'After Comment Field', 'comment-rating-field-pro-plugin' ); ?>
										</option>
										<option value="middle"<?php selected( $group['ratingInput']['position'], 'middle' ); ?>>
											<?php _e( 'Before Comment Field', 'comment-rating-field-pro-plugin' ); ?>
										</option>
										<option value="above"<?php selected( $group['ratingInput']['position'], 'above' ); ?>>
											<?php _e( 'Top of Comment Form / Before All Comment Fields', 'comment-rating-field-pro-plugin' ); ?>
										</option>
									</select>
                                
	                                <p class="description">
	                                	<?php _e( 'Note: For Jetpack Comments, rating fields will only be positioned above the fields.  This is due to Jetpack Comments restrictions.', 'comment-rating-field-pro-plugin' ); ?>
	                                </p>
	                            </div>
                            </div>
                            
                            <!-- Limit -->
                            <div class="option">
                            	<div class="left">
                            		<strong><?php _e( 'Limit Rating', 'comment-rating-field-pro-plugin' ); ?></strong>
                            	</div>
								<div class="right">
                                	<select name="ratingInput[limitRating]" size="1">
                                    	<option value="0"<?php selected( $group['ratingInput']['limitRating'], 0 ); ?>>
                                    		<?php _e( 'No', 'comment-rating-field-pro-plugin' ); ?>
                                    	</option>
                                    	<option value="1"<?php selected( $group['ratingInput']['limitRating'], 1 ); ?>>
                                    		<?php _e( 'Yes', 'comment-rating-field-pro-plugin' ); ?>
                                    	</option>
                                    </select>
                               
	                                <p class="description">
		                                <?php _e( 'If enabled, prevents reviewers from leaving more than one rating.', 'comment-rating-field-pro-plugin' ); ?>
	                                </p>
	                            </div>
                            </div>
                            
                            <!-- Disable on Replies -->
                            <div class="option">
                            	<div class="left">
                            		<strong><?php _e( 'Disable on Replies', 'comment-rating-field-pro-plugin' ); ?></strong>
                            	</div>
								<div class="right">
                                	<select name="ratingInput[disableReplies]" size="1">
                                    	<option value="0"<?php selected( $group['ratingInput']['disableReplies'], 0 ); ?>>
											<?php _e( 'No', 'comment-rating-field-pro-plugin' ); ?>
										</option>
										<option value="1"<?php selected( $group['ratingInput']['disableReplies'], 1 ); ?>>
											<?php _e( 'Yes', 'comment-rating-field-pro-plugin' ); ?>
										</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Half / .5 Ratings -->
                            <div class="option">
                            	<div class="left">
                            		<strong><?php _e( 'Half / .5 Ratings', 'comment-rating-field-pro-plugin' ); ?></strong>
                            	</div>
								<div class="right">
                                	<select name="ratingInput[enableHalfRatings]" size="1">
                                    	<option value="0"<?php selected( $group['ratingInput']['enableHalfRatings'], 0 ); ?>>
											<?php _e( 'No', 'comment-rating-field-pro-plugin' ); ?>
										</option>
										<option value="1"<?php selected( $group['ratingInput']['enableHalfRatings'], 1 ); ?>>
											<?php _e( 'Yes', 'comment-rating-field-pro-plugin' ); ?>
										</option>
                                    </select>
                                </div>
                            </div>
						</div>

						<?php
						// Iterate through excerpt, comment and RSS groups to output settings
						foreach ( Comment_Rating_Field_Pro_Groups::get_instance()->get_output_group_types() as $key => $labels ) {
							?>
							<div class="postbox">
		                        <h3 class="hndle"><?php echo $labels['title']; ?></h3>

		                        <div class="option">
			                        <p class="description">
				                        <?php echo sprintf( __( 'Allows you to display average ratings anywhere where your theme outputs %s.', 'comment-rating-field-pro-plugin' ), $labels['type'] ); ?>
			                        </p>
		                        </div>
		                        
		                        <!-- Enabled -->
		                        <div class="option">
	                            	<div class="left">
	                            		<strong><?php _e( 'Enabled', 'comment-rating-field-pro-plugin' ); ?></strong>
	                            	</div>
									<div class="right">
	                                	<select name="<?php echo $key; ?>[enabled]" size="1" data-conditional="<?php echo $key; ?>-options">
	                                    	<option value="0"<?php selected( $group[ $key ]['enabled'], 0 ); ?>>
												<?php _e( 'Never Display', 'comment-rating-field-pro-plugin' ); ?>
											</option>
											<option value="1"<?php selected( $group[ $key ]['enabled'], 1 ); ?>>
												<?php _e( 'Display when Ratings Exist', 'comment-rating-field-pro-plugin' ); ?>
											</option>
											<option value="2"<?php selected( $group[ $key ]['enabled'], 2 ); ?>>
												<?php _e( 'Always Display', 'comment-rating-field-pro-plugin' ); ?>
											</option>
	                                    </select>
	                                </div>
	                            </div>
	                            
	                            <div id="<?php echo $key; ?>-options">  
	                            	<?php
	                            	// Position
	                            	if ( isset( $group[ $key ]['position'] ) ) {
	                            		?>                     
			                            <!-- Position -->
				                        <div class="option">
			                            	<div class="left">
			                            		<strong><?php _e( 'Position', 'comment-rating-field-pro-plugin' ); ?></strong>
			                            	</div>
											<div class="right">
			                                	<select name="<?php echo $key; ?>[position]" size="1">
			                                    	<option value=""<?php selected( $group[ $key ]['position'], '' ); ?>>
														<?php _e( 'Below Content', 'comment-rating-field-pro-plugin' ); ?>
													</option>
													<option value="above"<?php selected( $group[ $key ]['position'], 'above' ); ?>>
														<?php _e( 'Above Content', 'comment-rating-field-pro-plugin' ); ?>
													</option>
												</select>
			                                </div>
			                            </div>
			                            <?php
			                        }

		                            // Style
	                            	if ( isset( $group[ $key ]['style'] ) ) {
	                            		?> 
			                            <!-- Style -->
				                        <div class="option">
			                            	<div class="left">
			                            		<strong><?php _e( 'Style', 'comment-rating-field-pro-plugin' ); ?></strong>
			                            	</div>
											<div class="right">
			                                	<select name="<?php echo $key; ?>[style]" size="1">
			                                    	<option value=""<?php selected( $group[ $key ]['style'], '' ); ?>>
														<?php _e( 'Filled Color Only', 'comment-rating-field-pro-plugin' ); ?>
													</option>
													<option value="grey"<?php selected( $group[ $key ]['style'], 'grey' ); ?>>
														<?php _e( 'Filled and Empty Colors', 'comment-rating-field-pro-plugin' ); ?>
													</option>
			                                    </select>
			                                </div>
			                            </div>
			                            <?php
			                        }

			                        // Average
	                            	if ( isset( $group[ $key ]['average'] ) ) {
	                            		?> 
			                            <!-- Average -->
				                        <div class="option">
			                            	<div class="left">
			                            		<strong><?php _e( 'Show Average', 'comment-rating-field-pro-plugin' ); ?></strong>
			                            	</div>
											<div class="right">
			                                	<select name="<?php echo $key; ?>[average]" size="1">
			                                    	<option value="0"<?php selected( $group[ $key ]['average'], 0 ); ?>>
														<?php _e( 'No', 'comment-rating-field-pro-plugin' ); ?>
													</option>
													<option value="1"<?php selected( $group[ $key ]['average'], 1 ); ?>>
														<?php _e( 'Yes, using Stars', 'comment-rating-field-pro-plugin' ); ?>
													</option>
													<option value="2"<?php selected( $group[ $key ]['average'], 2 ); ?>>
														<?php _e( 'Yes, using Bars (Amazon Style)', 'comment-rating-field-pro-plugin' ); ?>
													</option>
												</select>
			                                   	<input type="text" name="<?php echo $key; ?>[averageLabel]" value="<?php echo $group[ $key ]['averageLabel']; ?>" placeholder="<?php _e( 'Average Rating Label', 'comment-rating-field-pro-plugin' ); ?>" />
			                                </div>
			                            </div>
			                            <?php
			                        }

			                        // Position
	                            	if ( isset( $group[ $key ]['totalRatings'] ) ) {
	                            		?> 
			                            <!-- Total Ratings -->
			                            <div class="option">
			                            	<div class="left">
			                            		<strong><?php _e( 'Total Ratings', 'comment-rating-field-pro-plugin' ); ?></strong>
			                            	</div>
											<div class="right">
			                                	<select name="<?php echo $key; ?>[totalRatings]" size="1">
			                                    	<option value=""<?php selected( $group[ $key ]['totalRatings'], '' ); ?>>
			                                    		<?php _e( 'Do not display', 'comment-rating-field-pro-plugin' ); ?>
			                                    	</option>
			                                     	<option value="1"<?php selected( $group[ $key ]['totalRatings'], 1 ); ?>>
			                                     		<?php _e( 'Display', 'comment-rating-field-pro-plugin' ); ?>
			                                     	</option>
			                                   	</select>

			                                   	<input type="text" name="<?php echo $key; ?>[totalRatingsBefore]" value="<?php echo ( isset( $group[ $key ]['totalRatingsBefore'] ) ? $group[ $key ]['totalRatingsBefore'] : '' ); ?>" placeholder="<?php _e( 'Before Text', 'comment-rating-field-pro-plugin' ); ?>" />
			                                   	<input type="text" name="<?php echo $key; ?>[totalRatingsAfter]" value="<?php echo ( isset( $group[ $key ]['totalRatingsAfter'] ) ? $group[ $key ]['totalRatingsAfter'] : '' );; ?>" placeholder="<?php _e( 'After Text', 'comment-rating-field-pro-plugin' ); ?>" />
			                                </div>
			                            </div>
			                            <?php
			                        }

			                        // Show Breakdown
	                            	if ( isset( $group[ $key ]['showBreakdown'] ) ) {
	                            		?> 
			                            <!-- Show Breakdown -->
			                            <div class="option">
			                            	<div class="left">
			                            		<strong><?php _e( 'Show Breakdown', 'comment-rating-field-pro-plugin' ); ?></strong>
			                            	</div>
											<div class="right">
			                                	<select name="<?php echo $key; ?>[showBreakdown]" size="1">
			                                    	<option value="0"<?php selected( $group[ $key ]['showBreakdown'], 0 ); ?>>
			                                    		<?php _e( 'No', 'comment-rating-field-pro-plugin' ); ?>
			                                    	</option>
			                                     	<option value="1"<?php selected( $group[ $key ]['showBreakdown'], 1 ); ?>>
			                                    		<?php _e( 'Yes, using Stars', 'comment-rating-field-pro-plugin' ); ?>
			                                    	</option>
			                                    </select>
			                                </div>
			                            </div>
			                            <?php
			                        }

			                        // Show Rating Number
			                        if ( isset( $group[ $key ]['showRatingNumber'] ) ) {
	                            		?> 
			                        	<!-- Show Rating Number -->
			                            <div class="option">
			                            	<div class="left">
			                            		<strong><?php _e( 'Show Rating Number', 'comment-rating-field-pro-plugin' ); ?></strong>
			                            	</div>
											<div class="right">
			                                	<select name="<?php echo $key; ?>[showRatingNumber]" size="1">
			                                    	<option value="0"<?php selected( $group[ $key ]['showRatingNumber'], 0 ); ?>>
			                                    		<?php _e( 'No', 'comment-rating-field-pro-plugin' ); ?>
			                                    	</option>
			                                     	<option value="1"<?php selected( $group[ $key ]['showRatingNumber'], 1 ); ?>>
			                                     		<?php _e( 'Yes, as Number', 'comment-rating-field-pro-plugin' ); ?>
			                                     	</option>
			                                     	<option value="2"<?php selected( $group[ $key ]['showRatingNumber'], 2 ); ?>>
			                                     		<?php _e( 'Yes, as Percentage', 'comment-rating-field-pro-plugin' ); ?>
			                                     	</option>
												</select>
			                                </div>
			                            </div>
			                            <?php
			                        }

			                        // Filter Comments
			                        if ( isset( $group[ $key ]['filterComments'] ) ) {
	                            		?> 
			                            <!-- Average -->
				                        <div class="option">
			                            	<div class="left">
			                            		<strong><?php _e( 'Filter Comments', 'comment-rating-field-pro-plugin' ); ?></strong>
			                            	</div>
											<div class="right">
			                                	<select name="<?php echo $key; ?>[filterComments]" size="1">
			                                    	<option value="0"<?php selected( $group[ $key ]['filterComments'], 0 ); ?>>
														<?php _e( 'No', 'comment-rating-field-pro-plugin' ); ?>
													</option>
													<option value="1"<?php selected( $group[ $key ]['filterComments'], 1 ); ?>>
														<?php _e( 'Yes', 'comment-rating-field-pro-plugin' ); ?>
													</option>
												</select>
			                                </div>
			                            </div>
			                            <?php
			                        }

			                        // Link to Comments
	                            	if ( isset( $group[ $key ]['linkToComments'] ) ) {
	                            		?> 
			                            <!-- Link to Comments -->
			                            <div class="option">
			                            	<div class="left">
			                            		<strong><?php _e( 'Link to Comments', 'comment-rating-field-pro-plugin' ); ?></strong>
			                            	</div>
											<div class="right">
			                                	<select name="<?php echo $key; ?>[linkToComments]" size="1">
			                                    	<option value="0"<?php selected( $group[ $key ]['linkToComments'], 0 ); ?>>
														<?php _e( 'No', 'comment-rating-field-pro-plugin' ); ?>
													</option>
													<option value="1"<?php selected( $group[ $key ]['linkToComments'], 1 ); ?>>
														<?php _e( 'Yes', 'comment-rating-field-pro-plugin' ); ?>
													</option>
												</select>
			                                </div>
			                            </div>
			                            <?php
			                        }
			                        ?>
	                            </div>
	                            <!-- ./extra-options -->
							</div>
							<?php
						} // Close foreach
						?>
						
						<!-- Rating Output: Comments -->
						<div class="postbox">
	                        <h3 class="hndle"><?php _e( 'Rating Output: Comments', 'comment-rating-field-pro-plugin' ); ?></h3>

	                        <div class="option">
		                        <p class="description">
			                        <?php _e( 'Defines how ratings are displayed on Comments.', 'comment-rating-field-pro-plugin' ); ?>
		                        </p>
	                        </div>
	                        
	                        <!-- Enabled -->
	                        <div class="option">
                            	<div class="left">
                            		<strong><?php _e( 'Enabled', 'comment-rating-field-pro-plugin' ); ?></strong>
                            	</div>
								<div class="right">
                                	<select name="ratingOutputComments[enabled]" size="1" data-conditional="comment-options">
                                    	<option value="0"<?php selected( $group['ratingOutputComments']['enabled'], 0 ); ?>>
                                    		<?php _e( 'Never Display', 'comment-rating-field-pro-plugin' ); ?>
                                    	</option>
                                    	<option value="1"<?php selected( $group['ratingOutputComments']['enabled'], 1 ); ?>>
                                    		<?php _e( 'Display when Ratings Exist', 'comment-rating-field-pro-plugin' ); ?>
                                    	</option>
                                    	<option value="2"<?php selected( $group['ratingOutputComments']['enabled'], 2 ); ?>>
                                    		<?php _e( 'Always Display', 'comment-rating-field-pro-plugin' ); ?>
                                    	</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div id="comment-options">
	                            <!-- Position -->
		                        <div class="option">
	                            	<div class="left">
	                            		<strong><?php _e( 'Position', 'comment-rating-field-pro-plugin' ); ?></strong>
	                            	</div>
									<div class="right">
	                                	<select name="ratingOutputComments[position]" size="1">
	                                    	<option value=""<?php selected( $group['ratingOutputComments']['position'], '' ); ?>>
												<?php _e( 'Below Comment Text', 'comment-rating-field-pro-plugin' ); ?>
											</option>
											<option value="above"<?php selected( $group['ratingOutputComments']['position'], 'above' ); ?>>
												<?php _e( 'Above Comment Text', 'comment-rating-field-pro-plugin' ); ?>
											</option>
	                                    </select>
	                                </div>
	                            </div>
	                            
	                            <!-- Style -->
		                        <div class="option">
	                            	<div class="left">
	                            		<strong><?php _e( 'Style', 'comment-rating-field-pro-plugin' ); ?></strong>
	                            	</div>
									<div class="right">
	                                	<select name="ratingOutputComments[style]" size="1">
	                                    	<option value=""<?php selected( $group['ratingOutputComments']['style'], '' ); ?>>
												<?php _e( 'Filled Color Only', 'comment-rating-field-pro-plugin' ); ?>
											</option>
	                                     	<option value="grey"<?php selecteD( $group['ratingOutputComments']['style'], 'grey' ); ?>>
												<?php _e( 'Filled and Empty Colors', 'comment-rating-field-pro-plugin' ); ?>
											</option>
	                                   	</select>
	                                </div>
	                            </div>
	                            
	                            <!-- Average -->
		                        <div class="option">
	                            	<div class="left">
	                            		<strong><?php _e( 'Show Average', 'comment-rating-field-pro-plugin' ); ?></strong>
	                            	</div>
									<div class="right">
	                                	<select name="ratingOutputComments[average]" size="1">
	                                    	<option value="0"<?php selected( $group['ratingOutputComments']['average'], 0 ); ?>>
	                                    		<?php _e( 'No', 'comment-rating-field-pro-plugin' ); ?>
	                                    	</option>
	                                     	<option value="1"<?php selected( $group['ratingOutputComments']['average'], 1 ); ?>>
	                                     		<?php _e( 'Yes, using Stars', 'comment-rating-field-pro-plugin' ); ?>
	                                     	</option>
	                                   	</select>
	                                   	<input type="text" name="ratingOutputComments[averageLabel]" value="<?php echo $group['ratingOutputComments']['averageLabel']; ?>" placeholder="<?php _e( 'Average Rating Label', 'comment-rating-field-pro-plugin' ); ?>" / >
	                                </div>
	                            </div>
	                            
	                            <!-- Show Breakdown -->
	                            <div class="option">
	                            	<div class="left">
	                            		<strong><?php _e( 'Show Breakdown', 'comment-rating-field-pro-plugin' ); ?></strong>
	                            	</div>
									<div class="right">
	                                	<select name="ratingOutputComments[showBreakdown]" size="1">
	                                    	<option value="0"<?php selected( $group['ratingOutputComments']['showBreakdown'], 0 ); ?>>
	                                    		<?php _e( 'No', 'comment-rating-field-pro-plugin' ); ?>
	                                    	</option>
	                                     	<option value="1"<?php selected( $group['ratingOutputComments']['showBreakdown'], 1 ); ?>>
	                                     		<?php _e( 'Yes, using Stars', 'comment-rating-field-pro-plugin' ); ?>
	                                     	</option>
										</select>
	                                </div>
	                            </div>

	                            <!-- Show Rating Number -->
	                            <div class="option">
	                            	<div class="left">
	                            		<strong><?php _e( 'Show Rating Number', 'comment-rating-field-pro-plugin' ); ?></strong>
	                            	</div>
									<div class="right">
	                                	<select name="ratingOutputComments[showRatingNumber]" size="1">
	                                    	<option value="0"<?php selected( $group['ratingOutputComments']['showRatingNumber'], 0 ); ?>>
	                                    		<?php _e( 'No', 'comment-rating-field-pro-plugin' ); ?>
	                                    	</option>
	                                     	<option value="1"<?php selected( $group['ratingOutputComments']['showRatingNumber'], 1 ); ?>>
	                                     		<?php _e( 'Yes, as Number', 'comment-rating-field-pro-plugin' ); ?>
	                                     	</option>
	                                     	<option value="2"<?php selected( $group['ratingOutputComments']['showRatingNumber'], 2 ); ?>>
	                                     		<?php _e( 'Yes, as Percentage', 'comment-rating-field-pro-plugin' ); ?>
	                                     	</option>
										</select>
	                                </div>
	                            </div>
	                            
                            </div>
                            <!-- ./extra-options -->
                            
						</div>

						<!-- Rating Output: RSS Comments -->
						<div class="postbox">
	                        <h3 class="hndle"><?php _e( 'Rating Output: RSS Comments', 'comment-rating-field-pro-plugin' ); ?></h3>

	                        <div class="option">
		                        <p class="description">
			                        <?php _e( 'Defines how ratings are displayed on Comment RSS Feeds.', 'comment-rating-field-pro-plugin' ); ?>
		                        </p>
	                        </div>
	                        
	                        <!-- Enabled -->
	                        <div class="option">
                            	<div class="left">
                            		<strong><?php _e( 'Enabled', 'comment-rating-field-pro-plugin' ); ?></strong>
                            	</div>
								<div class="right">
                                	<select name="ratingOutputRSSComments[enabled]" size="1" data-conditional="rss-comment-options">
                                    	<option value="0"<?php selected( $group['ratingOutputRSSComments']['enabled'], 0 ); ?>>
                                    		<?php _e( 'Never Display', 'comment-rating-field-pro-plugin' ); ?>
                                    	</option>
                                    	<option value="1"<?php selected( $group['ratingOutputRSSComments']['enabled'], 1 ); ?>>
                                    		<?php _e( 'Display when Ratings Exist', 'comment-rating-field-pro-plugin' ); ?>
                                    	</option>
                                    	<option value="2"<?php selected( $group['ratingOutputRSSComments']['enabled'], 2 ); ?>>
                                    		<?php _e( 'Always Display', 'comment-rating-field-pro-plugin' ); ?>
                                    	</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div id="rss-comment-options">
	                            <!-- Position -->
		                        <div class="option">
	                            	<div class="left">
	                            		<strong><?php _e( 'Position', 'comment-rating-field-pro-plugin' ); ?></strong>
	                            	</div>
									<div class="right">
	                                	<select name="ratingOutputRSSComments[position]" size="1">
	                                    	<option value=""<?php selected( $group['ratingOutputRSSComments']['position'], '' ); ?>>
												<?php _e( 'Below Comment Text', 'comment-rating-field-pro-plugin' ); ?>
											</option>
											<option value="above"<?php selected( $group['ratingOutputRSSComments']['position'], 'above' ); ?>>
												<?php _e( 'Above Comment Text', 'comment-rating-field-pro-plugin' ); ?>
											</option>
	                                    </select>
	                                </div>
	                            </div>
	                            
	                            <!-- Average -->
		                        <div class="option">
	                            	<div class="left">
	                            		<strong><?php _e( 'Show Average', 'comment-rating-field-pro-plugin' ); ?></strong>
	                            	</div>
									<div class="right">
	                                	<select name="ratingOutputRSSComments[average]" size="1">
	                                    	<option value="0"<?php selected( $group['ratingOutputRSSComments']['average'], 0 ); ?>>
	                                    		<?php _e( 'No', 'comment-rating-field-pro-plugin' ); ?>
	                                    	</option>
	                                     	<option value="1"<?php selected( $group['ratingOutputRSSComments']['average'], 1 ); ?>>
	                                     		<?php _e( 'Yes', 'comment-rating-field-pro-plugin' ); ?>
	                                     	</option>
	                                   	</select>
	                                   	<input type="text" name="ratingOutputRSSComments[averageLabel]" value="<?php echo $group['ratingOutputRSSComments']['averageLabel']; ?>" placeholder="<?php _e( 'Average Rating Label', 'comment-rating-field-pro-plugin' ); ?>" / >
	                                </div>
	                            </div>
	                            
	                            <!-- Show Breakdown -->
	                            <div class="option">
	                            	<div class="left">
	                            		<strong><?php _e( 'Show Breakdown', 'comment-rating-field-pro-plugin' ); ?></strong>
	                            	</div>
									<div class="right">
	                                	<select name="ratingOutputRSSComments[showBreakdown]" size="1">
	                                    	<option value="0"<?php selected( $group['ratingOutputRSSComments']['showBreakdown'], 0 ); ?>>
	                                    		<?php _e( 'No', 'comment-rating-field-pro-plugin' ); ?>
	                                    	</option>
	                                     	<option value="1"<?php selected( $group['ratingOutputRSSComments']['showBreakdown'], 1 ); ?>>
	                                     		<?php _e( 'Yes', 'comment-rating-field-pro-plugin' ); ?>
	                                     	</option>
										</select>
	                                </div>
	                            </div>
                            </div>
                            <!-- ./extra-options -->
                            
						</div>
		                
		            	<!-- Save -->
		                <div class="submit">
			                <?php wp_nonce_field( 'save_group', 'comment-rating-field-pro-plugin_nonce' ); ?>
		                	<input type="submit" name="submit" value="<?php _e( 'Save', 'comment-rating-field-pro-plugin' ); ?>" class="button-primary" />
		                </div>
					</div>
					<!-- /normal-sortables -->	    			
	    		</div>
	    		<!-- /post-body-content -->
	    		
	    		<!-- Sidebar -->
	    		<div id="postbox-container-1" class="postbox-container">
	    		    <!-- Targeted Placement Options -->
	                <div class="postbox targeted-placement-options">
	                    <h3 class="hndle"><?php _e( 'Targeted Placement Options', 'comment-rating-field-pro-plugin' ); ?></h3>

                		<?php
                        // Go through all Post Types
                    	$post_types = Comment_Rating_Field_Pro_Common::get_instance()->get_post_types();
                    	foreach ( $post_types as $type => $post_type ) {
                    		?>
                    		<div class="option">
	                    		<label for="placement_options_type_<?php echo $post_type->name; ?>">
									<div class="left">
										<strong><?php echo sprintf( __( 'Enable on %s', 'comment-rating-field-pro-plugin' ), $post_type->labels->name ); ?></strong>
									</div>
									<div class="right">
										<input id="placement_options_type_<?php echo $post_type->name; ?>" type="checkbox" name="placementOptions[type][<?php echo $type; ?>]" value="1"<?php echo ( isset( $group['placementOptions']['type'][ $type ] ) ? ' checked' : ''); ?> />
									</div>
								</label>
							</div>
                        	
                    		<?php
                    		// Go through all taxonomies for this Post Type
                    		$taxonomies = Comment_Rating_Field_Pro_Common::get_instance()->get_post_type_taxonomies( $type );
                    		
                    		// Skip post types with no taxonomies
                    		if ( empty( $taxonomies ) || count( $taxonomies ) == 0 ) {
                    			continue;
                    		}

                    		// Iterate through taxonomy
                    		foreach ( $taxonomies as $tax_prog_name => $taxonomy ) {
                    			$terms = get_terms( $tax_prog_name, array(
									'hide_empty' => 0
								) );
            					?>
            					<div class="option">
            						<strong><?php _e( 'Enable on '.$post_type->labels->singular_name . ' ' . $taxonomy->label ); ?></strong>
            	
                					<div class="tax-selection">
                						<div class="tabs-panel trigger-tax-<?php echo $type; ?>" style="height: 70px;">
                							<ul class="list:category categorychecklist form-no-clear" style="margin: 0; padding: 0;">				                    			
												<?php
												foreach ( $terms as $term_key => $term ) {
				                                    ?>
				                                    <li>
														<label class="selectit">
															<input type="checkbox" name="placementOptions[tax][<?php echo $tax_prog_name; ?>][<?php echo $term->term_id; ?>]" value="1"<?php echo ( isset( $group['placementOptions']['tax'][ $tax_prog_name ][ $term->term_id ] ) ? ' checked' : '' ); ?> class="trigger-tax-<?php echo $type; ?>" />
															<?php echo $term->name; ?>      
														</label>
													</li>
				                                    <?php
												}	
												?>
											</ul>
										</div>
									</div>
								</div>
								<?php
							}
						}
                    	?>
					</div>

	                <!-- Save -->
	                <div class="postbox targeted-placement-options">
	                    <h3 class="hndle"><?php _e( 'Publish', 'comment-rating-field-pro-plugin' ); ?></h3>
	                    <div class="inside">
							<input type="submit" name="submit" value="<?php _e( 'Save', 'comment-rating-field-pro-plugin'); ?>" class="button button-primary" />
	                	</div>
	                </div>
			
	    		</div>
	    		<!-- /postbox-container -->
    		</form>
    	</div>
	</div>       
</div>