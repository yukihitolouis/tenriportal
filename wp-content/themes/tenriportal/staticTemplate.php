<?php /* Template Name: StaticTemplate */ ?>
<?php
/**
 * The template for displaying "about" pages.
 */

get_header(); ?>

<?php
  // TO SHOW THE PAGE CONTENTS
  while ( have_posts() ) : the_post(); ?> <!--Because the_content() works only inside a WP Loop -->
  	<div class="container">
	  	<div class="row">
      	<div class="col-lg-12 single-detail-text">
					<h2><?php echo wp_title('') ?></h2>
	      	<p>
						<?php the_content(); ?> <!-- Page Content -->
	      	</p>
      	</div>
      	<div class="col-lg-10 col-lg-offset-1 divider">
	      	<hr />
    		</div>	
  		</div>
    </div>
<?php
endwhile; //resetting the page loop
wp_reset_query(); //resetting the page query
?>

<?php get_footer(); ?>