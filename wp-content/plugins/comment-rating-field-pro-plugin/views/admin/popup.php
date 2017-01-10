<?php
// Bootstrap WordPress
$wp_include = "../wp-load.php";
$i = 0;
while( ! file_exists( $wp_include ) && $i++ < 10 ) { 
    $wp_include = "../$wp_include";
}
require( $wp_include );
?>
<!DOCTYPE html>
<head>
	<title><?php _e( 'Insert Average Rating', 'comment-rating-field-pro-plugin' ); ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo admin_url(); ?>load-styles.php?c=1&amp;load=buttons,common,forms" type="text/css" media="all" />
    <link rel="stylesheet" href="../../_modules/dashboard/css/admin.css" type="text/css" media="all" />
</head>
<body class="wp-core-ui">

	<form class="crfp-popup">

        <!-- Enabled -->
        <div class="option">
            <div class="left">
                <strong><?php _e( 'Enabled', 'comment-rating-field-pro-plugin' ); ?></strong>
            </div>
            <div class="right">
                <select name="enabled" size="1">
                    <option value="1">
                        <?php _e( 'Display when Ratings Exist', 'comment-rating-field-pro-plugin' ); ?>
                    </option>
                    <option value="2">
                        <?php _e( 'Always Display', 'comment-rating-field-pro-plugin' ); ?>
                    </option>
                </select>
            </div>
        </div>

        <!-- Style -->
        <div class="option">
            <div class="left">
                <strong><?php _e( 'Style', 'comment-rating-field-pro-plugin' ); ?></strong>
            </div>
            <div class="right">
                <select name="style" size="1">
                    <option value="">
                        <?php _e( 'Filled Color Only', 'comment-rating-field-pro-plugin' ); ?>
                    </option>
                    <option value="grey">
                        <?php _e( 'Filled and Empty Colors', 'comment-rating-field-pro-plugin' ); ?>
                    </option>
                </select>
            </div>
        </div>

        <!-- Average -->
        <div class="option">
            <div class="left">
                <strong><?php _e( 'Show Average', 'comment-rating-field-pro-plugin' ); ?></strong>
            </div>
            <div class="right">
                <select name="average" size="1">
                    <option value="0">
                        <?php _e( 'No', 'comment-rating-field-pro-plugin' ); ?>
                    </option>
                    <option value="1">
                        <?php _e( 'Yes, using Stars', 'comment-rating-field-pro-plugin' ); ?>
                    </option>
                    <option value="2">
                        <?php _e( 'Yes, using Bars (Amazon Style)', 'comment-rating-field-pro-plugin' ); ?>
                    </option>
                </select>
                <input type="text" name="averageLabel" value="<?php _e( 'Average:', 'comment-rating-field-pro-plugin' ); ?>" placeholder="<?php _e( 'Average Rating Label', 'comment-rating-field-pro-plugin' ); ?>" />
            </div>
        </div>

        <!-- Total Ratings -->
        <div class="option">
            <div class="left">
                <strong><?php _e( 'Total Ratings', 'comment-rating-field-pro-plugin' ); ?></strong>
            </div>
            <div class="right">
                <select name="totalRatings" size="1">
                    <option value="">
                        <?php _e( 'Do not display', 'comment-rating-field-pro-plugin' ); ?>
                    </option>
                    <option value="1">
                        <?php _e( 'Display', 'comment-rating-field-pro-plugin' ); ?>
                    </option>
                </select>

                <input type="text" name="totalRatingsBefore" value="" placeholder="<?php _e( 'Before Text', 'comment-rating-field-pro-plugin' ); ?>" />
                <input type="text" name="totalRatingsAfter" value="" placeholder="<?php _e( 'After Text', 'comment-rating-field-pro-plugin' ); ?>" />
            </div>
        </div>
     
        <!-- Show Breakdown -->
        <div class="option">
            <div class="left">
                <strong><?php _e( 'Show Breakdown', 'comment-rating-field-pro-plugin' ); ?></strong>
            </div>
            <div class="right">
                <select name="showBreakdown" size="1">
                    <option value="0">
                        <?php _e( 'No', 'comment-rating-field-pro-plugin' ); ?>
                    </option>
                    <option value="1">
                        <?php _e( 'Yes, using Stars', 'comment-rating-field-pro-plugin' ); ?>
                    </option>
                </select>
            </div>
        </div>
  
        <!-- Show Rating Number -->
        <div class="option">
            <div class="left">
                <strong><?php _e( 'Show Rating Number', 'comment-rating-field-pro-plugin' ); ?></strong>
            </div>
            <div class="right">
                <select name="showRatingNumber" size="1">
                    <option value="0">
                        <?php _e( 'No', 'comment-rating-field-pro-plugin' ); ?>
                    </option>
                    <option value="1">
                        <?php _e( 'Yes, as Number', 'comment-rating-field-pro-plugin' ); ?>
                    </option>
                    <option value="2">
                        <?php _e( 'Yes, as Percentage', 'comment-rating-field-pro-plugin' ); ?>
                    </option>
                </select>
            </div>
        </div>
                            
        <!-- Average -->
        <div class="option">
            <div class="left">
                <strong><?php _e( 'Filter Comments', 'comment-rating-field-pro-plugin' ); ?></strong>
            </div>
            <div class="right">
                <select name="filterComments" size="1">
                    <option value="0">
                        <?php _e( 'No', 'comment-rating-field-pro-plugin' ); ?>
                    </option>
                    <option value="1">
                        <?php _e( 'Yes', 'comment-rating-field-pro-plugin' ); ?>
                    </option>
                </select>
            </div>
        </div>
                               
        <!-- Link to Comments -->
        <div class="option">
            <div class="left">
                <strong><?php _e( 'Link to Comments', 'comment-rating-field-pro-plugin' ); ?></strong>
            </div>
            <div class="right">
                <select name="linkToComments" size="1">
                    <option value="0">
                        <?php _e( 'No', 'comment-rating-field-pro-plugin' ); ?>
                    </option>
                    <option value="1">
                        <?php _e( 'Yes', 'comment-rating-field-pro-plugin' ); ?>
                    </option>
                </select>
            </div>
        </div>
        
        <div class="option">
        	<div class="left">
        		<strong><?php _e( 'Post ID', 'comment-rating-field-pro-plugin' ); ?></strong>
            </div>
            <div class="right">
            	<input type="number" name="postID" min="1" max="9999999" step="1" /> 
            
                <p class="description">
                	<?php _e( 'Only required if you want to show the average ratings for a different Post/Page than this one', 'comment-rating-field-pro-plugin' ); ?>
                </p>
            </div>
        </div>

		<div class="option">
			<input type="submit" name="submit" value="<?php _e( 'Insert', 'comment-rating-field-pro-plugin' ); ?>" class="button button-primary" />
		</div>
	</form>
	
	<!-- Javascript -->
	<script type="text/javascript" src="<?php bloginfo( 'url' ); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script type="text/javascript" src="<?php echo admin_url(); ?>load-scripts.php?c=1&amp;load=jquery-core"></script>
    <script type="text/javascript">
        ( function( $ ) {

            $( 'form' ).on( 'submit', function( e ) {

                // Prevent default action
                e.preventDefault();

                // Build shortcode based on input
                var shortcode = '[crfp enabled="'+$('select[name=enabled]').val()+'"';
                shortcode += ' displayStyle="'+$('select[name=style]').val()+'"';
                shortcode += ' displayAverage="'+$('select[name=average]').val()+'"';
                shortcode += ' averageRatingText="'+$('input[name=averageLabel]').val()+'"';
                shortcode += ' displayTotalRatings="'+$('select[name=totalRatings]').val()+'"';
                shortcode += ' totalRatingsBefore="'+$('input[name=totalRatingsBefore]').val()+'"';
                shortcode += ' totalRatingsAfter="'+$('input[name=totalRatingsAfter]').val()+'"';
                shortcode += ' displayBreakdown="'+$('select[name=showBreakdown]').val()+'"';
                shortcode += ' displayRatingNumber="'+$('select[name=showRatingNumber]').val()+'"';
                shortcode += ' filterComments="'+$('select[name=filterComments]').val()+'"';
                shortcode += ' displayLink="'+$('select[name=linkToComments]').val()+'"';
                shortcode += ' id="'+$('input[name=postID]').val()+'"';
                shortcode += ']';

                // Insert into Editor
                tinyMCEPopup.execCommand( 'mceReplaceContent', false, shortcode );
                tinyMCEPopup.close();

            } );

        } ) ( jQuery );
    </script>
</body>
</html>