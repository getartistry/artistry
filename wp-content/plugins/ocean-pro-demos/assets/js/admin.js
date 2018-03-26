var $j = jQuery.noConflict();

$j(document).ready(function(e) {

	// Categories navigation
	(function () {
		// Cache selector to all items
		var $items 				= $j( '#opd-demo-wrap .themes' ).find( '.theme-wrap' ),
			fadeoutClass 		= 'opd-is-fadeout',
			fadeinClass 		= 'opd-is-fadein',
			animationDuration 	= 200;

		// Hide all items.
		var fadeOut = function () {
			var dfd = jQuery.Deferred();

			$items
				.addClass( fadeoutClass );

			setTimeout( function() {
				$items
					.removeClass( fadeoutClass )
					.hide();

				dfd.resolve();
			}, animationDuration );

			return dfd.promise();
		};

		var fadeIn = function ( category, dfd ) {
			var filter = category ? '[data-categories*="' + category + '"]' : 'div';

			if ( 'all' === category ) {
				filter = 'div';
			}

			$items
				.filter( filter )
				.show()
				.addClass( 'opd-is-fadein' );

			setTimeout( function() {
				$items
					.removeClass( fadeinClass );

				dfd.resolve();
			}, animationDuration );
		};

		var animate = function ( category ) {
			var dfd = jQuery.Deferred();

			var promise = fadeOut();

			promise.done( function () {
				fadeIn( category, dfd );
			} );

			return dfd;
		};

		$j( '.opd-navigation-link' ).on( 'click', function( event ) {
			event.preventDefault();

			// Remove 'active' class from the previous nav list items.
			$j( this ).parent().siblings().removeClass( 'active' );

			// Add the 'active' class to this nav list item.
			$j( this ).parent().addClass( 'active' );

			var category = this.hash.slice(1);

			// show/hide the right items, based on category selected
			var $container = $j( '#opd-demo-wrap .themes' );
			$container.css( 'min-width', $container.outerHeight() );

			var promise = animate( category );

			promise.done( function () {
				$container.removeAttr( 'style' );
			} );
		} );
	}());

	// Search functionality.
	$j( '.opd-search-input' ).on( 'keyup', function( event ) {
		if ( 0 < $j( this ).val().length ) {
			// Hide all items.
			$j( '#opd-demo-wrap .themes' ).find( '.theme-wrap' ).hide();

			// Show just the ones that have a match on the import name.
			$j( '#opd-demo-wrap .themes' ).find( '.theme-wrap[data-name*="' + $j( this ).val().toLowerCase() + '"]' ).show();
		} else {
			$j( '#opd-demo-wrap .themes' ).find( '.theme-wrap' ).show();
		}
	} );

	// if clicked on import data button
	$j( '.opd-install' ).live( 'click', function(e) {
		
		var $selected_demo 		= $j( this ).data( 'demo-id' ),
			$loading_icon 		= $j( '.preview-' + $selected_demo ),
			$success_icon 		= $j( '.success-' + $selected_demo ),
			$warning_icon 		= $j( '.warning-' + $selected_demo ),
			$disable_preview 	= $j( '.preview-all-' + $selected_demo );

		$loading_icon.show();
		$disable_preview.show();

		var data = {
			action: 'oceanwp_pro_demos_data',
			demo_type: $selected_demo
		};

		$j( '.importer-notice' ).hide();

		$j.post( ajaxurl, data, function( $response ) {
			if( $response && $response.indexOf( 'imported' ) == -1 ) {
				$j( '.importer-notice-1' ).attr( 'style', 'display:block !important' );
				$warning_icon.show();
				setTimeout( function() {
					$warning_icon.hide();
					$disable_preview.hide();
				}, 4000 );
			} else {
				$j( '.importer-notice-2' ).attr( 'style', 'display:block !important' );
				$success_icon.show();
				setTimeout( function() {
					$success_icon.hide();
					$disable_preview.hide();
				}, 4000 );
			}
			$loading_icon.hide();
		} ).fail( function() {
			$j( '.importer-notice-1' ).attr( 'style', 'display:block !important' );
				$warning_icon.show();
				setTimeout( function() {
					$warning_icon.hide();
					$disable_preview.hide();
				}, 4000 );
			$loading_icon.hide();

		} );

		e.preventDefault();

	} );

} );
