( function( $ ) {
	var WidgetoewSkillbarHandler = function( $scope, $ ) {
		$scope.find( '.oew-skillbar' ).each( function() {
			$( this ).appear( function() {
				$( this ).find( '.oew-skillbar-bar' ).animate( {
					width: $( this ).attr( 'data-percent' )
				}, 800 );
			} );
		}, {
			accX : 0,
			accY : 0
		} );
	};
	
	// Make sure we run this code under Elementor
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/oew-skillbar.default', WidgetoewSkillbarHandler );
	} );
} )( jQuery );