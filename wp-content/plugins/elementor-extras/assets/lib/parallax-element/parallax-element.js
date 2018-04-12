// -- parallaxElement
// @license parallaxElement v1.0.0 | MIT | Namogo 2017 | https://www.namogo.com
// --------------------------------
;(
    function( $, window, document, undefined ) {

		$.parallaxElement = function(element, options) {

			var defaults = {
				scope 			: $(window),
				type 			: 'scroll',
				scroll 			: {
					relative 	: 'middle',
					responsive	: false,
				},
				mouse 			: {
					axis 		: 'both',
					relative 	: 'element',
					distance 	: null,
				},
				speed 		: {
					desktop 	: 0.15,
					tablet		: 0.15,
					mobile		: 0.15,
				},
				invert 			: false,
				breakpoints		: {
					'mobile'	: 768,
					'tablet' 	: 1024,
				}
			};

			var plugin = this;

			plugin.opts = {};

			var $window				= null,
				$element			= $(element),
				$document			= $(document),
				
				winHeight 			= $(window).height(),
				docHeight 			= $document.height(),
				toWindowBottom 		= null,
				toItemTop 			= null,
				pToItemTop 			= null,
				toItemBottom 		= null,
				pToItemBottom 		= null,
				toTopFromMiddle 	= null,
				elementHeight		= null,
				elementWidth		= null,

				speed 				= null,
				elementX 			= null,
				mouseX 			 	= null,
				elementY 			= null,
				mouseY 			 	= null,
				coeff 				= 50,

				latestKnownScrollY  = -1,
				currentScrollY 		= 0,
				ticking 			= false,
				updateAF			= null;


			plugin.init = function() {
				plugin.opts = $.extend(true, {}, defaults, options);

				plugin._construct();
			};

			plugin._construct = function() {

				$window			= plugin.opts.scope;
				currentScrollY 	= $window.scrollTop();

				plugin.setSpeed( plugin.opts.speed.desktop );

				plugin.setup();
				plugin.events();
				plugin.requestTick();
			};

			plugin.setup = function() {

				winHeight 		= $(window).height();

				elementHeight		= $element.height();
				elementWidth		= $element.width();

				toItemTop 		= $element.offset().top;
				toItemBottom 	= toItemTop + elementHeight;
				toWindowBottom 	= $window.scrollTop();
				toTopFromMiddle = toItemTop + elementHeight / 2;

				if ( plugin.isTablet() ) {
						plugin.setSpeed( plugin.opts.speed.tablet );
				}

				if ( plugin.isMobile() ) {
						plugin.setSpeed( plugin.opts.speed.mobile );
				}

				if ( ! plugin.isDesktop() ) {
					$window.off( 'mousemove', plugin.onMouseMove );
				} else {
					$window.on( 'mousemove', plugin.onMouseMove );
				}
				
			};

			plugin.isTablet = function() {
				return $window.width() < plugin.opts.breakpoints['tablet'] && $window.width() >= plugin.opts.breakpoints['mobile'];
			};

			plugin.isMobile = function() {
				return $window.width() < plugin.opts.breakpoints['tablet'] && $window.width() < plugin.opts.breakpoints['mobile'];
			};

			plugin.isDesktop = function() {
				return $window.width() > plugin.opts.breakpoints['tablet'];
			};

			plugin.events = function() {
				
				$window.on( 'resize', plugin.setup );

				if ( 'mouse' === plugin.opts.type && plugin.isDesktop() ) {
					$window.on( 'mousemove', plugin.onMouseMove );
				}

				if ( 'scroll' === plugin.opts.type ) {
					$window.on( 'scroll', plugin.onScroll );
				}

			};

			plugin.onMouseMove = function( e ) {
				mouseX = e.clientX;
				mouseY = e.clientY;
				plugin.requestTick();
			};

			plugin.onScroll = function() {
				currentScrollY = $window.scrollTop();
				plugin.requestTick();
			};

			plugin.requestTick = function() {
				if ( ! ticking ) {
					updateAF = requestAnimationFrame( plugin.update ); }
				ticking = true;
			};

			plugin.setSpeed = function( _speed ) {
				speed = parseFloat( _speed );
				speed = ( plugin.opts.invert && speed > 0 ) ? -speed : speed;
			};

			plugin.update = function() {

				ticking = false;

				if ( $element.visible( true, false, 'vertical' ) ) {
					switch ( plugin.opts.type ) {
						case 'mouse' :
							plugin.pan();
							break;
						default :
							plugin.move();
					}
				}
			};

			plugin.move = function() {

				if ( plugin.opts.scroll.responsive && $window.width() < plugin.opts.breakpoints[ plugin.opts.scroll.responsive ] ) {
					plugin.clearProps();
					return;
				}

				if ( latestKnownScrollY !== currentScrollY ) {

					latestKnownScrollY = currentScrollY;

					coeff = 100 * ( 1 - speed + 0.1 );
					
					var	winHeight 			= $(window).height();
						middleOfScreen 		= currentScrollY + winHeight / 2,
						middleToMiddle 		= middleOfScreen - toTopFromMiddle,
						middleToTop 		= middleOfScreen - toItemTop,
						toWindowBottom 		= currentScrollY + winHeight,
						pToItemTop 			= $element.offset().top,
						pToItemBottom 		= pToItemTop + elementHeight,
						pxSinceVisible 		= currentScrollY - toItemTop + winHeight,
						pPxSinceVisible 	= currentScrollY - pToItemTop + winHeight;

						
					elementY = ( 'middle' === plugin.opts.scroll.relative ) ? middleToMiddle : currentScrollY;
					
					TweenMax.set( $element, { y : elementY * speed, x: 0 } );
				}
			};

			plugin.pan = function() {

				if ( ! mouseX || ! mouseY ) {
					return;
				}

				var ecx = ( $element.offset().left + $element.outerWidth() / 2 ),
					ecy = ( $element.offset().top - $window.scrollTop() + $element.outerHeight() / 2 ),

					vcx = ( $window.width() / 2 ),
					vcy = ( $(window).height() / 2 );

				var rx = ( 'viewport' === plugin.opts.mouse.relative ) ? vcx : ecx,
					ry = ( 'viewport' === plugin.opts.mouse.relative ) ? vcy : ecy;

				var scx = ( $window.width() - $window.outerWidth() ) / 2,
					mx 	= ( mouseX - rx ),
			
					scy = ( $(window).height() - $(window).outerHeight() ) / 2,
					my = ( mouseY - ry );

				elementX = plugin.opts.inverse ? scx - mx : scx + mx;
				elementY = plugin.opts.inverse ? scy - my : scy + my;

				elementX *= speed * 0.1;
				elementY *= speed * 0.1;

				var args = { ease: Power0.easeInOut };

				if ( 'vertical' === plugin.opts.mouse.axis || 'both' === plugin.opts.mouse.axis ) {
					args.y = elementY;
				}

				if ( 'horizontal' === plugin.opts.mouse.axis || 'both' === plugin.opts.mouse.axis ) {
					args.x = elementX;
				}

				if ( plugin.opts.mouse.distance ) {

					var d = Math.floor( Math.sqrt( Math.pow( mouseX - ( $element.offset().left + ( $element.width() / 2 ) ), 2) + Math.pow( mouseY - ( $element.offset().top - $window.scrollTop() + ( $element.height() / 2 ) ), 2 ) ) );

					if ( d > plugin.opts.mouse.distance ) {
						TweenMax.to( $element, 0.3, { x: 0, y: 0, ease : Power0.easeInOut } );
					} else {
						TweenMax.to( $element, 0.3, args );
					}
				} else {
					TweenMax.to( $element, 0.3, args );
				}
			}

			plugin.clearProps = function() {
				TweenMax.set( $element, { clearProps: "all" } );
			};

			plugin.destroy = function() {

				cancelAnimationFrame( updateAF );

				plugin.clearProps();

				$window
					.off( 'scroll', plugin.onScroll )
					.off( 'mousemove', plugin.onMouseMove )
					.off( 'resize', plugin.setup );

				$element.removeData( 'parallaxElement' );

			};

			plugin.init();

		};

		$.fn.parallaxElement = function(options) {
			return this.each(function() {
				if ( undefined === $(this).data('parallaxElement') ) {
					var plugin = new $.parallaxElement(this, options);
					$(this).data('parallaxElement', plugin);
				}
			});
		};

	}

)( jQuery, window, document );