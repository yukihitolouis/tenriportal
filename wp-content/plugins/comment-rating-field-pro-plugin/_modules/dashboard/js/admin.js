jQuery( document ).ready( function( $ ) {

	// Initialize conditionals
	$( 'input,select' ).conditional();

	// Initialize Clipboard
    if ( typeof Clipboard !== 'undefined' ) {
    	console.log( 'Firing' );
        var wpcube_clipboard = new Clipboard( '.clipboard-js' );
        $( document ).on( 'click', '.clipboard-js', function( e ) {
            e.preventDefault();
        } );
    }

} );