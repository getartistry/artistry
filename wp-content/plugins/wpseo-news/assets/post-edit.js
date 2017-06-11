/* jshint unused:false */
jQuery ( document ).ready( function( $ ) {
	'use strict';

	var keywordsField = $( '#yoast_wpseo_newssitemap-keywords' );
	var keywordsFieldParent = keywordsField.parent();
	var keywordsFieldParentFirstDiv = keywordsFieldParent.find( 'div:first' );

	keywordsField.on( 'keyup', function() {
		if ( $( this ).val().split( ',' ).filter( Boolean ).length > 10 ) {
			// The 'form-invalid' CSS class comes from WordPress and is in `/wp-admin/css/forms.css`.
			keywordsFieldParent.addClass( 'form-invalid' );
			// The 'error-message' CSS class comes from WordPress and is in `/wp-admin/css/common.css`.
			keywordsFieldParentFirstDiv.addClass( 'error-message' );
		} else {
			keywordsFieldParent.removeClass( 'form-invalid' );
			keywordsFieldParentFirstDiv.removeClass( 'error-message' );
		}
	});
});
