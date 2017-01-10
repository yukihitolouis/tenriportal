/**
 * Tabbed UI
 */

var active_tab 			= '',
	active_child_tab 	= '';	

jQuery( document ).ready( function( $ ) {

	/**
	* Top level tabbed interface. If defined in the view:
	* - tabs are set to display, as JS is enabled
	* - the selected tab's panel is displayed, with all others hidden
	* - clicking a tab will switch which panel is displayed
	*/
	if ( $( 'h2.nav-tab-wrapper.needs-js' ).length > 0 ) {
		// Show tabbed bar
		$( 'h2.nav-tab-wrapper.needs-js' ).fadeIn( 'fast', function() {
			$( this ).removeClass( 'needs-js' );
		} );
		
		// Hide all panels
		$( 'div.panel' ).hide();

		// Get the active tab, so we know which panel to display
		active_tab = window.location.hash;
		if ( active_tab.length == 0 ) {
			// Get active tab from the tabbed menu
			active_tab = $( 'h2.nav-tab-wrapper a.nav-tab-active' ).attr( 'href' );
		} else {
			// Get active tab from the window location hash
			$( 'h2.nav-tab-wrapper a.nav-tab-active' ).removeClass( 'nav-tab-active' );
			$( 'h2.nav-tab-wrapper a[href="' + active_tab + '"]' ).addClass( 'nav-tab-active' );
		}

		// Show the active tab's panel now
		$( active_tab + '-panel' ).show();
		
		// Change active panel on tab click
		$( 'h2.nav-tab-wrapper' ).on( 'click', 'a', function( e ) {

			e.preventDefault();
			
			// Deactivate all tabs, hide all panels
			$( 'h2.nav-tab-wrapper a' ).removeClass( 'nav-tab-active' );
			$( 'div.panel' ).hide();
			
			// Set clicked tab to active
			$( this ).addClass( 'nav-tab-active' );
			active_tab = $( this ).attr( 'href' );

			// Show the active tab's panel now
			$( active_tab + '-panel' ).show();

			// Update the form URL, so when the user submits the form, they see the same tab again
			

			// Update the URL hash
			if ( history.pushState ) {
    			history.pushState( null, null, $( this ).attr( 'href' ) );
			} else {
    			location.hash = $( this ).attr( 'href' );
			}
		} );
	}

} );