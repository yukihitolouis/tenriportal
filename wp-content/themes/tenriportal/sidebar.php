<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package tenriportal
 */
?>


		  	<!-- sidebar -->
		  	<div class="col-lg-3 mobile-hide sidebar-full col-sm-4">
			  	<aside>
				  	<div class="sidebar-section">
					  	<h4>FILTER</h4>
				  	</div>
				  	
				  	<form method="get" id="searchform" action="<?php bloginfo('url'); ?>">
				  	
				  	
					  	<div class="sidebar-section">
					  		
						    <H5>AUTHOR</h5>
						    	<input type="text" placeholder="Search..." class="form-control tw hidden" name="s" />
						    	<div class="form-group tags">
									
										<?php
											/*
											$taxonomy_name = 'post_tag';
											$taxonomys = get_terms($taxonomy_name);
											if(!is_wp_error($taxonomys) && count($taxonomys)):
												foreach($taxonomys as $taxonomy):
													$tax_posts = get_posts(array('post_type' => get_post_type(), 'taxonomy' => $taxonomy_name, 'term' => $taxonomy->slug ) );
													if($tax_posts):
										?>
										<div class="checkbox">
											<label><input type="checkbox" name="post_tag[]" value="<?php echo $taxonomy->slug; ?>"><?php echo $taxonomy->name; ?></label>
										</div>
										<?php
													endif;
												endforeach;
											endif;
											*/
																						
											
											$book_posts = get_posts(array(
												'post_type' => 'book',
												 'nopaging' => true 
												));
											$meta_name = 'book_author';
											$authors = array();	
											foreach($book_posts as $book){
												
												$author = get_post_meta($book->ID,$meta_name,true);
												$authors[] = $author;
												
											}
											$authors = array_unique($authors);
											foreach($authors as $author_name){
												if($author_name):?>
												<div class="checkbox">
													<label><input type="checkbox" name="authors[]" value="<?php echo $author_name; ?>"><?php echo $author_name; ?></label>
												</div>
												<?php endif;
											}
											
										?>
											
										
									
								</div>
										
								
							    	
							
							
							
							
					  	</div>
					  	
					  	<div class="sidebar-section">
					  		
					  		<h5>BOOK GENRES</h5>
					  			<?PHP
						  			/*
						  			$taxName = 'genre';
						  			$post_type = 'book';
						  			$allGenres = get_terms($taxName);
									
									
									if(!is_wp_error($taxonomys) && count($taxonomys)){
										foreach($allGenres as $genre){
											$post_array = array(
												'post_type' => $post_type,
												'taxonomy' => $taxName,
												'term' => $genre->slug
											);
											$tax_posts = get_posts($post_array);
											if($tax_posts){
												
												?>
												<div class="checkbox">
													<label><input type="checkbox" name="book[]" value="<?php echo $genre->slug; ?>"><?php echo $genre->name; ?></label>
												</div>
												
												<?php
												
											}
										}
									}
									

									*/
									

									$taxonomy_name = 'genre';
									$taxonomys = get_terms($taxonomy_name);
									
									    foreach($taxonomys as $taxonomy):
									    	
									        $tax_posts = get_posts(array('post_type' => 'book', 'taxonomy' => $taxonomy_name, 'term' => $taxonomy->slug ) );
									        
									        if($tax_posts):
									?>
									<div class="checkbox">
									<label><input type="checkbox" name="post_tag[]" value="<?php echo $taxonomy->slug; ?>"><?php echo $taxonomy->name; ?></label>
									</div>
									<?php
									        endif;
									    endforeach;
									
						  			
						  			
						  			?>
					  	
					  	
					  	</div>
					  	
					  	<input type="hidden" name="s" />
						<input type="submit" class="searchSubmit green" value="Search" />
					  	
				  	</form>
				  	
				  	
				  	
				  	
			  	</aside>
			  	
			  	
		  	</div>
<?php


if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
	<div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</div><!-- #primary-sidebar -->
<?php endif; ?>


