/**
* Tags
*/
jQuery( document ).ready( function( $ ) {

	var wpcube_tags = function() {
		$( 'select.wpcube-tags' ).each( function() {
			$( this ).unbind( 'change.wpcube-tags' ).on( 'change.wpcube-tags', function( e ) {

				// Insert tag into required input or textarea
				var tag 	= $( this ).val(),
					ele 	= $( this ).data( 'element' ),
					val 	= $( ele ).val(),
					pos 	= $( ele )[0].selectionStart;

				// Pad tag if not at start
				if ( pos > 0 ) {
					tag = ' ' + tag;
				}

				// Insert tag
				$( ele ).val( val.substring( 0, pos ) + tag + val.substring( pos ) );

			} );
		} );
	}
	
	wpcube_tags();

} );