<?php
/**
 * The template for displaying all single book review.
 *
 * @package pathways
 */

get_header(); ?>

<div class="container">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php echo "single-book_review.php"?>

		<?php
		//while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', get_post_format() );
					
			$fields = get_fields();

			if( $fields )
			{
				foreach( $fields as $field_name => $value )
				{
					if( $value ){
						echo '<div>';
							echo '<h3>' . get_field_object($field_name)['label']  . ':' . '</h3>'. $value;
						echo '</div>';
					}
				}
			}

			the_post_navigation();

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		//endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .container -->
<?php
get_sidebar();
get_footer();
