<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package pathways
 */
get_header();
?>	

		  	
	  	
	  	<div class="row">
		  	
		  	
		  	<?php get_sidebar(); ?>

		  	
		  	<div class="col-lg-9 col-sm-8">
			  	
			  	
			  	<?php if(have_posts()): while(have_posts()): the_post(); ?>
			  	<article>
				  	<a href="<?php the_permalink(); ?>">
				  		<div class="row">
					  		<div class="col-lg-3 col-xs-4">
						      	<div class="thumb img-container">
							      	<?php if(has_post_thumbnail()): the_post_thumbnail('thumbnail'); else: ?>
							      	<img src="<?php bloginfo('template_directory'); ?>/img/default_book.png" alt />
							      	<?php endif; ?>
						      	</div>
						  	</div>
					  		
					  		<div class="col-lg-9 col-xs-8">
						      	<div class="book-meta">
							      	
							      	<div class="meta-info">
								      	<h2><?php the_title(); ?></h2>
								      	<h4><?php the_field('subtitle'); ?></h4>
							      	</div>
							      	<div class="meta-info">
										<p class="author"><?php if(get_field('book_author')){ the_field('book_author'); }else{ the_field('publisher'); }?></p>				      	
							      	</div>
							      	
							      	<div class="meta-info stars mobile-hide">
								      	<?php
									      	$cfrp_id = get_post()->ID;
											$instance = array(
											    'enabled' => 2,
											    'displaystyle' => 'grey',
											    'displayaverage' => 0,
											    'filtercomments' => 0,
											    'averageratingtext' => '',
											    'displaytotalratings' => 1,
											    'totalratingsbefore' => 'From:',
											    'totalratingsafter' => 'Ratings',
											    'displaybreakdown' => 1,
											    'displaylink' => 0,
											    'id' => $cfrp_id,
											);
											if ( function_exists( 'display_average_rating' ) ) {
											    display_average_rating( $instance ); 
										}
										?>
								      	
							      	</div>
							      	
							      	<p class="review_contents">
								      	<?php if(get_field('contents')){the_field('contents');}else{ echo "Book detail is currently Not Available."; } ?>
							      	</p>
							      	
							      	<div class="meta-info stars mobile-show">
								      	<p>
									      <i class="fa fa-star fa-lg"></i>
									      <i class="fa fa-star fa-lg"></i>
									      <i class="fa fa-star fa-lg"></i>
									      <i class="fa fa-star fa-lg"></i>
									      <i class="fa fa-star-o fa-lg"></i>
									     &nbsp; <span class="comment-blue"><i class="fa fa-comment fa-lg"></i>&nbsp;2</span>
								      	</p>
							      	</div>
							      	
						      	</div>
						      	
					      	</div>
					  		
				  		</div>
				  		
				  		<div class="col-lg-12 divider">
					      	<hr />
				      	</div>
				  	</a>
			  	</article>
			  	<?php endwhile; endif; ?>
			  	
			  	
			  	
			  	
	      	</div>
	      	
	      	
	      	
	      	
		  	
		  	
		  		
	  	</div>
	  	
	</div> <!-- /container -->  
<?Php get_footer(); ?>