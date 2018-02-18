( function( $ ) {


	/* Main Sidebar section */

	wp.customize( 'dce_sidebar_title_uppercase', function( value ) {
		value.bind( function( newval ) {
			if ( newval ) {
					$( '#main-content #sidebar h4.widgettitle' ).css( 'text-transform', 'uppercase' );
				} else {
					$( '#main-content #sidebar h4.widgettitle' ).css( 'text-transform', 'none' );
			}
		} );
	} );

	wp.customize( 'dce_sidebar_title_italics', function( value ) {
		value.bind( function( newval ) {
			if ( newval ) {
					$( '#main-content #sidebar h4.widgettitle' ).css( 'font-style', 'italic' );
				} else {
					$( '#main-content #sidebar h4.widgettitle' ).css( 'font-style', 'normal' );
			}
		} );
	} );

	
} )( jQuery );