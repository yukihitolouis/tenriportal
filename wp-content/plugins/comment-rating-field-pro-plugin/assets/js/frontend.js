jQuery( document ).ready( function( $ ) {

	// Determine whether Jetpack Comments are enabled
	var crfp_jetpack_comments = ( ( $( 'iframe#jetpack_remote_comment' ).length > 0 ) ? true : false );

	/**
	* Rating Plugin: Init
	*/
	$( 'p.crfp-field' ).each( function() {
		$( 'input.star', $( this ) ).rating( {
			cancel: $( this ).parent().data( 'cancel-text' ),
			cancelValue: 0,
			split: ( ( crfp.enable_half_ratings == 1 ) ? 2 : 1 ),
			callback: function( rating ) {
				var parentElement = $( this ).closest( '.crfp-field' ),
					name = $( 'input[type=hidden]', $( parentElement ) ).attr( 'name' );
				
				// Set hidden field value = rating
				$( 'input[type=hidden]', $( parentElement ) ).val( rating );

				/**
				* If Jetpack Comments are enabled, store the value in a cookie,
				* which the main plugin can read once the comment is posted to 
				* our WordPress Installation.
				* Because Jetpack uses an iframe, we can't POST this data the traditional way
				*/
				if ( crfp_jetpack_comments ) {
					// Replace square brackets on field name
					var field_id = name.replace( 'crfp-rating[', '' ).replace( ']', '' );

					// Send AJAX request to store the rating temporarily
					$.ajax( {
					    type: 'POST',
					    url: crfp.ajax_url,
					   	data: {
					   		action: 	'comment_rating_field_pro_save_rating',
					   		nonce: 		crfp.nonce,
					   		post_id: 	crfp.post_id,
					   		rating:     rating,
					   		field_id:   field_id
					    },
					    success: function( response ) {
					    },
					    error: function( xhr, textStatus, e ) {
					    }
					} );
				}
			}
		} );
	} );
	
	/**
	* Cancel: Reset rating to zeruo
	*/
	$( 'div.rating-cancel a' ).bind( 'click', function(e) { 
		var parentElement = $( this ).closest( '.crfp-field' );
		$( 'input[type=hidden]', $( parentElement ) ).val( '0' );	
	} );
	
	/**
	* Submit: Check rating supplied, if required
	*/			
	$( 'form#commentform' ).bind( 'submit', function(e) {
		$( '.crfp-field' ).each(function() {
			// If field hidden, this is a reply that has ratings disabled, so don't require it
			if ( $(this).css( 'display' ) != 'none' ) {			
				var field = $( this ),
					required = $( field ).data('required'),
					required_text = $( field ).data('required-text');

				if ( required == '1' ) {
					if ( $( 'input[type=hidden]', $( field ) ).val() == '' || $( 'input[type=hidden]', $( field ) ).val() == 0 ) {
						alert( required_text );
						e.preventDefault();
						return false;
					}	
				}
			}
		} );
	} );
	
	/**
	* JS enabled on Comments
	*/
	if ( typeof crfp !== 'undefined' && typeof addComment !== 'undefined' ) {
		// Check if replies are disabled
		if ( crfp.disable_replies == '1' ) {
			// Hide CRFP rating fields when Reply button clicked
			$( 'a.comment-reply-link' ).on( 'click', function(e) {
				$( 'p.crfp-field' ).hide();
			} );	
			
			// Show CRFP rating fields when Cancel button clicked
			$( 'a#cancel-comment-reply-link' ).on( 'click', function(e) {
				$( 'p.crfp-field' ).show();
			} );
		}
	}

	/**
	* Jetpack Comments
	* - Move rating fields, as we can only output them after the Jetpack comment iframe
	*/
	if ( crfp_jetpack_comments ) {
		// Move fields
		$( 'p.crfp-field' ).each( function() {
			$( this ).prependTo( '#commentform' );
		} );
	}
});