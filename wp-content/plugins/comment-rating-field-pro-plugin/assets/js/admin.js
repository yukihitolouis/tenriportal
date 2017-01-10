jQuery( document ).ready( function( $ ) {

	/**
	* Post: Delete All Ratings
	*/
	$( 'a.crfp-delete-ratings' ).on( 'click', function( e ) {

		e.preventDefault();

		// Confirm the user really wants to do this
		var result = confirm( crfp.delete_ratings );
		if ( ! result ) {
			return;
		}

		// Send AJAX request to delete comment ratings and post rating
		$.ajax( {
		    type: 'POST',
		    url: crfp.ajax_url,
		   	data: {
		   		action: 	'comment_rating_field_pro_delete_all_ratings',
		   		post_id: 	crfp.post_id,
		   		nonce: 		crfp.nonce,
		    },
		    success: function( response ) {
		    	if ( response == '1' ) {
		    		// Deletion OK
		    		// Hide ratings on screen
		    		// Lazy match as if we hide all CRFP containers, we lose the comment text too
		    		$( "*[class*='crfp-group']" ).hide();
		    		
		    		// Change Reset Ratings meta box
		    		$( '#comment-rating-field-pro-plugin-ratings div.inside div.option:first-child p' ).text( crfp.deleted_ratings );
		    		$( '#comment-rating-field-pro-plugin-ratings div.inside div.option:last-child' ).hide();
		    	}
		    },
		    error: function( xhr, textStatus, e ) {
		    	alert( textStatus );
		    }
		} );
		
	} );
		

	/**
	* Color Pickers
	*/
	$( '.color-picker-control' ).each( function() {
		$( this ).wpColorPicker();
	} );

	/**
	* Rating Field: Add
	*/
	$('button.add-rating-field').on('click', function(e) {
		e.preventDefault();
		$('#sortable').append($('div.field.hidden').html());
		
		// Iterate through all rating fields to set hierarchy spans
		$('#sortable div.option').each(function(count) {
			$('span.hierarchy', $(this)).text((count+1));
		});
	});

	/**
	* Rating Fields: Sort
	*/
	if ( $( '#sortable' ).length > 0) {
		$( '#sortable' ).sortable();
	}

	/**
	* Rating Field: Delete
	*/
	$( document ).on('click', 'a.delete-rating-field', function(e) {
		e.preventDefault();

		var result = confirm( crfp.delete_rating_field );
		if ( result ) {
			$( this ).parent().parent().remove();
		}
	});

} );