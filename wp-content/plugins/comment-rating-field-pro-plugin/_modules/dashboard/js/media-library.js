/**
 * Choose Image from Media Library
 */
( function( $ ){

	// Open Media Library
	$( '#wpbody' ).on( 'click', '.insert-media-plugin', function( e ) {

		// Prevent default action
		e.preventDefault();

		// Get some attributes from the button we clicked
		// This tells us where to store some values later on
		var input_id = $( this ).data( 'input' ), // Should be an input field with this ID
			output_id = $( this ).data( 'output' ); // Should be an output field with this ID

		// Setup new wp.media instance, if it's not already set by one of our plugins
		var plugin_media_manager = wp.media.frames.plugin_media_manager = wp.media( {
			frame:    'post',
			multiple: false
		} );
		
		/**
		* Insert Media
		*/
		plugin_media_manager.on( 'insert', function( selection ) {
			// Iterate through the selection (in this case, one item)
			selection.map( function( attachment ) {
				// Get some attachment attributes from the model
				var attachment_id = attachment.get( 'id' ),
					attachment_url = attachment.get( 'url' );

				// Insert the attachment URL
				$( 'input#' + input_id ).val( attachment_url );
				$( 'img#' + output_id ).attr( 'src', attachment_url );
				
			} );
		} );

		// Open the Media View
		plugin_media_manager.open();

	} );

	// Remove a chosen image
	$( '#wpbody' ).on( 'click', '.delete-media-plugin', function( e ) {
		// Prevent default action
		e.preventDefault();

		// Get some attributes from the button we clicked
		// This tells us where to store some values later on
		var input_id = $( this ).data( 'input' ), // Should be an input field with this ID
			output_id = $( this ).data( 'output' ); // Should be an output field with this ID

		// Remove image
		$( 'input#' + input_id ).val( '' );
		$( 'img#' + output_id ).attr( 'src', '' );
	} );

} ( jQuery ) );