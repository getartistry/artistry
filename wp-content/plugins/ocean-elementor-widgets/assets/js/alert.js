( function( $ ) {
	var WidgetoewAlertMessageHandler = function( $scope, $ ) {
		$scope.find( '.oew-alert-close-btn' ).click( function() {

	        $( this ).parents( 'div[class^="oew-alert"]' ).fadeOut( 500 );

	    } );
	};
	
	// Make sure we run this code under Elementor
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/oew-alert.default', WidgetoewAlertMessageHandler );
	} );
} )( jQuery );