( function( $ ) {
	var WidgetoewBlogCarouselHandler = function( $scope, $ ) {
	    if ( $( 'body' ).hasClass( 'no-carousel' ) ) {
			return;
		}

		var $carousel = $scope.find( '.oew-carousel' ).eq(0);

		if ( $carousel.length > 0 ) {

			var $settings = $carousel.data( 'settings' );

			// If RTL
			if ( $( 'body' ).hasClass( 'rtl' ) ) {
				var rtl = true;
			} else {
				var rtl = false;
			}

			$carousel.slick( {
				infinite: true,
				slidesToShow: $settings['items'],
				slidesToScroll: 1,
				arrows: $settings['arrows'],
				prevArrow: '<button type="button" class="slick-prev"><span class="fa fa-angle-left"></span></button>',
				nextArrow: '<button type="button" class="slick-next"><span class="fa fa-angle-right"></span></button>',
				speed: 500,
				rtl: rtl,
				responsive: [
					{
						breakpoint: 960,
						settings: {
							slidesToShow: $settings['items'],
						}
					},
					{
						breakpoint: 768,
						settings: {
							slidesToShow: $settings['tablet'],
						}
					},
					{
						breakpoint: 480,
						settings: {
							slidesToShow: $settings['mobile'],
						}
					}
				]
			} );

		}
	};
	
	// Make sure we run this code under Elementor
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/oew-blog-carousel.default', WidgetoewBlogCarouselHandler );
	} );
} )( jQuery );