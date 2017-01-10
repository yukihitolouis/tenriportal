<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package tenriportal
 */
 ?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/bootstrap.min.css">
        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
        </style>
        <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/slick/slick.css">
        
        
		<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/main.css">

        <script src="<?php bloginfo('template_directory'); ?>/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); 
        remove_action( 'wp_head', 'wp_print_styles',8);
        remove_action( 'wp_head', 'wp_print_footer_scripts',20);
		wp_head(); ?>
    </head>
    <body <?php body_class(); ?> >
	    <!----
		login details    
		    
		-->
	    <?php
		if(is_user_logged_in()){
			$loginSwitch = 1;
			$current_user = wp_get_current_user();

		}else{
			$loginSwitch = 0;
		}
		?>
	    
	    <!--------------------
		
		
		LOGIN MODAL Comment
		
	---------------------->	
	<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
	  	<div class="modal-dialog" role="document">
	    	<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<?php if(!$loginSwitch){ ?>
					<h4 class="modal-title" id="myModalLabel2">LOGIN</h4>
					<?php }else{ ?>
					<h4 class="modal-title" id="myModalLabel2">HELLO <?php echo $current_user->user_firstname; ?>!</h4>
					<?php } ?>
				</div>
				<div class="modal-body">
					<?php if($loginSwitch){ ?>
						<div class="modal-user-thumb">
							<div class="comment-author vcard user-thumb img-container">
					        	<?php echo get_avatar($current_user->ID); ?>
					    	</div>
						</div>
						<div class="modal-user-info">
							<p class="username">
					        	<?php echo $current_user->user_firstname." ".$current_user->user_lastname; ?>
							</p>
							<p class="email">
								<?php echo $current_user->user_email; ?>
							</p>
				    	</div>
						
						<div class="login-buttons">
							<a href="<?php echo wp_logout_url(home_url()); ?>">
								<button type="button" class="btn btn-primary btn-save btn-logout" ><i class="fa fa-lg fa-power-off"></i>&nbsp;LOGOUT</button>
							</a>
							<button type="button" class="btn btn-default btn-cancel btn-login" data-dismiss="modal"><i class="fa fa-lg fa-sign-out"></i>&nbsp;Close</button>
						</div>
						
					<?php }else{ ?>
					
						<?php wp_login_form(); ?>
												
					<?php } ?>
					
				</div>
				
		    </div>
		</div>
	</div> 	
	<!--------------------
		
		
		/END LOGIN MODAL Comment
		
	---------------------->	      
	    
	    
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          
          <button type="button" class="navbar-toggle navbar-toggle-search" data-toggle="collapse" data-target=".searchbox2">
          	<i class="fa fa-search fa-lg"></i>
          </button>
          
          <a class="navbar-brand fletter" href="<?PHP echo home_url(); ?>"><?php bloginfo('title'); ?></a>
        </div>
        
				<div class="center-block searchbox">
					<?php get_search_form(); ?>
				</div>        
        <div class="center-block searchbox2 mobile-show" style="">
			<?php get_search_form(); ?>
        </div>
        
        <div class="navbar-collapse collapse pull-right">
          <ul class="nav navbar-nav">
	          <li><?php if(!is_user_logged_in()){ ?>
		          <a href="#" data-target="#loginModal" data-toggle="modal">
			          <i class="fa fa-lg fa-lock"></i>&nbsp;LOGIN
		          </a>
		          <?php }else{ ?>
		          <a href="#" data-target="#loginModal" data-toggle="modal">
			          <i class="fa fa-lg fa-user"></i>&nbsp;<?php echo $current_user->user_firstname; ?>
		          </a>
		          <?php } ?>
	          </li>
            <li class="active"><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
            <li><a href="<?php echo get_page_link( get_page_by_title( 'About' )->ID ); ?>">About</a></li>
          </ul>
        </div><!--/.navbar-collapse -->
      </div>
    </div>
	<?php if(!is_home()){ ?>
		<div class="breadcrumb-bar">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="breadcrumbs" typeof="BreadcrumbList" vocab="http://schema.org/">
						    <?php if(function_exists('bcn_display'))
						    {
						        bcn_display();
						    }?>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	<?php } ?>
		
	<?php if(is_search()){ ?>
	
		<div class="searchresultbar">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="searchResultBarContents">
							<?php
								
								$count = $wp_query->found_posts;
								
							?>
						    Found <?php echo $count; ?> books for the search result for <?php the_search_query(); ?>
						    
						    
						</div>
					</div>
				</div>
			</div>
			
		</div>
	
	<?php }?>
	
	<?php if(is_search() || is_archive()){ ?>
		<div class="container archive-listing">
      <!-- Example row of columns -->
      
      <div class="mobile-show">
				<div class="row filter-container">
					<aside>
				
				  	<div class="sidebar-section filter-title">
					  	<h4>FILTER</h4>
					  	<button data-toggle="collapse" data-target=".filter-body"><i class="fa fa-lg fa-plus"></i></button>
				  	</div>
				  	
				  	<div class="filter-body">
					
					  	<div class="sidebar-section">
						  	<h5>AUTHOR</h5>
						  	
						  	<div class="form-group tags">
							  	<form method="get" id="searchform" action="<?php bloginfo('url'); ?>">
									<input type="hidden" name="s" />	
								  	<select class="form-control" name="authors[]">
									  	<option></option>
								  	<?php
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
											
											<option value="<?php echo $author_name; ?>"><?php echo $author_name; ?></option>
											
												<?php endif;
											}
										?>
								  		</select>
								  		<input type="submit" class="searchSubmit green" value="Search" />
							  	</form>
						  	</div><!--"form-group tags"-->
					  	</div><!--"sidebar-section"-->
					  	
					  	
					  	<div class="sidebar-section">
						  	
						  	<h5>GENRES</h5>
						  	
						  	<div class="form-group tags">
							  	<form method="get" id="searchform2" action="<?php bloginfo('url'); ?>">
									<input type="hidden" name="s" />
								  	<select name="post_tag[]" class="form-control">
									  	<option></option>
									  	<?php
										  	
									  		$taxonomy_name = 'genre';
												$taxonomys = get_terms($taxonomy_name);
										
									    	foreach($taxonomys as $taxonomy):
										    	
									        $tax_posts = get_posts(array('post_type' => 'book', 'taxonomy' => $taxonomy_name, 'term' => $taxonomy->slug ) );
										        
									        if($tax_posts):
											?>
										
											<option value="<?php echo $taxonomy->slug; ?>"><?php echo $taxonomy->name; ?></option>
										
											<?php
										      endif;
										    endforeach;	
											?>
									  	</select>
								  	
								  	<input type="submit" class="searchSubmit green" value="Search" />
							  	</form>
						  	</div> <!--"form-group tags"-->
					  	</div><!--"sidebar-section"-->
				  	
				  	</div><!--"filter-body"-->
					
			  	</aside>
				</div><!--"row filter-container"-->
			</div><!--"mobile-show"-->
		</div><!--"container archive-listing"-->
	<?php } ?>
	
	
	<?php if(is_home()){ ?>
	
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
      </div>
    </div>
  <?php } ?>