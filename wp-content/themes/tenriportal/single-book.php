<?php
/**
 *
 * Template file for displaying search results 
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package pathways
 */

get_header(); ?>

 	<!--------------------
		
		
		REVIEW MODAL Comment
		
	---------------------->	
	<div class="modal fade" id="writeReviewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">WRITE REVIEW</h4>
	      </div>
	      <div class="modal_comment_form"></div>
	      
	      <div class="modal-body">
		      <?php comment_form(); ?>
		    <!--
	        <div class="review-star">
		        <p class="align-center">
			      <i class="fa fa-star-o fa-lg"></i>
			      <i class="fa fa-star-o fa-lg"></i>
			      <i class="fa fa-star-o fa-lg"></i>
			      <i class="fa fa-star-o fa-lg"></i>
			      <i class="fa fa-star-o fa-lg"></i>
		      	</p>
	        </div>
	        <div class="review-title">
		        <input type="text" class="form-control" id="review-input-title" placeholder="Review Title">
	        </div>
	        <div class="review-detail">
		        <textarea class="form-control" id="review-input-detail" rows="6" placeholder="Review Detail"></textarea>
	        </div>
	        -->
	        <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal" style="float:left"><i class="fa fa-lg fa-close"></i>&nbsp;Cancel</button>
	        <div class="divider" style="padding-bottom:45px">
	        </div>
	      </div>
	      
	    </div>
	  </div>
	</div> 	
	<!--------------------
		
		
		//REVIEW MODAL Comment
		
	---------------------->	      
      
	<div class="container">
		<?php 
			if(have_posts()):
			while(have_posts()):the_post(); 
		
			
			?>
			
		
      	<div class="row single-detail-meta">
	      	
	      	<div class="col-lg-3 col-sm-4">
		      	<div class="thumb-single">
			      	
			      	
			      	<?php if(has_post_thumbnail()): the_post_thumbnail('full'); else: ?>
			      	<img src="<?php bloginfo('template_directory'); ?>/img/default_book.png" alt />
			      	<?php endif; ?>
		      	</div>
	      	</div>
	      	<div class="col-lg-9 col-sm-8">
		      	
		      	<div class="meta-single">
			      	
			      	<!-- title -->
			      	<div class="meta-info">
				      	<h2><?php the_title(); ?></h2>
				      	<h4><?php the_field('book_subtitle'); ?></h4>
			      	</div>
			      	<div class="meta-info">
						<p class="author"><?php if(get_field('book_author')){ the_field('book_author'); }else{ the_field('publisher'); }?></p>				      	
						<p class="translator"><?php if(get_field('translator')): ?>TRANSLATED BY: <?php the_field('translator'); endif;?></p>
			      	</div>
			      	<div class="meta-info">
				      	<p class="publish-date">Published date: <?php the_field('published_date'); ?></p>
				      	<p class="number-pages">Number of Pages: <?php the_field('number_of_pages'); ?></p>
			      	</div>
			      	
			      	<div class="meta-info">
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
			      	<!--suppress review buttons-->
			      	<!--<a href="#reviews" type="button" class="btn btn-primary btn-lg"><i class="fa fa-lg fa-comment"></i> VIEW REVIEW</a>
			      	<button type="button" class="btn btn-warning btn-lg" data-target="#writeReviewModal" data-toggle="modal"><i class="fa fa-lg fa-edit"></i> WRITE REVIEW</button>-->
		      	</div>
		      	
	      	</div>
	      	
	      	
	      	<div class="col-lg-10 col-lg-offset-1 divider col-sm-10">
		      	<hr />
	      	</div>
	      	
      	</div> <!--- main container row -->
      
      
	  	<div class="row">
	      	<div class="col-lg-12 single-detail-text">
		      	<div class="section-header">
			      	<h4 class="fletter">CONTENTS</h4>
		      	</div>
		      	
		      	<p><?php if(get_field('contents')){the_field('contents');}else{ echo "Currently Not Available."; } ?></p>
	      	</div>
	      	
	      	
	      	<div class="col-lg-10 col-lg-offset-1 divider" id="reviews">
		      	<hr />
	      	</div><div class="col-lg-1"></div>
	      	
	  	</div>
	  	<div class="row">
	      	<div class="col-lg-12 single-detail-text">
		      	<div class="section-header">

		      	<h4 class="fletter">DESCRIPTION</h4>
		      	</div>
		      	<?php if(get_field('description')){the_field('description');}else{ echo "Currently Not Available."; } ?>
	      	</div>
	      	
	      	
	      	<div class="col-lg-10 col-lg-offset-1 divider" id="reviews">
		      	<hr />
	      	</div><div class="col-lg-1"></div>
	      	
	      	
	  	</div>
	  	<div class="row">
	      	<div class="col-lg-12 single-detail-text">
		      	<div class="section-header">

		      	<h4 class="fletter">RELATED BOOKS</h4>
		      	</div>
		      	<?php if(get_field('related_books')){the_field('related_books');}else{ echo "Currently Not Available."; } ?>
	      	</div>
	      	
	      	
	      	<div class="col-lg-10 col-lg-offset-1 divider" id="reviews">
		      	<hr />
	      	</div><div class="col-lg-1"></div>
	      	
      	</div>
		
	  	
	  	<div class="row">
		  	<div class="col-lg-12 section-header">
		      	<h4 class="fletter">REVIEWS</h4>
	      	</div>
	    </div>
		
	      	<?php comments_template();  ?>
		<!--
	      	<div class="review-post">
		      	<div class="col-lg-2">
			      	<div class="user-meta">
				      	<div class="user-thumb img-container">
					      	<img src="<?php bloginfo('template_directory'); ?>/img/user1.png" alt />
				      	</div>
				      	<div class="user-info">
					      	<p class="username">YUKIHITO TAKEUCHI</p>
					      	<p class="review-postdate">Posted on: 2016/06/12</p>
				      	</div>
			      	</div>
		      	</div>
		      	
		      	<div class="col-lg-10">
			      	<div class="review_meta ">
				      	<p class="review_star">
					      <i class="fa fa-star fa-lg"></i>
					      <i class="fa fa-star fa-lg"></i>
					      <i class="fa fa-star fa-lg"></i>
					      <i class="fa fa-star fa-lg"></i>
					      <i class="fa fa-star-o fa-lg"></i>
				      	</p>
				      	
				      	<h5 class="review_title">Review Title Here!</h5>
				      	<p class="review_contents">
					      	Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
	
				      	</p>
			      	</div>
		      	</div>
		      	
		      	<div class="col-lg-10 col-lg-offset-1 divider">
			      	<hr />
		      	</div>
		      	
	      	</div><!---- end single review post --
		  	
		  	<div class="review-post">
		      	<div class="col-lg-2">
			      	<div class="user-meta">
				      	<div class="user-thumb img-container">
					      	<img src="<?php bloginfo('template_directory'); ?>/img/user2.png" alt />
				      	</div>
				      	<div class="user-info">
					      	<p class="username">HISAE ADACHI</p>
					      	<p class="review-postdate">Posted on: 2016/06/10</p>
				      	</div>
			      	</div>
		      	</div>
		      	
		      	<div class="col-lg-10">
			      	<div class="review_meta ">
				      	<p class="review_star">
					      <i class="fa fa-star fa-lg"></i>
					      <i class="fa fa-star fa-lg"></i>
					      <i class="fa fa-star fa-lg"></i>
					      <i class="fa fa-star-o fa-lg"></i>
					      <i class="fa fa-star-o fa-lg"></i>
				      	</p>
				      	
				      	<h5 class="review_title">Review Title Here!</h5>
				      	<p class="review_contents">
					      	Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
	
				      	</p>
			      	</div>
		      	</div>
		      	
		      	<div class="col-lg-10 col-lg-offset-1 divider">
			      	<hr />
			    --> 	
			    <?php endwhile; endif; ?>
		      	</div>

	      	</div>
		  	
		  	
		  		
	  	</div><!--- end reviews -->
	  	
	</div> <!-- /container -->  
	
<?php get_footer(); ?>




<?Php
	
	/*
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php echo "archive-book_review.php"?>

		<?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>

			<?php
			endif;

			/* Start the Loop 
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 *
				//get_template_part( 'template-parts/content', get_post_format() );
				get_template_part( 'template-parts/content', 'search');

			endwhile;

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php*/
/*get_sidebar();
get_footer();*/
