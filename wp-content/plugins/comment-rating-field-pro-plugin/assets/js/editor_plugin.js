( function() {
	tinymce.PluginManager.add( 'crfp', function( editor, url ) {
		
		// Add Button to Visual Editor Toolbar
		editor.addButton( 'crfp', {
			title: 	'Comment Rating Field Pro - Display Rating',
			icon: 	'icon dashicons-star-filled',
			cmd: 	'crfp'
		} );	

		// Add Command when Button Clicked
		editor.addCommand( 'crfp', function() {
			editor.windowManager.open( {
				title: 			'Insert Average Rating',
				file : 			url + '/../../../views/admin/popup.php',
                width: 			500,
                height: 		450,
                close_previous: 1,
                inline: 		1,
                popup_css: 		url + '../../../assets/css/admin.css',
                resizable: 		0,
                plugin_url: 	url,
            } );
		} );
	} );

} )();