<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
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
	      <div class="modal-body">
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
	        
	        
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal"><i class="fa fa-lg fa-close"></i>&nbsp;Cancel</button>
	        <button type="button" class="btn btn-primary btn-save"><i class="fa fa-lg fa-check"></i>&nbsp;Save Review</button>
	      </div>
	    </div>
	  </div>
	</div> 	
	<!--------------------
		
		
		//REVIEW MODAL Comment
		
	---------------------->	      
      
	<div class="container">
      	<div class="row single-detail-meta">
	      	
	      	<div class="col-lg-3">
		      	<div class="thumb-single">
			      	<img src="<?php bloginfo('template_directory'); ?>/img/book5.png" alt />	
		      	</div>
	      	</div>
	      	<div class="col-lg-9">
		      	
		      	<div class="meta-single">
			      	
			      	<!-- title -->
			      	<div class="meta-info">
				      	<h2><?php the_title(); ?></h2>
				      	<h4>Subtitle is inserted here</h4>
			      	</div>
			      	<div class="meta-info">
						<p class="author">TENRIKYO DOYUSHA</p>				      	
						<p class="translator">TRANSLATED BY: </p>
			      	</div>
			      	<div class="meta-info">
				      	<p class="publish-date">Pulished date: <?php date(); ?></p>
				      	<p class="number-pages">Number of Pages: <?php echo "142"; ?></p>
			      	</div>
			      	
			      	<div class="meta-info">
				      	<p>
					      <i class="fa fa-star fa-lg"></i>
					      <i class="fa fa-star fa-lg"></i>
					      <i class="fa fa-star fa-lg"></i>
					      <i class="fa fa-star fa-lg"></i>
					      <i class="fa fa-star-o fa-lg"></i>
				      	</p>
			      	</div>
			      	<button type="button" class="btn btn-primary btn-lg" data-target="#loginModal" data-toggle="modal"><i class="fa fa-lg fa-comment"></i> VIEW REVIEW</button>
			      	<button type="button" class="btn btn-warning btn-lg" data-target="#writeReviewModal" data-toggle="modal"><i class="fa fa-lg fa-edit"></i> WRITE REVIEW</button>
		      	</div>
		      	
	      	</div>
	      	
	      	
	      	<div class="col-lg-10 col-lg-offset-1 divider">
		      	<hr />
	      	</div>
	      	
      	</div> <!--- main container row -->
      
      
	  	<div class="row">
	      	<div class="col-lg-12 single-detail-text">
		      	<p>
			      	Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
		      	</p>
			  	<p>
Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
		      	</p>
	      	</div>
	      	
	      	
	      	<div class="col-lg-10 col-lg-offset-1 divider">
		      	<hr />
	      	</div>
	      	
      	</div>
	  	
	  	
	  	<div class="row">
		  	<div class="col-lg-12 section-header">
		      	<h4 class="fletter">REVIEWS</h3>
	      	</div>
	      	
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
		      	
	      	</div><!---- end single review post -->
		  	
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
		      	</div>
		      	
	      	</div>
		  	
		  	
		  		
	  	</div><!--- end reviews -->
	  	
	</div> <!-- /container -->  
	
<?php get_footer(); ?>