<?php echo 'content-revew.php' ?>
<?php
	$args = array(
		'post_type' => 'book',
		'post_status' => 'publish'
		);
	// The Query
	$the_query = new WP_Query( $args );

?>

<?php if ( $the_query->have_posts() ) : ?>

	<!-- pagination here -->

	<!-- the loop -->
	<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
		<h2><?php the_title(); ?></h2>
		<?php
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
		?>

	<?php endwhile; ?>
	<!-- end of the loop -->

	<!-- pagination here -->

	<?php wp_reset_postdata(); ?>

<?php else : ?>
	<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; ?>


