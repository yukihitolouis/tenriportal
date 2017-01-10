<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package pathways
 */

get_header();

?>

<?php
	

$s = $_GET['s'];
$post_tag = @$_GET['post_tag'];
$authors = @$_GET['authors'];
$commments_flag = @$_GET['comments'];
//tax_query用
if($post_tag){
    $taxquerysp[] = array(
            'taxonomy'=>'genre',
            'terms'=> $post_tag,
            'include_children'=>false,
            'field'=>'slug',
            'operator'=>'AND'
            );
}

$taxquerysp['relation']= 'OR';

//custom field用
if($authors){
	foreach($authors as $author){
		$metaquerysp[] = array(
			'key' => 'book_author',
			'value' => $author,
		);
	}
}

$metaquerysp['relation']= 'OR';

?>


	


    	
	  	
	  	
	  	<div class="row">
		  	
		  	<div class="col-lg-9 col-sm-8">
			  	
			  	<?php if(!$commments_flag){?>
			  	<?php if(have_posts()): while(have_posts()): the_post(); ?>
			  	
			  	
			  	
			  	<article>
				  	<a href="<?php the_permalink(); ?>">
				  		<div class="row">
					  		<div class="col-lg-3 col-xs-4">
						      	<div class="thumb img-container">
							      	<?php if(has_post_thumbnail()): the_post_thumbnail('full'); else: ?>
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
									      	<?php if(get_field('description')){the_field('description');}else{ echo "Book detail is currently Not Available."; } ?>
								      	</p>
								      	
								      	<div class="meta-info stars mobile-show">
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
							  	
						      	
					      	</div>
					  		
				  		</div>
				  		
				  		<div class="col-lg-12 divider">
					      	<hr />
				      	</div>
				  	</a>
			  	</article>
			  	<?php endwhile; endif; ?>
			  	<?php }else{ 
				  	?>
				  	<div class="divider col-lg-12">
				  	</div>
				  	<?php
				  		foreach($comments as $comment):
				  		$post = get_post($comment->comment_post_ID);
				  ?>		
				  			<a href="<?php the_permalink();?>">
					  			<div class="row">
				  				  		<div class="col-lg-4">
									      	<div class="thumb img-container">
										      	<?php $comment_thumb = get_the_post_thumbnail($comment->comment_post_ID);?>
										      	<?php if($comment_thumb): echo $comment_thumb; else: ?>
										      	<img src="<?php bloginfo('template_directory'); ?>/img/default_book.png" alt />
										      	<?php endif; ?>
									      	</div>		      	
								      	</div>
								      	
								      	<div class="col-lg-8">
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
										      	<p class="reviewer">Commented by <?php echo $comment->comment_author; ?></p>
										      	<p class="review_date">Commented on <?php echo $comment->comment_date; ?></p>
										      	<h5 class="review_title"></h5>
										      	<p class="review_contents">
											      	<?php echo $comment->comment_content; ?>
										      	</p>
									      	</div>
									      	
								      	</div>
								      	
					  			</div>
				  			</a>	
				  			<div class="col-lg-12 divider">
						      	<hr />
					      	</div>
							      	
							  	<?php endforeach; ?>
							  	<?php } ?>
	      	</div>
	      	
	      	
	      	
	      	
		  	
		  	
		  		
	  	</div>
	  	
	</div> <!-- /container -->  
<?Php get_footer(); ?>