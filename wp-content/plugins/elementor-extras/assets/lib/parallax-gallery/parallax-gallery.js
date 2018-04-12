// -- parallaxGallery
// @license parallaxGallery v1.0.0 | MIT | Namogo 2017 | https://www.namogo.com
// --------------------------------
;(
    function( $, window, document, undefined ) {

		$.parallaxGallery = function(element, options) {

			var defaults = {
				speed 			: 0.15,
				speedTablet		: 0.15,
				speedMobile		: 0.15,
				columns			: 3,
				columnsTablet	: 2,
				columnsMobile	: 1,
				scope 			: $(window),
				transformItem 	: null,
				disableOn		: false,
				breakpoints		: {
					'mobile'	: 768,
					'tablet' 	: 1024,
				}
			};

			var plugin = this;

			plugin.opts = {};

			var $window			= null,
				gallery			= element,
				$gallery		= $(element),
				$document		= $(document),

				scrolled		= null,
				speed 			= 0.15,
				
				winHeight 		= $(window).height(),
				docHeight 		= $document.height(),
				$items			= $gallery.find('> *');


			plugin.init = function() {
				plugin.opts = $.extend({}, defaults, options);

				plugin._construct();
			};

			plugin._construct = function() {

				$window		= plugin.opts.scope;
				scrolled 	= $window.scrollTop();

				plugin.setup();
				plugin.update();
				plugin.events();

			};

			plugin.setup = function() {

				if ( speed <= 0 ) speed = 0.001;

				if ( $window.width() < plugin.opts.breakpoints['tablet'] ) {
					if ( $window.width() < plugin.opts.breakpoints['mobile'] ) {
						plugin.setupColumns( plugin.opts.columnsMobile );
						speed = plugin.opts.speedMobile;
					} else {
						plugin.setupColumns( plugin.opts.columnsTablet );
						speed = plugin.opts.speedTablet;
					}
				} else {
					plugin.setupColumns( plugin.opts.columns );
					speed = plugin.opts.speed;
				}

				if ( speed < 0 ) speed = 0.001;
				
			};

			plugin.setupColumns = function( columns ) {

				$items.each( function( index ) {

					var $item 			= ( plugin.opts.transformItem ) ? $(this).find( plugin.opts.transformItem ) : $(this);

					$item.removeClass('is--3d');

					if ( columns == 1 || columns == 3 ) {

						if ( $(this).is( ':nth-child(even)' ) ) { $item.addClass('is--3d'); }

					} else if ( columns == 2 ) {

						if (
							$(this).is( ':nth-child(4n+2)' ) ||
							$(this).is( ':nth-child(4n+3)' )
						) { $item.addClass('is--3d'); }
						
					} else if( columns == 4 ) {

						if (
							$(this).is( ':nth-child(8n+2)' ) ||
							$(this).is( ':nth-child(8n+4)' ) ||
							$(this).is( ':nth-child(8n+5)' ) ||
							$(this).is( ':nth-child(8n+7)' )
						) { $item.addClass('is--3d'); }
						
					} else if( columns == 5 ) {

						if (
							$(this).is( ':nth-child(10n+2)' ) ||
							$(this).is( ':nth-child(10n+4)' ) ||
							$(this).is( ':nth-child(10n+6)' ) ||
							$(this).is( ':nth-child(10n+8)' ) ||
							$(this).is( ':nth-child(10n+10)' )
						) { $item.addClass('is--3d'); }
						
					} else if ( columns == 6 ) {

						if (
							$(this).is( ':nth-child(12n+2)' ) ||
							$(this).is( ':nth-child(12n+4)' ) ||
							$(this).is( ':nth-child(12n+6)' ) ||
							$(this).is( ':nth-child(12n+7)' ) ||
							$(this).is( ':nth-child(12n+9)' ) ||
							$(this).is( ':nth-child(12n+11)' )
						) { $item.addClass('is--3d'); }
						
					}

				});
			};

			plugin.events = function() {

				$window.on( 'scroll', plugin.update );

				$window.on( 'resize', function() {

					winHeight = $(window).height();

					plugin.setup();
					plugin.update();

				});

			};

			plugin.update = function() {

				if ( plugin.opts.disableOn && $window.width() < plugin.opts.breakpoints[plugin.opts.disableOn] ) {
					plugin.clearTransforms();
					
					return;
				}

				var scrolled 		= $window.scrollTop();

				$items.each( function( index ) {

					var $item 			= ( plugin.opts.transformItem ) ? $(this).find( plugin.opts.transformItem ) : $(this);

					if( ! $item.length ) // Ignore undefined
						return;

					var itemIndex		= index,
						
						itemHeight		= $item.outerHeight(),
						itemWidth		= $item.outerWidth(),

						_condition		= $item.is('.is--3d'),
						
						toTop 			= $item.offset().top,
						toBottom 		= toTop + itemHeight,
						toTopFromMiddle = toTop + itemHeight / 2,

						_speed	= ( _condition ) ? 0.3 * speed / 2 : 0.3 * speed,

						pos = ((scrolled - toTopFromMiddle) + winHeight / 2 ) * _speed;

					TweenMax.set( $item, { y: pos } );

				});
			};

			plugin.clearTransforms = function() {
				var $transformedItems = ( plugin.opts.transformItem ) ? $gallery.find( plugin.opts.transformItem ) : $items;

				TweenMax.set( $transformedItems, { clearProps: "all" } );
			};

			plugin.destroy = function() {

				plugin.clearTransforms();
				$window.off( 'scroll', plugin.update );
				$gallery.removeData( 'parallaxGallery' );

			};

			plugin.init();

		};

		$.fn.parallaxGallery = function(options) {

			return this.each(function() {

				$.fn.parallaxGallery.destroy = function() {
					if( 'undefined' !== typeof( plugin ) ) {
						$(this).data( 'parallaxGallery' ).destroy();
						$(this).removeData( 'parallaxGallery' );
					}
				}

				if (undefined === $(this).data('parallaxGallery')) {
					var plugin = new $.parallaxGallery(this, options);
					$(this).data('parallaxGallery', plugin);
				}
			});

		};

	}

)( jQuery, window, document );