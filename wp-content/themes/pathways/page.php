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

  <!-- Main jumbotron for a primary marketing message or call to action -->
  <div class="jumbotron">
    <div class="container">
			<?php get_search_form ();  ?>
    </div>
  </div>

  <div class="container">
<!-- Example row of columns -->
	<div class="row">
		<div class="col-md-6">
		<h2>Heading</h2>
		<p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
		<p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
	</div>
	<div class="col-md-6">
		<h2>Heading</h2>
		<p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
		<p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
	</div>
	</div>
	
    <div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

				<?php
				//while ( have_posts() ) : the_post();

					//get_template_part( 'template-parts/content', get_post_format() );
					//experiment to see if this grabs template-parts/content-review.php
					get_template_part( 'template-parts/content', 'review' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

				//endwhile; // End of the loop.
				?>

			</main><!-- #main -->
		</div><!-- #primary -->

    <hr>

  </div> <!-- /container --> 

<?php
/*get_sidebar();*/
get_footer();
