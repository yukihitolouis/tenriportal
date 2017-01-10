<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package tenriportal
 */
get_header();
 ?>

<div class="container">
      <!-- Example row of columns -->      
      	<div class="row">
	      	<div class="col-lg-10 col-lg-offset-2 section-header">
		      	<h4 class="fletter">FEATURED</h3>
	      	</div>
	  		<div class="slick col-lg-8 col-lg-offset-2">
		  		<?php
			  		$args = array(
            			'post_type'=>'book',
            			'posts_per_page'=>30,
            			'order_by' => 'rand'
            			);
            			
            		query_posts($args);
		  		
		  		
		  		if(have_posts()): while(have_posts()): the_post(); ?>
			  		<article>
			  			<a href="<?php the_permalink(); ?>">
					  		<div class="bookpost">
						      	<div class="thumb img-container">
							      	<?php if(has_post_thumbnail()): the_post_thumbnail('full'); else: ?>
							      	<img src="<?php bloginfo('template_directory'); ?>/img/default_book.png" alt />
							      	<?php endif; ?>
						      	</div>
						      	<div class="post_meta">
							      	<h5><?php the_title();?></h5>
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
					      	</div>
			  			</a>
			  		</article>
		      	<?php 
			      	endwhile; endif; 
			      	wp_reset_query();
		      	?>
	      	
	  		
	      	
	  		</div>
	      	<!--
	      	<div class="col-lg-1 arrow-right">
		      	<div class="arrow-box">
			  		<i class="fa fa-chevron-right fa-lg"></i>
		  		</div>
	      	</div>
	      	-->
	      	<div class="col-lg-10 col-lg-offset-1 divider">
		      	<hr />
	      	</div>
	      	
      	</div>
      
      
	  	<div class="row">
	      	<div class="col-lg-10 col-lg-offset-2 section-header">
		      	<h4 class="fletter">NEW BOOKS</h3>
	      	</div>
	      	
	  		<div class="slick col-lg-8 col-lg-offset-2">
		  		<?php
			  		$args = array(
            			'post_type'=>'book',
            			'posts_per_page'=>8,
            			'order_by' => 'date',
            			'order'   => 'DESC',
            			);
            			
            		query_posts($args);
		  		
		  		
		  		if(have_posts()): while(have_posts()): the_post(); ?>
			  		<article>
			  			<a href="<?php the_permalink(); ?>">
					  		<div class="bookpost">
						      	<div class="thumb img-container">
							      	<?php if(has_post_thumbnail()): the_post_thumbnail('full'); else: ?>
							      	<img src="<?php bloginfo('template_directory'); ?>/img/default_book.png" alt />
							      	<?php endif; ?>
						      	</div>
						      	<div class="post_meta">
							      	<h5><?php the_title();?></h5>
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
					      	</div>
			  			</a>
			  		</article>
		      	<?php 
			      	endwhile; endif; 
			      	wp_reset_query();
		      	?>
	      	
	  		
	      	
	  		</div>
	      	
	      	<div class="col-lg-10 col-lg-offset-1 divider">
		      	<hr />
	      	</div>
	      	
      	</div>
	  	
	  	
	  	<div class="row">
		  	<div class="col-lg-10 col-lg-offset-2 section-header">
		      	<h4 class="fletter">LATEST REVIEW</h3>
	      	</div>
	      	
	      	<?php 
		      	$args = array(
			      	'number'=>1,
			      	'orderby'=>'date',
		      	);
		      	$latest_comment = get_comments($args);
		      	foreach($latest_comment as $comment):
	      	?>
	      	<div class="col-lg-2 col-lg-offset-2">
		      	<div class="thumb img-container">
			      	<?php $comment_thumb = get_the_post_thumbnail($comment->comment_post_ID);?>
			      	<?php if($comment_thumb): echo $comment_thumb; else: ?>
			      	<img src="<?php bloginfo('template_directory'); ?>/img/default_book.png" alt />
			      	<?php endif; ?>
		      	</div>		      	
	      	</div>
	      	
	      	<div class="col-lg-6">
		      	<div class="review_meta">
			      	<?php 
				     $cfrp_id = $comment->comment_post_ID;
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
			      	<p class="reviewer"><?php echo $comment->comment_author; ?></p>
			      	<p class="review_date">Posted on <?php echo $comment->comment_date; ?></p>
			      	<h5 class="review_title"></h5>
			      	<p class="review_contents">
				      	<?php echo $comment->comment_content; ?>
			      	</p>
		      	</div>
		      	
	      	</div>
		  	<?php endforeach; ?>
		  	<div class="col-lg-10 col-lg-offset-1 divider">
		      	<hr />
	      	</div>
	      	
	      	
		  		
	  	</div>
	  	
	</div> <!-- /container -->  
<?php get_footer(); ?>