// -- Timeline Plugin
// @license Timeline v1.0.0 | MIT | Namogo 2017 | https://www.namogo.com
// --------------------------------
;(
	function( $, window, document, undefined ) {

		$.timeline = function(element, options) {

			var defaults = {
				scope 	: $(window),
				points 	: '.timeline-item__point',
			};

			var plugin = this;

			plugin.opts = {};

			var $window			= null,
				$viewport		= $(window),
				$element		= $(element),

				dragging 		= false,
				scrolling 		= false,
				resizing 		= false,

				latestKnownScrollY  	= -1,
				latestKnownWindowHeight = -1,
				currentScrollY 			= 0,
				currentWindowHeight 	= 0,
				ticking 				= false,
				updateAF				= null,

				$line 			= $element.find( '.ee-timeline__line' ),
				$progress		= $line.find( '.ee-timeline__line__inner' ),
				$cards			= $element.find( '.timeline-item' );


			plugin.init = function() {
				plugin.opts = $.extend({}, defaults, options);
				plugin._construct();
			};

			plugin._construct = function() {

				$window				= plugin.opts.scope;
				currentScrollY 		= $window.scrollTop();
				currentWindowHeight = $(window).height();

				plugin.events();
				plugin.requestTick();
				plugin.animateCards();

			};

			plugin.requestTick = function() {
				if ( ! ticking ) {
					updateAF = requestAnimationFrame( plugin.refresh );
				}
				ticking = true;
			};

			plugin.animateCards = function() {
				$cards.each( function() {
					if( $(this).offset().top <= $window.scrollTop() + $viewport.outerHeight() * 0.95 && $(this).hasClass('is--hidden') ) {
						$(this).removeClass('is--hidden').addClass('bounce-in');
					}
				});
			};

			plugin.events = function() {

				$window.on('scroll', plugin.onScroll );
				$(window).on('resize', plugin.onResize );

			};

			plugin.onScroll = function() {
				currentScrollY = $window.scrollTop();

				plugin.requestTick();
				plugin.animateCards();
			};

			plugin.onResize = function() {
				currentScrollY = $window.scrollTop();
				currentWindowHeight = $window.height();

				plugin.requestTick();
			};

			plugin.setup = function() {
				
				$line.css({
					'top' 		: $cards.first().find( plugin.opts.points ).offset().top - $cards.first().offset().top,
					'bottom'	: ( $element.offset().top + $element.outerHeight() ) - $cards.last().find( plugin.opts.points ).offset().top
				});

			};

			plugin.refresh = function() {

				ticking = false;

				if ( latestKnownWindowHeight !== currentWindowHeight ) {
					plugin.setup();
				}

				if ( ( latestKnownScrollY !== currentScrollY ) || ( latestKnownWindowHeight !== currentWindowHeight ) ) {

					latestKnownScrollY 		= currentScrollY;
					latestKnownWindowHeight = currentWindowHeight;

					plugin.progress();
				}
			}

			plugin.progress = function() {

				var _last_pos = $cards.last().find( plugin.opts.points ).offset().top,
					_pos = ( $window.scrollTop() - $progress.offset().top ) + ( $viewport.outerHeight() / 2 );

					if ( _last_pos <= ( $window.scrollTop() + $viewport.outerHeight() / 2 ) ) {
						_pos = _last_pos - $progress.offset().top;
					}

					$progress.css({
						'height' : _pos + 'px'
					});

				$cards.each( function() {
					if ( $(this).find( plugin.opts.points ).offset().top < ( $window.scrollTop() + $viewport.outerHeight() / 2 ) ) {
						$(this).addClass('is--focused');
					} else {
						$(this).removeClass('is--focused');
					}
				});

			};

			plugin.destroy = function() {

				// $window.off( 'scroll', plugin.update );
				$element.removeData( 'timeline' );

			};

			plugin.init();

		};

		$.fn.timeline = function(options) {

			return this.each(function() {

				$.fn.timeline.destroy = function() {
					if( 'undefined' !== typeof( plugin ) ) {
						$(this).data( 'timeline' ).destroy();
						$(this).removeData( 'timeline' );
					}
				}

				if (undefined === $(this).data('timeline')) {
					var plugin = new $.timeline(this, options);
					$(this).data('timeline', plugin);
				}
			});

		};

	}

)( jQuery, window, document );