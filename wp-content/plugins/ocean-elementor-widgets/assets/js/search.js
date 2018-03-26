( function( $ ) {
	var WidgetoewSearchHandler = function( $scope, $ ) {

		var $search = $scope;

		function oewAjaxSearch( e ) {

			var $ajaxurl 		= $search.find( '.oew-search-wrap' ).data( 'ajaxurl' ),
				$searchResults 	= $search.find( '.oew-search-results' ),
				$loadingSpinner = $search.find( '.oew-search-wrap .oew-ajax-loading' );

			$.ajax( {
				type: 'post',
				url	: $ajaxurl,
				data: {
				    action: 'oew_ajax_search',
				    search: e
			    },
				beforeSend: function() {
					$searchResults.slideUp( 200 );
					setTimeout( function() {
						$loadingSpinner.fadeIn( 50 );
					}, 150 );
				},
				success: function( result ) {
					if ( result === 0 || result == '0' ) {
						result = '';
					} else {
						$searchResults.html( result );
					}
				},
				complete: function() {
					$loadingSpinner.fadeOut( 200 );
					setTimeout( function() {
						$searchResults.slideDown( 400 ).addClass( 'filled' );
					}, 200 );
				}
			} );

		}

	    $search.find( '.oew-ajax-search input.field' ).on( 'keyup', function() {

			var $searchValue 		= $( this ).val(),
				$lastSearchValue 	= '',
				$searchTimer 		= null;

			clearTimeout( $searchTimer );

			if ( $lastSearchValue != $.trim( $searchValue ) && $searchValue.length >= 3 ) {
				$searchTimer = setTimeout( function() {
					oewAjaxSearch( $searchValue );
				}, 400);
			}

		} );

		$( document ).on( 'click', function() {
			$( '.oew-search-results.filled' ).slideUp( 200 );
		} ).on( 'click', '.oew-ajax-search, .oew-search-results', function( e ) {
		    e.stopPropagation();
		} ).on( 'click', '.oew-ajax-search', function() {
		    $( this ).parent().find( '.oew-search-results.filled' ).slideDown( 400 );
		} );

	};
	
	// Make sure we run this code under Elementor
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/oew-search.default', WidgetoewSearchHandler );
	} );
} )( jQuery );