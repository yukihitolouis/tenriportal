<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
		<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
		<button class="btn btn-primary" type="submit" id="searchsubmit" value="Search">Search</button>
</form>

<?php echo do_shortcode("[book_search_form]"); ?>
